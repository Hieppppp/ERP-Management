let positionTable;
$(function (e) {
    positionTable = $("#position-datatable").DataTable({
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
            url: "/api/v1/position",
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
                data: 'description',
                name: "description"
            },
            {
                data: "id",
                name: "id"
            },
            {
                data: function (data, type, row) {
                    return `<button class="btn btn-primary fs-14 text-white edit-icn" onclick="showEditModal(${data.id})" title="Edit"><i class="fe fe-edit"></i></button>
                            <button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deletePosition" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`;
                },
                width: "10%",
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            })
        },
    });

    $("#position-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            positionTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deletePosition(${id})`);
}

function deletePosition (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/position/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            positionTable.draw(false);
            $("#deletePosition").modal("hide");
            hideLoadingSpinner()
            notification("success", trans("message.deleteSuccess"));
        },
        error: function () {
            notification('error', trans('message.deleteFailed'));
            hideLoadingSpinner()
        },
    });
}

function showCreateModal () {
    showSmallModal(trans('translation.position.create'), `/position/create`);
}

function refreshPositionTable () {
    positionTable.draw(false);
}

function showEditModal (id) {
    showSmallModal(trans('translation.position.edit'), `/position/${id}/edit?callbackFunction=positionEdit`);
}
