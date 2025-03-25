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
                    return `<input type="number" class="form-control" value="${
                        data.pick_quantity || 0
                    }" name="quantity[]" max="${
                        data.quantity
                    }" required id='quantity_${data.id}'>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeStock('${data.id}')"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
    });

    $("#select_stock").validate({
        onfocusout: false,
        rules: {
            "quantity[]": {
                greaterThan: 0,
            },
        },
    });

    $("#stock_table").on("input", 'input[name="quantity[]"]', function () {
        if ($(this).val() === "") {
            $(this).val(0);
        }
        calculateTotalStock();
    });
    calculateTotalStock();
});

function addStock() {
    showLargeModal(
        trans("translation.inventory.selectInventory"),
        `/inventory/select?productId=${saleOrderDetail.product?.id}&warehouseId=${warehouseId}`
    );
}

function refreshInventory(data) {
    dataInventory = data;
    updateStockList();
    calculateTotalStock();
    closeLargeModal();
}

function updateStockList() {
    var existingIds = stockDataTable
        .data()
        .toArray()
        .map((item) => item.id);
    var newIds = dataInventory.map((item) => item.id);
    dataInventory.forEach(function (item) {
        var row = stockDataTable.row(function (idx, data, node) {
            return data.id === item.id;
        });

        if (!row.any()) {
            stockDataTable.row.add(item).draw(false);
        }
    });

    existingIds.forEach(function (id) {
        if (!newIds.includes(id)) {
            stockDataTable
                .row(function (idx, data, node) {
                    return data.id === id;
                })
                .remove()
                .draw(false);
        }
    });
}

function removeStock(id) {
    dataInventory = dataInventory.filter((el) => el.id != id);
    updateStockList();
    calculateTotalStock();
}

function calculateTotalStock() {
    totalStock = 0;
    dataInventory.forEach(function (item) {
        totalStock += parseFloat($(`#quantity_${item.id}`).val() || 0);
    });
    $("#totalStock").text(totalStock);
}

function confirmValidateStock() {
    showConfirmModal(
        `validateStock()`,
        "",
        trans("message.confirmValidateStock")
    );
}

function validateStock() {
    closeConfirmModal();
    if (!$("#select_stock").valid()) {
        return;
    }
    if (!dataInventory.length) {
        notification(
            "error",
            trans("message.pleaseSelectAtLeast1item", {
                item: trans("translation.menu.inventory"),
            })
        );
        return;
    }
    if (totalStock != saleOrderDetail.quantity) {
        notification("error", trans("message.totalStockQuantityNotMatch"));
        return;
    }
    showLoadingSpinner();
    const data = {};
    data.inventories = dataInventory;
    data.inventories.forEach(function (item, index) {
        const inventoryId = item.id;
        data.inventories[index].select_quantity = $(
            `#quantity_${inventoryId}`
        ).val();
    });
    $.ajax({
        processData: false,
        contentType: false,
        url: `/api/v1/sale-order/${saleOrderDetail.sale_order_id}/product/${saleOrderDetail.product_id}/add-stock`,
        method: "POST",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json",
        },
        success: function (response) {
            hideLoadingSpinner();
            window.location.href = `/sale-order/${saleOrderDetail.sale_order_id}/receipt`;
            notification("success", trans("message.success"));
        },
        error: function (jqXHR, textStatus, errorThrown) {
            hideLoadingSpinner();
            if (jqXHR?.responseJSON) {
                if (Array.isArray(jqXHR.responseJSON?.message)) {
                    return notification("error", jqXHR.responseJSON.message[0]);
                }
            }
        },
    });
}
