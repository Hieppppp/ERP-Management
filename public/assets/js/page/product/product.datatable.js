let productTable;
let uniqueIdCounter = 0;
let filterDatas = {};
let dataSupplier = [];
let productId = null;
let isSelectOneSupplier = true;
const params = new URLSearchParams(window.location.search);
const orderByParam = params.get('orderBy');
const orderTypeParam = params.get('orderType');
const quantityFilterParam = params.get('quantityFilter');
let columnIndex = 0;
let orderType = 'desc';

if (orderByParam == 'quantity' && ['desc', 'asc'].includes(orderTypeParam)) {
    columnIndex = 7;
    orderType = orderTypeParam;
}
if (quantityFilterParam == 'below') {
    filterDatas = {
        filters: [
            {
                column: 'quantity',
                condition: 'below',
                value: 'minimum'
            }
        ]
    }
}

$(function (e) {
    productTable = $("#product-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [
            [columnIndex, orderType]
        ],
        language: datatableLanguage(),
        ajax: {
            url: "/api/v1/filter-product",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function (d) {
                $.extend(d, filterDatas);
            },
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
                data: "code",
                name: "code",
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`;
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
                data: "sku",
                name: "sku",
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
                    return data?.quantity ?? 0;
                },
                name: "quantity",
            },
            {
                data: function (data, type, row) {
                    let actionDelete = '';
                    if ((data.quantity ?? 0) <= 0) {
                        actionDelete = `<li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteProduct" onclick="confirmRemove(${data.id})">${trans('translation.delete')}</a></li>`
                    }
                    return `<div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle rounded-pill"
                            data-bs-toggle="dropdown" aria-expanded="false"> ${trans('translation.action')} </button>
                        <ul class="dropdown-menu" style="">
                            <li><a class="dropdown-item" href="/product/${data.id}">${trans('translation.detail')}</a></li>
                            <li><a class="dropdown-item" href="/product/${data.id}/edit">${trans('translation.edit')}</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="createPurchaseOrder(${data.id})">${trans('translation.purchaseOrder.create')}</a></li>
                            <li><a class="dropdown-item" href="/product/${data.id}/inventory"">${trans('translation.product.inventory')}</a></li>
                            ${actionDelete}
                        </ul>
                    </div>`;
                },
                width: "10%",
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image text-center",
                targets: 1,
            },
            {
                className: "text-center",
                targets: -1,
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            })
        },
        createdRow: function (row, data, dataIndex) {
            applyQuantityClasses(row, data?.quantity ?? 0, data.min_quantity);
        },
    });

    $("#product-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            productTable.search("").draw();
        }
    });

    const dataFilterSettings = {
        code: {
            text: trans("translation.product.code"),
            value: "products.code",
            options: [
                { text: trans("translation.filter.contain"), value: "contain" },
                { text: trans("translation.filter.notContain"), value: "noContain" },
            ],
            handler: function (column, condition, value) {
                if (condition === "contain") {
                    condition = "LIKE";
                    value = `%${value}%`;
                } else if (condition === "noContain") {
                    condition = "NOT LIKE";
                    value = `%${value}%`;
                }
                return { column, condition, value };
            },
        },
        name: {
            text: trans("translation.product.name"),
            value: "products.name",
            options: [
                { text: trans("translation.filter.contain"), value: "contain" },
                { text: trans("translation.filter.notContain"), value: "noContain" },
                { text: trans("translation.filter.startWith"), value: "startWith" },
                { text: trans("translation.filter.endWith"), value: "endWith" },
            ],
            handler: function (column, condition, value) {
                switch (condition) {
                    case "noContain":
                        condition = "NOT LIKE";
                        value = `%${value}%`;
                        break;
                    case "startWith":
                        condition = "LIKE";
                        value = `${value}%`;
                        break;
                    case "endWith":
                        condition = "LIKE";
                        value = `%${value}`;
                        break;
                    default:
                        condition = "LIKE";
                        value = `%${value}%`;
                        break;
                }
                return { column, condition, value };
            },
        },
        category: {
            text: trans("translation.product.category"),
            value: "products.category_id",
            options: [
                { text: trans("translation.filter.is"), value: "=" },
                { text: trans("translation.filter.isNot"), value: "!=" },
            ],
            type: "select2",
            api: "/api/v1/category/search",
        },
        unitPrice: {
            text: trans("translation.product.unitPrice"),
            value: "products.unit_price",
            options: [
                { text: trans("translation.filter.equal"), value: "=" },
                { text: trans("translation.filter.notEqual"), value: "!=" },
                { text: "<=", value: "<=" },
                { text: ">=", value: ">=" },
                { text: "<", value: "<" },
                { text: ">", value: ">" }
            ],
        },
        unit: {
            text: trans("translation.product.unit"),
            value: "products.unit_id",
            options: [
                { text: trans("translation.filter.is"), value: "=" },
                { text: trans("translation.filter.isNot"), value: "!=" },
            ],
            type: "select2",
            api: "/api/v1/unit/search",
        },
        supplier: {
            text: trans("translation.product.supplier"),
            value: "supplier",
            options: [
                { text: trans("translation.filter.in"), value: "in" },
                { text: trans("translation.filter.notIn"), value: "notIn" },
            ],
            type: "select2",
            api: "/api/v1/supplier/search",
        },
        quantity: {
            text: trans("translation.product.quantity"),
            value: "quantity",
            options: [
                { text: trans("translation.filter.below"), value: "below" },
                { text: trans("translation.filter.reached"), value: "reached" },
                { text: trans("translation.filter.approaching"), value: "approaching" },
                { text: trans("translation.filter.optimal"), value: "optimal" },
            ],
            columns: [
                {
                    target: 3,
                    html: function () {
                        const thirdInput = $('<input>').attr('type', 'text').addClass('form-control').prop('disabled', true).val('minimum');
                        return thirdInput;
                    }
                }
            ]
        },
    };

    initFilter({
        containerId: 'filter-container',
        addRuleBtnId: 'add-rule',
        setting: dataFilterSettings
    });

    $('#filter-product-btn').on('click', function () {
        filterDatas = getFilterData('filter-container', dataFilterSettings);
        productTable.ajax.reload();
        $("#product-filter").modal("hide");
    });

    $('#product-filter').on('hidden.bs.modal', function (e) {
        $('#filter-btn i').removeClass('text-light');
        $('#filter-btn i').removeClass('text-primary');
        if (filterDatas.filters.length) {
            $('#filter-btn i').addClass('text-primary');
        } else {
            $('#filter-btn i').addClass('text-light');
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deleteProduct(${id})`);
}

function deleteProduct (id) {
    showLoadingSpinner();
    $.ajax({
        type: "DELETE",
        url: `/api/v1/product/${id}`,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
            productTable.draw(false);
            $("#deleteProduct").modal("hide");
            hideLoadingSpinner();
            notification("success", trans("message.deleteSuccess"));
        },
        error: function (xhr, err) {
            productTable.draw(false);
            $("#deleteProduct").modal("hide");
            hideLoadingSpinner();
            if (xhr?.responseJSON) {
                return notification('error', xhr.responseJSON?.message);
            }
            notification("error", trans("message.modal_not_found"));
        },
    });
}

function openSearchFilter () {
    $("#product-filter").modal("show");
}

function createPurchaseOrder (id) {
    productId = id;
    showLargeModal(
        trans("translation.supplier.selectSupplier"),
        `/supplier/select?productId=${id}`
    );
}

function refreshSupplier(data) {
    dataSupplier = data; 
    const supplierId = dataSupplier[0].id; 
    if (supplierId && productId ) {
        window.location.href = `/purchase-order/create?supplierId=${supplierId}&productId=${productId}`;
    }
    return;
}


