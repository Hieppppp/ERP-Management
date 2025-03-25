if (typeof shelveSelectTable == 'undefined') {
    let shelveSelectTable;
}
if (typeof shelveIds == 'undefined') {
    let shelveIds;
}
shelveIds = JSON.parse(JSON.stringify(dataShelve));
if (typeof shelveIdsParams == 'undefined') {
    let shelveIdsParams;
}
shelveIdsParams = []
if (typeof dataWarehouse != 'undefined' && dataWarehouse?.id) {
    shelveIdsParams.push(`warehouseId=${dataWarehouse?.id}`)
}

if (typeof excludeShelve != 'undefined' && excludeShelve) {
    shelveIdsParams.push(`excludeShelve=${excludeShelve.join(',')}`)
}

if (typeof filterByWarehouse != 'undefined' && filterByWarehouse) {
    $('#warehouseSelect').show();
    initAjaxSelect2('warehouse_id', '/api/v1/warehouse/search');
}

$(function (e) {
    shelveSelectTable = $("#shelve-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[1, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: `/api/v1/shelve?${shelveIdsParams.join('&')}`,
            type: "GET",
            error: function () {
                notification('error', trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                ));
            },
        },
        columns: [
            {
                data: null
            },
            {
                data: "code",
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
                    return truncateText(data.warehouse, 20);
                },
                name: "warehouse",
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
                    return data.updated_at;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            });
        },
        createdRow: (row) => {
            addCheckBoxDataTable(row);
        },
        rowCallback: function (row, data) {
            if (Array.isArray(shelveIds)) {
                if (shelveIds.some((item) => {
                    return item.id === data.id
                })) {
                    $('input[name="checkBoxSelected"]', row).prop('checked', true);
                }
            } else {
                if (shelveIds?.id === data.id) {
                    $('input[name="checkBoxSelected"]', row).prop('checked', true);
                }
            }

        }
    });

    $("#shelve-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            shelveSelectTable.search("").draw();
        }
    });

    $('#shelve-datatable tbody').on('change', 'input[name="checkBoxSelected"]', function () {
        let data = shelveSelectTable.row($(this).closest('tr')).data();
        if (typeof isSelectOneShelve != 'undefined' && isSelectOneShelve) {
            if ($(this).is(':checked')) {
                shelveIds = [data];
            } else {
                shelveIds = [];
            }
        } else {
            if ($(this).is(':checked')) {
                shelveIds.push(data);
            } else {
                shelveIds = shelveIds.filter(el => el.id !== data.id);
            }
        }
    });

    $('#warehouse_id').on('change', function () {
        let warehouseId = $(this).val();
        if (warehouseId) {
            shelveIdsParams = [`warehouseId=${warehouseId}`];
        } else {
            shelveIdsParams = [];
        }
        shelveSelectTable.ajax.url(`/api/v1/shelve?${shelveIdsParams.join('&')}`).load();
    })
});

function selectShelve () {
    if (shelveIds.length > 0) {
        renderShelve(shelveIds)
    } else {
        notification('error', trans('message.pleaseSelectAtLeast1item', { item: trans('translation.shelve.shelve') }));
    }
}

function showModalCreate () {
    let urlCreateShelve = `/shelve/create`;
    if (typeof dataWarehouse != 'undefined' && dataWarehouse?.id) {
        urlCreateShelve += `?warehouseId=${dataWarehouse?.id}`;
    }
    showSmallModal(trans('translation.shelve.create'), urlCreateShelve);
}

function createShelve () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($('#shelve_create').valid()) {
            var formData = $('#shelve_create').serialize();
            $.ajax({
                url: '/api/v1/shelve',
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    shelveSelectTable.draw(false);
                    notification("success", trans("message.success"));
                    closeSmallModal();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    notification("error", trans("message.error"));
                    hideLoadingSpinner()
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}
