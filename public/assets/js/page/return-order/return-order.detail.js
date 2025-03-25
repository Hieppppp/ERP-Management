let productReturnTable;
$(document).ready(function () {
    initDatePicker(["scheduled_date"]);

    productReturnTable = $("#return_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: returnOrderDetails,
        ordering: false,
        paging: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return `${data.product_locations?.product.code}`;
                },
                className: "text-start",
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product_locations?.product?.deleted_at) {
                        $(td).addClass('deleted-text-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.product_locations?.product?.images[0]) {
                        imageUrl = data.product_locations.product.images[0].image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product_locations?.product?.deleted_at) {
                        $(td).addClass('deleted-image-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return `<a href="/product/${data.product_locations?.product.id}" target="_blank">${truncateText(data.product_locations?.product?.name)}</a>`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product_locations?.product?.deleted_at) {
                        $(td).addClass('deleted-text-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.product_locations?.product?.unit?.name);
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.product_locations?.product?.category?.name);
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return data.product_locations?.shelve?.code;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return data.demand_quantity;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return data.quantity;
                },
                className: "text-start",
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
});
