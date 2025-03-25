let dataShelve = purchaseOrder?.purchase_product_shelves
let isDataChanged = false;
let dataSupplier = purchaseOrder?.supplier;
$(function (e) {
    shelveDatatable = $("#shelve_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataShelve,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return data.shelve.code
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.shelve.name, 20)
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.shelve.location)
                },
            },
            {
                data: function (data, type, row) {
                    return data.quantity;
                },

            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ],
    });
    let sku = getSku(purchaseOrder?.products[0]?.suppliers);
    $('#sku').val(sku);
});
function getSku (data) {
    data = data.filter((item) => {
        return item.id == dataSupplier.id;
    });
    if (data.length > 0) {
        return data[0]?.pivot?.sku;
    }
    return '';
}
