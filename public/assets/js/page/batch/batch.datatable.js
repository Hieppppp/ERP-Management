let batchTable;
$(function (e) {
    batchTable = $("#batch-datatable").DataTable({
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
            url: "/api/v1/batch",
            type: "GET",
            error: function () {
                notification('error', trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                ));
            },
        },
        columns: [
            {
                data: function (data, type, row) {
                    return `<a href="/batch/${data.id}">${data.batchCode}</a>`;
                },
                name: "code",
            },
            {
                data: "received_date",
                name: "received_date"
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.supplier_name, 20);
                },
                name: "supplier_name",
            },
            {
                data: 'number_of_products',
                name: "number_of_products",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.storage_location);
                },
                name: "storage_location"
            },
            {
                data: function (data, type, row) {
                    let html = `<a class= "btn btn-primary fs-14 text-white" href = "/batch/${data.id}" title = "${trans('translation.detail')}"><i class="fe fe-eye"></i></a>`;
                    return html;
                },
                className: "text-center",
                width: "5%",
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
        }
    });

    $("#batch-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            batchTable.search("").draw();
        }
    });
});
