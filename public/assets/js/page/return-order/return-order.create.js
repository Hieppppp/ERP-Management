const returnProductModal = $("#return-product-select-modal");
function openReturnProductSelection() {
    selectProducts = returnProductsData;
    productReturnSelectTable.draw(true);
    returnProductModal.modal("show");
}
let selectProducts = [];
let returnProductsData = [];
let productReturnSelectTable;
let productReturnTable;
$(document).ready(function () {
    initDatePicker([["scheduled_date", new Date()]]);
    $("#scheduled_date").datepicker("setDate", new Date()).datepicker("update");

    productReturnSelectTable = $("#return-product-select").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: productLocationData,
        ordering: false,
        searching: true,
        columns: [
            {
                data: null,
            },
            {
                data: function (data, type, row) {
                    return `${data.product.code}`;
                },
                className: "text-start",
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product.deleted_at) {
                        $(td).css('color', '#9F9F9F')
                    }
                }
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.product?.images[0]) {
                        imageUrl = data.product.images[0].image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product.deleted_at) {
                        $(td).find('img').css({
                            'filter': 'grayscale(100%)',
                            'opacity': '0.5',
                            'pointer-events': 'none'
                        });
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return `<a href="/product/${data.product.id}" target="_blank">${truncateText(data.product?.name, 20)}</a>`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product.deleted_at) {
                        $(td).find('a').css({
                            'pointer-events': 'none',
                            'color': '#9F9F9F'
                        });
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return `${truncateText(data.product?.unit?.name, 20)}`;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return `${truncateText(data.product?.category?.name, 20)}`;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return `${data.shelve.code}`;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return `${data.quantity}`;
                },
                className: "text-start",
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image text-center",
                targets: 2,
            },
        ],
        createdRow: (row) => {
            addCheckBoxDataTable(row);
        },
        rowCallback: function (row, data) {
            if (
                selectProducts.some((item) => {
                    return item.id === data.id;
                })
            ) {
                $('input[name="checkBoxSelected"]', row).prop("checked", true);
            } else {
                $('input[name="checkBoxSelected"]', row).prop("checked", false);
            }
        },
    });

    $("#return-product-select tbody").on(
        "change",
        'input[name="checkBoxSelected"]',
        function () {
            let data = productReturnSelectTable
                .row($(this).closest("tr"))
                .data();
            if ($(this).is(":checked")) {
                selectProducts.push(data);
            } else {
                selectProducts = selectProducts.filter(
                    (item) => item.id !== data.id
                );
            }
        }
    );

    productReturnTable = $("#return_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: returnProductsData,
        ordering: false,
        paging: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return `${data.product.code}`;
                },
                className: "text-start",
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product.deleted_at) {
                        $(td).addClass('deleted-text-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.product?.images[0]) {
                        imageUrl = data.product.images[0].image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product.deleted_at) {
                        $(td).addClass('deleted-image-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return `<a href="/product/${data.product.id}" target="_blank">${truncateText(data.product?.name, 20)}</a>`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product.deleted_at) {
                        $(td).addClass('deleted-text-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.product?.unit?.name, 20);
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.product?.category?.name, 20);
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return data.quantity;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return data.shelve?.code;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return `<input type="number" class="form-control " name="quantity[]" max="${data.quantity}" required id='quantity_${data.id}'>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeReturnProduct('${data.id}', this)"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image text-center",
                targets: 1,
            },
        ],
    });

    $("#return_order").validate({
        rules: {
            scheduled_date: {
                notPastDate: true,
            },
            "quantity[]": {
                required: true,
                min: 1,
                number: true,
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass("fc-datepicker")) {
                error.insertAfter(element.closest(".input-group"));
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: function (event, validator) {
            if (validator.numberOfInvalids()) {
                var invalidElement = $(validator.errorList[0].element);
                if (invalidElement.closest("table").length) {
                    var row = invalidElement.closest("tr");
                    var scrollBody = $("#return_product_datatable")
                        .parent()
                        .first();
                    var scrollTop = scrollBody.scrollTop();
                    var rowTop = $(row).position().top;
                    scrollBody.scrollTop(scrollTop + rowTop - 50);
                }
            }
        },
    });
});

function selectReturnProduct() {
    returnProductsData = selectProducts;
    returnProductModal.modal("hide");
    updateReturnProductDataTable();
}

function updateReturnProductDataTable() {
    var existingIds = productReturnTable
        .data()
        .toArray()
        .map((item) => item.id);
    var newIds = returnProductsData.map((item) => item.id);
    returnProductsData.forEach(function (item) {
        var row = productReturnTable.row(function (idx, data, node) {
            return data.id === item.id;
        });

        if (!row.any()) {
            productReturnTable.row.add(item).draw(false);
        }
    });

    existingIds.forEach(function (id) {
        if (!newIds.includes(id)) {
            productReturnTable
                .row(function (idx, data, node) {
                    return data.id === id;
                })
                .remove()
                .draw(false);
        }
    });
}

function removeReturnProduct(id) {
    returnProductsData = returnProductsData.filter((item) => item.id != id);
    updateReturnProductDataTable();
}

function confirmSendReturnOrder() {
    showConfirmModal(
        "createReturnPurchaseOrder()",
        "",
        trans("message.confirmSendPurchaseOrder")
    );
}

function createReturnPurchaseOrder() {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#return_order").valid()) {
            if (returnProductsData.length <= 0) {
                hideLoadingSpinner();
                notification(
                    "error",
                    trans("message.pleaseSelectAtLeast1item", {
                        item: trans("translation.menu.product"),
                    })
                );
                return;
            }

            var form = $("#return_order")[0];
            var formData = new FormData(form);
            returnProductsData.forEach((item, index) => {
                formData.append(
                    `products[${index}][product_location_id]`,
                    item.id
                );
                formData.append(
                    `products[${index}][demand_quantity]`,
                    $(`#quantity_${item.id}`).val()
                );
            });
            formData.append("purchase_order_id", purchaseOrder.id);

            $.ajax({
                processData: false,
                contentType: false,
                url: "/api/v1/return-order",
                method: "POST",
                data: formData,
                success: function (response) {
                    hideLoadingSpinner();
                    window.location.href = `/return-order/${response.data.id}/confirm`;
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner();
                    notification("error", trans("message.error"));
                },
            });
        } else {
            hideLoadingSpinner();
        }
    }, 100);
    closeConfirmModal();
}
