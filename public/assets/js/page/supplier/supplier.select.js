if (typeof supplierSelectTable == 'undefined') {
    let supplierSelectTable;
}
if (typeof supplierIds == 'undefined') {
    let supplierIds;
}
supplierIds = JSON.parse(JSON.stringify(dataSupplier));
$(function (e) {
    supplierSelectTable = $("#supplier-select-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: false,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[1, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: `/api/v1/supplier`,
            data: {
                includeProductIds: includeProductId || ''
            },
            type: "GET",
            error: function () {
                notification('error', trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                ));
            },
        },
        columns: [
            {
                data: null
            },
            {
                data: "code",
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return `<img src="${data.logo_url ? data.logo_url : '/assets/images/no-image.png'}" width="50" height="50" class="img-overlay-light-box">`
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
                    return truncateText(data.email)
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
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 2
            },
            {
                className: 'max-width-address w-25',
                targets: 6
            },
        ],
        drawCallback: function (settings) {
            var pageInfo = supplierSelectTable.page.info();
            if (pageInfo.page >= pageInfo.pages && pageInfo.pages > 1) {
                supplierSelectTable.page(pageInfo.pages - 1).draw(false);
            }
        },
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            })
        },
        createdRow: (row) => {
            addCheckBoxDataTable(row);
        },
        rowCallback: function (row, data) {
            if (supplierIds.some((item) => {
                return item.id === data.id
            })) {
                $('input[name="checkBoxSelected"]', row).prop('checked', true);
            }
        }
    });

    $("#supplier-select-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            supplierSelectTable.search("").draw();
        }
    });

    $('#supplier-select-datatable tbody').on('change', 'input[name="checkBoxSelected"]', function () {
        let data = supplierSelectTable.row($(this).closest('tr')).data();
        if (typeof isSelectOneSupplier != 'undefined' && isSelectOneSupplier) {
            if ($(this).is(':checked')) {
                $('input[name="checkBoxSelected"]').prop('checked', false);
                $(this).prop('checked', true);
                supplierIds = [data];
            } else {
                supplierIds = [];
            }
        } else {
            if ($(this).is(':checked')) {
                supplierIds.push(data);
            } else {
                supplierIds = supplierIds.filter(el => el.id !== data.id);
            }
        }
    });
});

function selectSupplier () {
    if (supplierIds.length > 0) {
        refreshSupplier(supplierIds)
    } else {
        notification('error', trans('message.pleaseSelectAtLeast1item', { item: trans('translation.menu.supplier') }));
    }
}
