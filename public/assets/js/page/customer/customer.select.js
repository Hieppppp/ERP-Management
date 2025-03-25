if (typeof customerSelectTable == "undefined") {
    let customerSelectTable;
}
if (typeof customerIds == "undefined") {
    let customerIds;
}
// customerIds = JSON.parse(JSON.stringify(dataSupplier));

customerSelectTable = $("#select-customer-datatable").DataTable({
    orderCellsTop: true,
    fixedHeader: true,
    processing: true,
    serverSide: true,
    responsive: false,
    search: {
        return: true,
    },
    order: [[1, "desc"]],
    language: datatableLanguage(),
    ajax: {
        url: "/api/v1/customer",
        type: "GET",
        error: function () {
            notification(
                "error",
                trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                )
            );
        },
    },
    columns: [
        {
            data: null,
        },
        {
            data: "code",
            name: "code",
        },
        {
            data: function (data, type, row) {
                let image_url = data.avatar_url
                    ? data.avatar_url
                    : "/assets/images/no-image.png";
                return `<img src="${image_url}" width="50" height="50" class="img-overlay-light-box">`;
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
            name: "created_at",
        },
    ],
    columnDefs: [
        { targets: "no-sort", sortable: false, orderable: false },
        {
            className: "min-w-image text-center",
            targets: 2,
        },
        {
            className: "max-width-address",
            targets: 6,
        },
    ],
    initComplete: function (settings) {
        setDatatableFilters({
            api: this.api(),
            tableId: settings.sTableId,
        });
    },
    createdRow: (row) => {
        addCheckBoxDataTable(row);
    },
    rowCallback: function (row, data) {
        if (
            customerIds.some((item) => {
                return item.id === data.id;
            })
        ) {
            $('input[name="checkBoxSelected"]', row).prop("checked", true);
        }
    },
});

$("#select-customer-datatable_filter input").on("input", function () {
    if (!$(this).val()) {
        customerSelectTable.search("").draw();
    }
});

$("#select-customer-datatable tbody").on(
    "change",
    'input[name="checkBoxSelected"]',
    function () {
        let data = customerSelectTable.row($(this).closest("tr")).data();
        if (typeof isSelectOneCustomer != "undefined" && isSelectOneCustomer) {
            if ($(this).is(":checked")) {
                $(
                    '#select-customer-datatable tbody input[name="checkBoxSelected"]'
                ).prop("checked", false);
                $(this).prop("checked", true);
                customerIds = [data];
            } else {
                customerIds = [];
            }
        } else {
            if ($(this).is(":checked")) {
                customerIds.push(data);
            } else {
                customerIds = customerIds.filter((el) => el.id !== data.id);
            }
        }
    }
);

function selectCustomer() {
    if (customerIds.length > 0) {
        refreshCustomer(customerIds);
    } else {
        notification(
            "error",
            trans("message.pleaseSelectAtLeast1item", {
                item: trans("translation.menu.customer"),
            })
        );
    }
}
