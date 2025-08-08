let documentTable;
$(function (e) {
    documentTable = $('#document-datatable').DataTable({
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
            url: "/api/v1/document",
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
                data: 'file_name',
                name: "file_name"
            },
            {
                data: function (data, row, type) {
                    return `<a href="http://127.0.0.1:8080/ipfs/${data.ipfs_hash}" target="_blank">${data.ipfs_hash}</a>`;
                },
                name: "ipfs_hash"
            },
            {
                data: "document_type",
                name: "document_type"
            },
            {
                data: "uploaded_by_name",
                name: "uploaded_by_name"
            },
            {
                data: function (data, type, row) {
                    return dateFormat(data.created_at || "");
                },
                name: "created_at"
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
     $("#document-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            documentTable.search("").draw();
        }
    });
});


function refreshDocumentTable () {
    documentTable.draw(false);
}
