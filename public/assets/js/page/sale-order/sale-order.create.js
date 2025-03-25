let dataProduct = [];
let saleProductDataTable;
let customerIds = [];
let selectedCustomer = {};
const isSelectOneCustomer = true;
let customerTaxes = [];
let dataWarehouse = [];

$(function (e) {
    $(`#deliver_method`).select2({
        placeholder: trans("translation.placeHolder.select"),
    });
    $(`#payment_term`).select2({
        placeholder: trans("translation.placeHolder.select"),
    });
    saleProductDataTable = $("#sale_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataProduct,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return data.code;
                },
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`;
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
            },
            {
                data: "quantity",
            },
            {
                data: function (data, type, row) {
                    return `<input type="number" class="form-control" data-unit-price="${data.unit_price}" value="0" name="quantity[]" required id='quantity_${data.id}'>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
            },
            {
                data: function (data, type, row) {
                    return `<input type="number" class="form-control" data-product-id="${data.id}" name="discount[]" max="100" min="0" id='discount_${data.id}'>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `<span id='total_cost_${data.id}'>0$</span>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeProduct('${data.id}', this)"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image text-center",
                targets: 1,
            },
            {
                className: "text-end",
                targets: [-2, -4],
            },
            {
                className: "text-center",
                targets: -1,
            },
        ],
    });
    $("#sale_order_create").validate({
        rules: {
            "quantity[]": {
                required: true,
                greaterThan: 0,
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass("select2-show-search")) {
                error.insertAfter(element.next(".select2-container"));
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: function (event, validator) {
            if (validator.numberOfInvalids()) {
                var invalidElement = $(validator.errorList[0].element);
                if (invalidElement.closest("table").length) {
                    var row = invalidElement.closest("tr");
                    var scrollBody = $("#sale_product_datatable")
                        .parent()
                        .first();
                    var scrollTop = scrollBody.scrollTop();
                    var rowTop = $(row).position().top;
                    scrollBody.scrollTop(scrollTop + rowTop - 50);
                }
            }
        },
    });

    $("#sale_product_datatable").on(
        "input",
        'input[name="quantity[]"], input[name="discount[]"]',
        function () {
            var inventoryId = $(this).attr("id").split("_").pop();
            if (inventoryId) {
                var quantity = $(`#quantity_${inventoryId}`).val();
                var discount = $(`#discount_${inventoryId}`).val() || 0;
                var unit_price = $(`#quantity_${inventoryId}`).attr(
                    "data-unit-price"
                );
                if (quantity && unit_price) {
                    $(`#total_cost_${inventoryId}`).text(
                        `${numberFormat(
                            (quantity * unit_price * (100 - discount)) / 100,
                            2
                        )}$`
                    );
                    calculateTotalAmount();
                }
            }
        }
    );

    $("#customer_name").on("click", function () {
        showLargeModal(
            trans("translation.customer.select"),
            "/customer/select"
        );
    });

    $("#payment_term").on("change", function () {
        $("#payment_term").valid();
    });

    $("#deliver_method").on("change", function () {
        $("#deliver_method").valid();
    });
    calculateTotalAmount();
    initAjaxSelect2("country", "/api/v1/country/search");
    initAjaxSelect2("province", "/api/v1/province/search");
    initAjaxSelect2("city", "/api/v1/city/search");
    $("#country").on("change", function (data) {
        $(this).valid();
        if ($(this).val() != "") {
            if ($("#province").val() != null) {
                $("#province").val("initialValue");
            }
            if ($("#city").val() != null) {
                $("#city").val("initialValue");
            }
            $("#province").prop("disabled", false);
        } else {
            $("#province").prop("disabled", true);
        }
    });
    $(`#country`).on("select2:select", function (e) {
        initAjaxSelect2(
            "province",
            `/api/v1/province/search?countryId=${e.params?.data?.country_id}`
        );
    });
    $("#province").on("change", function (data) {
        $(this).valid();
        const selectedValue = $(this).val();
        if (selectedValue != "") {
            if ($("#city").val() != null) {
                $("#city").val("initialValue");
            }
            $("#city").prop("disabled", false);
        } else {
            $("#city").prop("disabled", true);
        }
    });
    $(`#province`).on("select2:select", function (e) {
        initAjaxSelect2(
            "city",
            `/api/v1/city/search?provinceId=${e.params?.data?.province_id}`
        );
    });
    $("#city").on("change", function (data) {
        $(this).valid();
    });

    $("#warehouse_name").on("click", function () {
        showLargeModal(
            trans("translation.warehouse.selectWarehouse"),
            "/warehouse/select"
        );
    });
});

function selectProductModal() {
    showLargeModal(
        trans("translation.inventory.selectProduct"),
        "/product/select"
    );
}

function refreshProduct(data) {
    dataProduct = data;
    updateOrderProductList();
    closeLargeModal();
    calculateTotalAmount();
}

function updateOrderProductList() {
    var existingIds = saleProductDataTable
        .data()
        .toArray()
        .map((item) => item.id);
    var newIds = dataProduct.map((item) => item.id);
    dataProduct.forEach(function (item) {
        var row = saleProductDataTable.row(function (idx, data, node) {
            return data.id === item.id;
        });

        if (!row.any()) {
            saleProductDataTable.row.add(item).draw(false);
        }
    });

    existingIds.forEach(function (id) {
        if (!newIds.includes(id)) {
            saleProductDataTable
                .row(function (idx, data, node) {
                    return data.id === id;
                })
                .remove()
                .draw(false);
        }
    });
}

