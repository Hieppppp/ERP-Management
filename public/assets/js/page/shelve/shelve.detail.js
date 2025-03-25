$(function (e) {
    shelveTable = $("#shelve-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: products,
        search: true,
        paging: true,
        info: true,
        ordering: true,
        searching: true,
        columns: [
            {
                data: function (data, type, row) {
                    return hasPermission('product') ? `<a href="/product/${data.id}" target="_blank">${truncateText(data.code, 20)}</a>` : truncateText(data.code, 20);
                },
            },
            {
                data: function (data, type, row) {
                    let imageUrl = '/assets/images/no-image.png';
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50">`
                },
                className: 'text-center'
            },
            {
                data: 'name',
            },
            {
                data: function (data, type, row) {
                    return truncateText(data?.category?.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data?.unit?.name, 20);
                },
            },
            {
                data: 'total_quantity',
            },
            {
                data: function (data, type, row) {
                    return hasPermission('batch') ? `<a href="/batch/${data.purchase_order_id}" target="_blank">${data.batch_code}</a>` : data.batch_code;
                },
            },
            {
                data: 'received_date',
            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
            });
        },
    });
});
