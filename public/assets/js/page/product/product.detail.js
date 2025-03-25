let dataParentProduct = productDataInDatabase.parent_products;
let dataChildProduct = productDataInDatabase.child_products;
let dataSupplier = productDataInDatabase.suppliers;
let dataShelves = productDataInDatabase?.shelves;
$(document).ready(function () {
    let taxes = [];
    if (productDataInDatabase?.taxes) {
        taxes = productDataInDatabase?.taxes.map(function (item) {
            return {
                id: item.id,
                value: item.code
            }
        })
    }
    $("#parent_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataParentProduct,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: "code",
            },
            {
                data: function (data, type, row) {
                    let imageUrl = '/assets/images/no-image.png';
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`
                },
            },
            {
                data: function (data, type, row) {
                    return `<a href="/product/${data.id}" target="_blank">${truncateText(data.name, 20)}</a>`;
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
                name: "unit_price",
                className: 'text-end'
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return data.quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return data.max_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return data.min_quantity;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
        ],
        "createdRow": function (row, data, dataIndex) {
            applyQuantityClasses(row, data?.quantity ?? 0, data.min_quantity);
        }
    });
    $("#child_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataChildProduct,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: "code",
            },
            {
                data: function (data, type, row) {
                    let imageUrl = '/assets/images/no-image.png';
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`
                },
            },
            {
                data: function (data, type, row) {
                    return `<a href="/product/${data.id}" target="_blank">${truncateText(data.name, 20)}</a>`;
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
                name: "unit_price",
                className: 'text-end'
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return null
                },
            },
            {
                data: function (data, type, row) {
                    return data.max_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return data.min_quantity;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
        ],
    });
    $("#supplier_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataSupplier,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: "code",
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return `<img src="${data.logo_url ? data.logo_url : '/assets/images/no-image.png'}" width="50" height="50" class="img-overlay-light-box">`
                },
                name: "logo",
            },
            {
                data: function (data, type, row) {
                    return `<a href="/supplier/${data.id}">${truncateText(data.name, 20)}</a>`;
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.email);
                },
                name: "email"
            },
            {
                data: "phone",
                name: "phone",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.address);
                },
                name: "address",
            },
            {
                data: function (data, type, row) {
                    return `${data?.pivot?.unit_cost}$`;
                },
                className: 'text-end'
            },
            {
                data: function (data, type, row) {
                    return data?.pivot?.sku ? data?.pivot?.sku : '';
                }
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
            {
                className: 'max-width-address w-25',
                targets: 5
            }
        ],
    });
    $("#shelve_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataShelves,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: "id",
                name: "id",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.warehouse_name, 20);
                },
                name: "warehouse_name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location);
                },
                name: "location"
            },
            {
                data: "quantity",
                name: "quantity",
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false }
        ],
    });
});
