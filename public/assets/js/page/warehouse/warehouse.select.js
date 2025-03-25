if (typeof warehouseSelectTable == "undefined") {
    let warehouseSelectTable;
}
if (typeof warehouseIds == "undefined") {
    let warehouseIds;
}
warehouseIds = JSON.parse(JSON.stringify(dataWarehouse));
$(function (e) {
    warehouseSelectTable = $("#warehouse-datatable").DataTable({
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
            url: "/api/v1/warehouse",
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
                width: "10%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
                width: "20%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.address);
                },
                name: "address",
                width: "60%",
            },
            {
                data: "shelves_count",
                name: "shelves_count",
            },
            {
                data: "updated_at",
                name: "updated_at",
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "max-width-address",
                targets: -1,
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
            });
            selectOne(settings.sTableId, warehouseSelectTable);
        },
        createdRow: (row) => {
            addCheckBoxDataTable(row);
        },
        rowCallback: function (row, data) {
            if (warehouseIds.id === data.id) {
                $('input[name="checkBoxSelected"]', row).prop("checked", true);
            }
        },
    });

    $("#warehouse-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            warehouseSelectTable.search("").draw();
        }
    });
});

function selectWarehouse() {
    const data = getDataSelected("warehouse-datatable", warehouseSelectTable);
    if (data) {
        renderWarehouse(data);
    } else {
        notification(
            "error",
            trans("message.pleaseSelectAtLeast1item", {
                item: trans("translation.menu.warehouse"),
            })
        );
    }
}
