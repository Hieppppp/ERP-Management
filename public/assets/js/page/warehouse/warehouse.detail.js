$(function (e) {
    let phoneNumberUpdate = document.querySelector("#phone_number_update");
    telInputEdit = window.intlTelInput(phoneNumberUpdate, {
        initialCountry: "ca",
        separateDialCode: true,
        showFlags: false,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js"
    });
    if (warehouse?.contact) {
        telInputEdit.setNumber(warehouse.contact);
    }
    productTable = $("#shelve-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: warehouse?.shelves,
        search: {
            return: true,
        },
        language: datatableLanguage(),
        paging: true,
        info: true,
        ordering: true,
        searching: true,
        columns: [
            {
                data: "code",
            },
            {
                data: function (data, type, row) {
                    return hasPermission('shelve') ? `<a target="__blank" href="/shelve/${data.id}">${truncateText(data.name, 20)}</a>` : truncateText(data.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location);
                },
            },
            {
                data: 'quantity'
            },
            {
                data: 'updated_at'
            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'max-width-address',
                targets: 2
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
            });
        },
    });

    $("#shelve-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            productTable.search("").draw();
        }
    });
});
