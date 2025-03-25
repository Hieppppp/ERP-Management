let dataShelve = [];
let dataSupplier = purchaseOrder?.supplier;
let isDataChanged = false;
let dataWarehouse = { id: purchaseOrder?.warehouse_id };
function addShelve () {
    showLargeModal(trans('translation.shelve.selectShelve'), '/shelve/select')
}
function renderShelve (data) {
    isDataChanged = true;
    dataShelve = data;
    shelveDatatable.clear();
    shelveDatatable.rows.add(dataShelve);
    shelveDatatable.draw();
    closeLargeModal();
}
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
                    return `<input type="number" class="form-control" name="quantity[]" min="0" required id='quantity_${data.id}'>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `
                <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeShelve('${data.id}', this)"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ],
    });
    $('#shelve_datatable').on('input', 'input[name="quantity[]"]', function () {
        calculateTotalAmount()
    });
    $('input, textarea, select').on('change input', function (event) {
        isDataChanged = true
    });

    $("#stock_product").validate({
        rules: {
            "quantity[]": {
                required: true,
                min: 1
            }
        },
        invalidHandler: function (event, validator) {
            if (validator.numberOfInvalids()) {
                var invalidElement = $(validator.errorList[0].element);
                if (invalidElement.closest('table').length) {
                    var row = invalidElement.closest('tr');
                    var scrollBody = $('#shelve_datatable').parent().first();
                    var scrollTop = scrollBody.scrollTop();
                    var rowTop = $(row).position().top;
                    scrollBody.scrollTop(scrollTop + rowTop - 50);
                }
            }
        }
    });
    let sku = getSku(purchaseOrder?.products[0]?.suppliers);
    $('#sku').val(sku);
});

function back () {
    if (isDataChanged) {
        showBackModal(`/purchase-order/${purchaseOrder?.id}`)
    } else {
        window.location.href = `/purchase-order/${purchaseOrder?.id}`;
    }
}

function removeShelve (id, e) {
    isDataChanged = true;
    dataShelve = dataShelve.filter(el => el.id != id);
    var row = $(e).closest('tr');
    shelveDatatable.row(row).remove().draw();
    calculateTotalAmount()
}

function calculateTotalAmount () {
    var total = 0;
    $('input[name="quantity[]"]').each(function () {
        var quantity = Number($(this).val()) || 0;
        total += quantity;
    });
    $('#totalAmount').text(total);
}

function save () {
    if (dataShelve.length <= 0) {
        notification('error', trans('message.pleaseSelectAtLeast1item', { item: trans('translation.shelve.shelve') }));
        return;
    }
    if (!checkQuantity()) {
        notification('error', trans('message.total_quantity_on_shelves_equals_received_quantity'));
        return
    }
    showConfirmModal('submit()', '', trans('message.confirmSave'))
}
function submit () {
    showLoadingSpinner();
    if ($('#stock_product').valid()) {
        if (!checkQuantity()) {
            notification('error', trans('message.total_quantity_on_shelves_equals_received_quantity'));
            hideLoadingSpinner();
            closeConfirmModal();
            return;
        }
        var data = {};
        if (dataShelve.length > 0) {
            data.shelves = dataShelve.map((item, index) => {
                return {
                    id: item.id,
                    quantity: $(`#quantity_${item.id}`).val()
                }
            });
        }
        $.ajax({
            processData: false,
            contentType: false,
            url: `/api/v1/purchase-order/${purchaseOrder?.id}/put-product/${purchaseOrder?.products[0]?.id}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(data),
            success: function (response) {
                hideLoadingSpinner()
                window.location.href = `/purchase-order/${purchaseOrder.id}`;
                notification("success", trans("message.success"));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                hideLoadingSpinner()
                if (jqXHR.responseJSON?.message) {
                    return notification('error', jqXHR.responseJSON?.message);
                }
                notification("error", trans("message.error"));
            }
        });
    } else {
        hideLoadingSpinner();
    }
    closeConfirmModal();
}

function checkQuantity () {
    let totalQuantity = 0;
    $('input[name="quantity[]"]').each(function () {
        var quantity = parseFloat($(this).val()) || 0;
        totalQuantity += quantity;
    });
    return totalQuantity == purchaseOrder?.products[0]?.pivot?.received_quantity;
}
function getSku (data) {
    data = data.filter((item) => {
        return item.id == dataSupplier.id;
    });
    if (data.length > 0) {
        return data[0]?.pivot?.sku;
    }
    return '';
}
