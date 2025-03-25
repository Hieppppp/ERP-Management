let supplierTable;
$(function (e) {
    supplierTable = $("#supplier-datatable").DataTable({
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
            url: "/api/v1/supplier",
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
                    let image_url = data.logo_url ? data.logo_url : "/assets/images/no-image.png";
                    return `<img src="${image_url}" width="50" height="50" class="img-overlay-light-box">`
                },
                name: "logo",
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
                data: 'quantity',
                name: "quantity",
            },
            {
                data: function (data, type, row) {
                    return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle rounded-pill"
                            data-bs-toggle="dropdown" aria-expanded="false"> ${trans('translation.action')} </button>
                            <ul class="dropdown-menu" style="">
                                <li><a class="dropdown-item" href="/supplier/${data.id}" title="${trans('translation.detail')}">${trans('translation.detail')}</a></li>
                                <li><a class="dropdown-item" href="/supplier/${data.id}/edit" title="${trans('translation.edit')}">${trans('translation.edit')}</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteSupplier" title="${trans('translation.delete')}" onclick="confirmRemove(${data.id})">${trans('translation.delete')}</button>
                                </li>
                            </ul>
                        </div>`;
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
            var pageInfo = supplierTable.page.info();
            if (pageInfo.page >= pageInfo.pages && pageInfo.pages > 1) {
                supplierTable.page(pageInfo.pages - 1).draw(false);
            }
        },
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            })
        },
    });

    $("#supplier-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            supplierTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deleteSupplier(${id})`);
}

function deleteSupplier (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/supplier/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            hideLoadingSpinner()
            supplierTable.draw(false);
            $("#deleteSupplier").modal("hide");
            notification("success", trans("message.deleteSuccess"));
        },
        error: function () {
            hideLoadingSpinner()
            supplierTable.draw(false);
            $("#deleteSupplier").modal("hide");
            notification('error', trans('message.modal_not_found'));
        },
    });
}
