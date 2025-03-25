let customerTable;
$(function (e) {
    customerTable = $("#customer-datatable").DataTable({
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
            url: "/api/v1/customer",
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
                    let image_url = data.avatar_url ? data.avatar_url : "/assets/images/no-image.png";
                    return `<img src="${image_url}" width="50" height="50" class="img-overlay-light-box">`
                },
                name: "avatar",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.email);
                },
                name: "email",
            },
            {
                data: "phone",
                name: "phone",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.address);
                },
                name: "address",
            },
            {
                data: function (data, type, row) {
                    return dateFormat(data.created_at || "");
                },
                name: "created_at"
            },
            {
                data: function (data, type, row) {
                    return `<a class="btn btn-primary fs-14 text-white edit-icn" href="/customer/${data.id}" title="${trans('translation.detail')}"><i class="fe fe-eye"></i></a>
                            <a class="btn btn-primary fs-14 text-white edit-icn" href="/customer/${data.id}/edit" title="${trans('translation.edit')}"><i class="fe fe-edit"></i></a>
                            <button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deleteCustomer" title="${trans('translation.delete')}" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`;
                },
                width: "10%",
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
            {
                className: 'max-width-address',
                targets: 5
            },
            {
                className: 'text-center',
                targets: -1
            },
        ],
        drawCallback: function (settings) {
            var pageInfo = customerTable.page.info();
            if (pageInfo.page >= pageInfo.pages && pageInfo.pages > 1) {
                customerTable.page(pageInfo.pages - 1).draw(false);
            }
        },
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            })
        },
    });

    $("#customer-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            customerTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deleteCustomer(${id})`);
}

function deleteCustomer (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/customer/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            hideLoadingSpinner()
            customerTable.draw(false);
            $("#deleteCustomer").modal("hide");
            notification("success", trans("message.deleteSuccess"));
        },
        error: function () {
            hideLoadingSpinner()
            customerTable.draw(false);
            $("#deleteCustomer").modal("hide");
            notification('error', trans('message.modal_not_found'));
        },
    });
}
