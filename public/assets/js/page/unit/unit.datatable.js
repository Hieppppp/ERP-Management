let unitTable;
$(function (e) {
    unitTable = $("#unit-datatable").DataTable({
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
            url: "/api/v1/unit",
            type: "GET",
            error: function () {
                notification('error', trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                ));
            },
        },
        columns: [
            {
                data: "id",
                name: "id",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.symbol);
                },
                name: "symbol",
            },
            {
                data: 'product_quantity',
                name: 'product_quantity',
            },
            {
                data: function (data, type, row) {
                    return dateFormat(data.created_at || "");
                },
                name: "created_at"
            },
            {
                data: function (data, type, row) {
                    return `<button class="btn btn-primary fs-14 text-white edit-icn" onclick="showEditModal(${data.id})" title="Edit"><i class="fe fe-edit"></i></button>
                            <button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deleteUnit" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`;
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

    $("#unit-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            unitTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deleteUnit(${id})`);
}

function deleteUnit (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/unit/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            hideLoadingSpinner()
            unitTable.draw(false);
            $("#deleteUnit").modal("hide");
            notification("success", trans("message.deleteSuccess"));
        },
        error: function () {
            hideLoadingSpinner()
            unitTable.draw(false);
            $("#deleteUnit").modal("hide");
            notification('error', trans('message.modal_not_found'));
        },
    });
}

function showCreateModal () {
    showSmallModal(trans('translation.unit.create'), `/unit/create`);
}

function refreshUnitTable () {
    unitTable.draw(false);
}

function showEditModal (id) {
    showSmallModal(trans('translation.unit.edit'), `/unit/${id}/edit`);
}
