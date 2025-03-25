let taxTable;
$(function (e) {
    taxTable = $("#tax-datatable").DataTable({
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
            url: "/api/v1/tax",
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
                data: "name",
                name: "name",
            },
            {
                data: "code",
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return `${data.rate}%`
                },
                name: "rate",
            },
            {
                data: function (data, type, row) {
                    return dateFormat(data.created_at || "");
                },
                name: "created_at"
            },
            {
                data: function (data, type, row) {
                    return `<button class="btn btn-primary fs-14 text-white edit-icn" onclick="showModalEdit(${data.id})" href="/tax/${data.id}/edit" title="Edit"><i class="fe fe-edit"></i></button>
                            <button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deleteTax" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`;
                },
                width: "10%"
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

    $("#tax-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            taxTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deleteTax(${id})`);
}

function deleteTax (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/tax/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            $("#deleteTax").modal("hide");
            hideLoadingSpinner()
            taxTable.draw(false);
            notification("success", trans("message.deleteSuccess"));
        },
        error: function () {
            $("#deleteTax").modal("hide");
            hideLoadingSpinner()
            taxTable.draw(false);
            notification('error', trans('message.modal_not_found'));
        },
    })
}

function showModalCreate () {
    showSmallModal(trans('translation.tax.taxCreate'), `/tax/create`);
}

function refreshTaxTable () {
    taxTable.draw(false);
}

function showModalEdit (id) {
    showSmallModal(trans('translation.tax.edit'), `/tax/${id}/edit`);
}
