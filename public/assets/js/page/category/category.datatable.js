let categoryTable;
$(function (e) {
    categoryTable = $("#category-datatable").DataTable({
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
            url: "/api/v1/category",
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
                data: 'product_quantity',
                name: "product_quantity"
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
                            <button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deleteCategory" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`;
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

    $("#category-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            categoryTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deleteCategory(${id})`);
}

function deleteCategory (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/category/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            categoryTable.draw(false);
            $("#deleteCategory").modal("hide");
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
    showSmallModal(trans('translation.category.create'), `/category/create`);
}

function refreshCategoryTable () {
    categoryTable.draw(false);
}

function showEditModal (id) {
    showSmallModal(trans('translation.category.edit'), `/category/${id}/edit?callbackFunction=categoryEdit`);
}
