let inventoryAddTable = '';
let inventoryAddData = [];
let dataSupplier = [];
let isSelectOneSupplier = true;
let dataWarehouse = {};

let selectedSupplier = {};
let selectedWarehouse = {};
let dataShelve = [];
$(document).ready(function () {
    inventoryAddTable = $("#inventory-add-table").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: inventoryAddData,
        ordering: false,
        info: false,
        paging: false,
        searching: false,
        columns: [
            {
                data: "code",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location);
                },
            },
            {
                data: function (data, type, row) {
                    return `<input type="number" class="form-control" name="quantity[]" required id='quantity_${data.id}'>`;
                },
                className: "wd-xs-30p",
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeSelectedShelve('${data.id}')"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
    });

    $("#supplier_name").on("click", function () {
        showLargeModal(
            trans("translation.supplier.selectSupplier"),
            `/supplier/select?productId=${productId}`
        );
    });

    $("#warehouse_name").on("click", function () {
        showLargeModal(
            trans("translation.warehouse.selectWarehouse"),
            "/warehouse/select"
        );
    });

    $("#add-product-inventory").validate({
        onfocusout: false,
        rules: {
            'quantity[]': {
                greaterThan: 0
            }
        },
    })
});

function showAddInventoryModal () {
    clearAddInventoryData();
    $("#add-inventory-modal").modal("show");
}

function refreshSupplier (data) {
    selectedSupplier = data[0];
    dataSupplier = [selectedSupplier];
    closeLargeModal();
    $("#supplier_name").val(selectedSupplier.name);
    if ($('#supplier_name').valid()) {
        $('#supplier_name-error').remove();
        $('#supplier_name').removeClass('error');
    }
}

function renderWarehouse (data) {
    if (dataWarehouse.id != data.id) {
        dataShelve = [];
        inventoryAddTable.clear();
        inventoryAddTable.rows.add(dataShelve);
        inventoryAddTable.draw();
    }
    dataWarehouse = data;
    closeLargeModal();
    $("#warehouse_name").val(data.address);
    if ($('#warehouse_name').valid()) {
        $('#warehouse_name-error').remove();
        $('#warehouse_name').removeClass('error');
    }
}

function selectShelves () {
    if (dataWarehouse.length <= 0) {
        return notification(
            "error",
            trans("message.pleaseSelectAtLeast1item", {
                item: trans("translation.menu.warehouse"),
            })
        );
    }
    showLargeModal(
        trans("translation.shelve.selectShelve"),
        "/shelve/select"
    );
}

function renderShelve (data) {
    dataShelve = data;
    updateListShelve();
    closeLargeModal();
}

function removeSelectedShelve (id) {
    dataShelve = dataShelve.filter((item) => item.id != id);
    updateListShelve();
}

function updateListShelve () {
    var existingIds = inventoryAddTable
        .data()
        .toArray()
        .map((item) => item.id);
    var newIds = dataShelve.map((item) => item.id);
    dataShelve.forEach(function (item) {
        var row = inventoryAddTable.row(function (idx, data, node) {
            return data.id === item.id;
        });

        if (!row.any()) {
            inventoryAddTable.row.add(item).draw(false);
        }
    });

    existingIds.forEach(function (id) {
        if (!newIds.includes(id)) {
            inventoryAddTable
                .row(function (idx, data, node) {
                    return data.id === id;
                })
                .remove()
                .draw(false);
        }
    });
}

function clearAddInventoryData () {
    dataShelve = [];
    inventoryAddTable.clear();
    inventoryAddTable.rows.add(dataShelve);
    inventoryAddTable.draw();
    selectedSupplier = {};
    selectedWarehouse = {};
    dataWarehouse = [];
    inventoryAddData = [];
    dataSupplier = [];
    $("#supplier_name").val('');
    $('#supplier_name-error').remove();
    $('#supplier_name').removeClass('error');
    $("#warehouse_name").val('');
    $('#warehouse_name-error').remove();
    $('#warehouse_name').removeClass('error');
}

function confirmAddInventory () {
    if ($("#add-product-inventory").valid()) {
        if (dataShelve.length <= 0) {
            hideLoadingSpinner();
            notification('error', trans('message.pleaseSelectAtLeast1item', { item: trans('translation.shelve.shelve') }));
            return;
        }
        $(`#confirmModal`).modal('show');
        $(`#confirmModal_title`).text(trans('translation.modal.confirm'));
        $(`#confirmModal_body`).html(`<p>${trans('message.confirm')}</p>`);
        $(`#confirmModalSubmitBtn`).attr('onclick', 'addProductInventory()');
    };
}

function addProductInventory () {
    showLoadingSpinner();
    let data = {};
    data.product_id = productId;
    data.supplier_id = selectedSupplier.id;
    data.warehouse_id = dataWarehouse.id;
    data.shelves = [];
    dataShelve.forEach((item, index) => {
        data.shelves.push({
            id: item.id,
            quantity: $(`#quantity_${item.id}`).val()
        })
    });
    $.ajax({
        url: `/api/v1/inventory`,
        method: "POST",
        processData: false,
        contentType: false,
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            ),
            "Content-Type": "application/json",
        },
        success: function (response) {
            hideLoadingSpinner();
            inventoryDataTable.draw(false);
            notification("success", trans("message.success"));
            modalStack = [];
            $(`#confirmModal`).modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            hideLoadingSpinner();
            modalStack = [];
            $(`#confirmModal`).modal('hide');
            if (jqXHR?.responseJSON?.message) {
                return notification(
                    "error",
                    jqXHR.responseJSON?.message
                );
            }
            notification("error", trans("message.updateFailed"));
        },
    });
}
