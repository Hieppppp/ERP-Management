let dataShelve = [];
let isDataChanged = false;
let filterByWarehouse = true;
let shelveAddTable;
let excludeShelve = [productLocation?.shelve_id];
$(function (e) {
    shelveAddTable = $("#shelve-add-table").DataTable({
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
                data: 'code',
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location);
                },
                name: "location",
                className: 'max-width-address',
            },
            {
                data: function (data, type, row) {
                    return data.quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return `<input type="number" class="form-control" name="quantity[]" required id='quantity_${data.id}' min="0">`;
                },
            },
            {
                data: function (data, type, row) {
                    return `<button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeShelve('${data.id}', this)"><i class="fe fe-trash"></i></button>`;
                }
            },
        ]
    });
    $("#movement").validate({
        rules: {
            "quantity[]": {
                required: true,
                greaterThan: 0
            }
        },
        invalidHandler: function (event, validator) {
            if (validator.numberOfInvalids()) {
                var invalidElement = $(validator.errorList[0].element);
                if (invalidElement.closest('table').length) {
                    var row = invalidElement.closest('tr');
                    var scrollBody = $('#shelve-add-table').parent().first();
                    var scrollTop = scrollBody.scrollTop();
                    var rowTop = $(row).position().top;
                    scrollBody.scrollTop(scrollTop + rowTop - 50);
                }
            }
        }
    });
});

function addShelve () {
    showLargeModal(trans('translation.shelve.selectShelve'), '/shelve/select')
}

function renderShelve (data) {
    if (data !== dataShelve) isDataChanged = true;
    const currentDataMap = new Set(dataShelve.map(item => item.id));
    const newDataMap = new Set(data.map(newItem => {
        if (!currentDataMap.has(newItem.id)) {
            shelveAddTable.row.add(newItem).draw();
        }
        return newItem.id;
    }));

    dataShelve.forEach(oldItem => {
        if (!newDataMap.has(oldItem.id)) {
            shelveAddTable.row(idx => dataShelve[idx].id === oldItem.id).remove().draw();
        }
    });

    dataShelve = data;
    shelveAddTable.draw();
    closeLargeModal();
}
function removeShelve (id, e) {
    isDataChanged = true;
    dataShelve = dataShelve.filter(el => el.id != id);
    var row = $(e).closest('tr');
    shelveAddTable.row(row).remove().draw();
}

function movement () {
    showLoadingSpinner()
    if ($('#movement').valid()) {
        if (!checkQuantity()) {
            notification('error', 'Quantity must be less than or equal to the total quantity of products at that location')
            hideLoadingSpinner()
            return
        }
        var data = {};
        if (dataShelve.length > 0) {
            data.shelves = dataShelve.map((item, index) => {
                return {
                    id: item.id,
                    quantity: $(`#quantity_${item.id}`).val()
                }
            });

            $.ajax({
                url: `/api/v1/product/movement/${productLocation.id}`,
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    hideLoadingSpinner()
                    notification("success", trans("message.success"));
                    window.location.href = `/product/${productLocation.product_id}/inventory`;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    notification("error", trans("message.error"));
                    hideLoadingSpinner()
                }
            });
        }
    } else {
    }
    hideLoadingSpinner()
}

function checkQuantity () {
    let totalQuantity = 0;
    $('input[name="quantity[]"]').each(function () {
        var quantity = parseFloat($(this).val().replace(/,/g, "")) || 0;
        totalQuantity += quantity;
    });
    return totalQuantity <= productLocation?.quantity;
}

function back () {
    if (isDataChanged) {
        showBackModal(`/product/${productLocation.product_id}/inventory`)
    } else {
        window.location.href = `/product/${productLocation.product_id}/inventory`;
    }
}
