if (typeof productSelectTable == "undefined") {
    let productSelectTable;
}
if (typeof productIds == "undefined") {
    let productIds;
}
productIds = JSON.parse(JSON.stringify(dataProduct));
if (typeof productParams == "undefined") {
    let productParams;
}
productParams = [];
if (typeof productIdInDatabase != "undefined") {
    productParams.push(`excludeProductIds=${productIdInDatabase}`);
}
if (
    typeof includeSupplierInProductList != "undefined" &&
    includeSupplierInProductList &&
    typeof supplierIdForProductList != "undefined" &&
    supplierIdForProductList.length > 0
) {
    productParams.push(
        `includeSupplierIds=${supplierIdForProductList.join(",")}`
    );
}
$(function (e) {
    productSelectTable = $("#product-select").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[1, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: `/api/v1/product?${productParams.join("&")}`,
            type: "GET",
            error: function () {
                notification(
                    "error",
                    trans(
                        "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                    )
                );
            },
        },
        columns: [
            {
                data: null,
            },
            {
                data: "code",
                name: "code",
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src = "${imageUrl}" width = "50" height = "50" class="img-overlay-light-box">`;
                },
                name: "image",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
                name: "category_name",
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
                name: "unit_price",
                className: "text-end",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
                name: "unit_name",
            },
            {
                data: function (data, type, row) {
                    return data.quantity || 0;
                },
                name: "quantity",
            },
            {
                data: function (data, type, row) {
                    return data.max_quantity;
                },
                name: "max_quantity",
            },
            {
                data: function (data, type, row) {
                    return data.min_quantity;
                },
                name: "min_quantity",
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image text-center",
                targets: 2,
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
            });
            if (typeof isSelectOne != "undefined" && isSelectOne) {
                selectOne(settings.sTableId, productSelectTable);
            }
        },
        createdRow: (row, data, dataIndex) => {
            addCheckBoxDataTable(row);
            applyQuantityClasses(row, data?.quantity ?? 0, data.min_quantity);
        },
        rowCallback: function (row, data) {
            if (
                productIds.some((item) => {
                    return item.id === data.id;
                })
            ) {
                $('input[name="checkBoxSelected"]', row).prop("checked", true);
            }
        },
    });

    $("#product-select_filter input").on("input", function () {
        if (!$(this).val()) {
            productTable.search("").draw();
        }
    });
    $("#product-select tbody").on(
        "change",
        'input[name="checkBoxSelected"]',
        function () {
            let data = productSelectTable.row($(this).closest("tr")).data();
            if (
                typeof isSelectOneProduct != "undefined" &&
                isSelectOneProduct
            ) {
                if ($(this).is(":checked")) {
                    $(
                        '#product-select tbody input[name="checkBoxSelected"]'
                    ).prop("checked", false);
                    $(this).prop("checked", true);
                    productIds = [data];
                } else {
                    productIds = [];
                }
            } else {
                if ($(this).is(":checked")) {
                    productIds.push(data);
                } else {
                    productIds = productIds.filter((el) => el.id !== data.id);
                }
            }
        }
    );
});

function selectProduct() {
    if (productIds.length > 0) {
        refreshProduct(productIds);
    } else {
        notification(
            "error",
            trans("message.pleaseSelectAtLeast1item", {
                item: trans("translation.menu.product"),
            })
        );
    }
}
