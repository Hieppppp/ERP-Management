let stockDataTable = "";
// let stockData = [];
let dataInventory = inventoryData;
let totalStock = 0;
$(function (e) {
    stockDataTable = $("#stock_table").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataInventory,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return data.batch_code;
                },
            },
            {
                data: function (data, type, row) {
                    return data.received_date;
                },
            },
            {
                data: function (data, type, row) {
                    return data.shelves_code;
                },
            },
            {
                data: function (data, type, row) {
                    return data.warehouse_code;
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location);
                },
            },
            {
                data: function (data, type, row) {
                    return data.quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return data.pick_quantity || 0;
                },
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
    });

    calculateTotalStock();
});

function calculateTotalStock() {
    totalStock = 0;
    dataInventory.forEach(function (item) {
        totalStock += parseFloat(item.pick_quantity || 0);
    });
    $("#totalStock").text(totalStock);
}
