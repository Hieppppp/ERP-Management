let shelveTable;
$(function (e) {
    shelveTable = $("#shelve-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[0, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: "/api/v1/shelve",
            type: "GET",
            error: function () {
                notification('error', trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                ));
            },
        },
        columns: [
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
                    return hasPermission('warehouse') ? `<a target="__blank" href="/warehouse/${data.warehouse_id}">${truncateText(data.warehouse, 20)}</a>` : truncateText(data.warehouse, 20);
                },
                name: "warehouse",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location);
                },
                name: "location",
            },
            {
                data: 'quantity',
                name: 'quantity',
            },
            {
                data: 'updated_at',
                name: 'updated_at',
            },
            {
                data: function (data, type, row) {
                    return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle rounded-pill"
                            data-bs-toggle="dropdown" aria-expanded="false"> ${trans('translation.action')} </button>
                            <ul class="dropdown-menu" style="">
                                <li><a class="dropdown-item" href="/shelve/${data.id}" title="${trans('translation.detail')}">${trans('translation.detail')}</i></a></li>
                                <li><button class="dropdown-item" onclick="showModalEdit(${data.id})" title="${trans('translation.edit')}">${trans('translation.edit')}</button></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><button class="dropdown-item" data-bs-toggle="modal"  title="${trans('translation.delete')}" data-bs-target="#deleteShelve" onclick="confirmRemove(${data.id})">${trans('translation.delete')}</button></li>
                            </ul>
                    </div>`;
                },
                width: "10%",
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'text-center',
                targets: -1
            },
            {
                className: 'max-width-address',
                targets: -3
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            })
        },
    });

    $("#shelve-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            shelveTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deleteShelve(${id})`);
}

function deleteShelve (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/shelve/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            shelveTable.draw(false);
            hideLoadingSpinner()
            $("#deleteShelve").modal("hide");
            notification("success", trans("message.deleteSuccess"));
        },
        error: function () {
            shelveTable.draw(false);
            hideLoadingSpinner()
            $("#deleteShelve").modal("hide");
            notification('error', trans('message.modal_not_found'));
        },
    });
}

function showModalCreate () {
    showSmallModal(trans('translation.shelve.create'), `/shelve/create`);
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
                    shelveTable.draw(false);
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

function showModalEdit (id) {
    showSmallModal(trans('translation.shelve.edit'), `/shelve/${id}/edit`);
}

function editShelve () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#shelve_edit").valid()) {
            const shelveIdEdit = $('#shelveId').val();
            var formData = $('#shelve_edit').serialize();
            $.ajax({
                url: `/api/v1/shelve/${shelveIdEdit}`,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    shelveTable.draw(false);
                    notification("success", trans("message.success"));
                    closeSmallModal();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner()
                    shelveTable.draw(false);
                    closeSmallModal();
                    if (jqXHR?.responseJSON) {
                        if (Array.isArray(jqXHR.responseJSON?.message)) {
                            return notification('error', xhr.responseJSON?.message[0]);
                        }
                    }
                    notification('error', trans('message.modal_not_found'));
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}