function removeProduct(id, e) {
    dataProduct = dataProduct.filter((el) => el.id != id);
    updateOrderProductList();
    calculateTotalAmount();
}

function calculateTotalAmount() {
    var totalAmount = 0;
    $('#sale_product_datatable span[id^="total_cost_"]').each(function () {
        let totalCost = $(this).text().replace("$", "").replace(/,/g, "");
        if (
            typeof totalCost === "string" &&
            !isNaN(totalCost) &&
            !isNaN(parseFloat(totalCost))
        ) {
            totalAmount += parseFloat(totalCost);
        }
    });
    let html = "";
    if (customerTaxes.length) {
        const totalAmountBeforeTax = totalAmount;
        customerTaxes.forEach(function (value, index) {
            let taxAmount = numberFormat(
                (totalAmountBeforeTax * value.rate) / 100,
                2
            );
            totalAmount += parseFloat(taxAmount.replace(/,/g, ""));
            html += `<p>${value.code}(${value.rate}%) : ${taxAmount}$</p>`;
        });
    }
    $("#taxList").html(html);
    $("#totalAmount").text(`${numberFormat(totalAmount, 2)}$`);
}

function refreshCustomer(data) {
    selectedCustomer = data[0];
    const taxData = JSON.parse(selectedCustomer.taxes);
    customerTaxes = [];
    if (taxData[0].code !== null) {
        customerTaxes = taxData;
    }
    $("#customer_name").val(selectedCustomer.name);
    $("#invoice_address").val(selectedCustomer.email);
    $("#deliver_address").val(selectedCustomer.address);
    $("#client-address").text(selectedCustomer.address);
    $("#client-phone").text(
        `${trans("translation.customer.phoneNumber")} : ${
            selectedCustomer.phone
        }`
    );
    $("#client-email").text(
        `${trans("translation.customer.email")} : ${selectedCustomer.email}`
    );
    closeLargeModal();
    $("#customer_name").valid();
    $("#deliver_address").valid();
    calculateTotalAmount();
}

function cancelCreate() {
    showBackModal("/sale-order");
}

function confirmSendSaleOrder() {
    showConfirmModal(
        `createSaleOrder(${confirmStatus})`,
        "",
        trans("message.confirmSendSaleOrder")
    );
}

function confirmCreateSaleOrder() {
    showConfirmModal(
        `createSaleOrder(${draftStatus})`,
        "",
        trans("message.confirmCreateSaleOrder")
    );
}

function createSaleOrder(status) {
    closeConfirmModal();
    if ($("#sale_order_create").valid()) {
        showLoadingSpinner();
        if (!dataProduct.length) {
            hideLoadingSpinner();
            notification(
                "error",
                trans("message.pleaseSelectAtLeast1item", {
                    item: trans("translation.menu.product"),
                })
            );
            return;
        }
        let data = {};
        data.customer_id = selectedCustomer.id;
        data.customer_email = selectedCustomer.email;
        data.customer_phone = selectedCustomer.phone;
        data.deliver_address = $("#deliver_address").val();
        data.customer_address = selectedCustomer.address;
        data.delivery_method = $("#deliver_method").val();
        data.payment_term = $("#payment_term").val();
        data.warehouse_id = $("#warehouse_id").val();
        data.products = dataProduct;
        data.products.forEach(function (item, index) {
            const inventoryId = item.id;
            data.products[index].order_quantity = $(
                `#quantity_${inventoryId}`
            ).val();
            data.products[index].discount_amount =
                $(`#discount_${inventoryId}`).val() || 0;
        });
        data.tax_data = customerTaxes;
        data.order_status = status;
        data.note = $("#note").val();
        $.ajax({
            processData: false,
            contentType: false,
            url: "/api/v1/sale-order",
            method: "POST",
            data: JSON.stringify(data),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "Content-Type": "application/json",
            },
            success: function (response) {
                hideLoadingSpinner();
                window.location.href =
                    status == 1
                        ? "/sale-order"
                        : `/sale-order/${response.data.id}/receipt`;
                notification("success", trans("message.success"));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                hideLoadingSpinner();
                notification("error", trans("message.error"));
            },
        });
    }
}

function showModalUpdateAddress() {
    $("#update-address").modal("show");
}

function showModalCreate(id) {
    if (id == "country") {
        showSmallModal_v2(
            trans("translation.country.create"),
            `/country/create`
        );
    }
    if (id == "province") {
        showSmallModal(
            trans("translation.province.create"),
            `/province/create`
        );
    }
    if (id == "city") {
        showSmallModal(trans("translation.city.create"), `/city/create`);
    }
}

function createNewAddress() {
    if ($("#createNewAddress").valid()) {
        let fullAddress = "";
        fullAddress +=
            $("#detail_address").val() +
            ", " +
            $("#city").val() +
            ", " +
            $("#province").val() +
            ", " +
            $("#postal_code").val() +
            ", " +
            $("#country").val();
        $("#deliver_address").val(fullAddress);
        $("#update-address").modal("hide");
    }
}

function renderWarehouse(data) {
    if (data?.id != dataWarehouse.id) {
        dataWarehouse = data;
        $("#warehouse_id").val(data.id);
        $("#warehouse_name").val(data.address);
        $("#warehouse_name").valid();
    }
    closeLargeModal();
}
