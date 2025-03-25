if (typeof inventorySelectTable == "undefined") {
    let inventorySelectTable;
}
if (typeof inventoryIds == "undefined") {
    let inventoryIds;
}
inventoryIds = JSON.parse(JSON.stringify(dataInventory));
$(function (e) {
    inventorySelectTable = $("#inventory-select-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: false,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[2, "asc"]],
        language: datatableLanguage(),
        ajax: {
            url: `/api/v1/product/${includeProductId}/inventory`,
            type: "GET",
            data: function (d) {
                if (includeWarehouseId) {
                    d.warehouseId = includeWarehouseId;
                }
            },
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
                data: "batch_code",
                name: "batch_code",
            },
            {
                data: "received_date",
                name: "received_date",
            },
            {
                data: "shelves_code",
                name: "shelves_code",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.warehouse_name, 20);
                },
                name: "warehouse_name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location);
                },
                name: "location",
            },
            {
                data: "quantity",
                name: "quantity",
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
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
                inventoryIds.some((item) => {
                    return item.id === data.id;
                })
            ) {
                $('input[name="checkBoxSelected"]', row).prop("checked", true);
                if (
                    typeof disableSelectedInventory != "undefined" &&
                    disableSelectedInventory
                ) {
                    $('input[name="checkBoxSelected"]', row).prop(
                        "disabled",
                        true
                    );
                }
            }
        },
    });

    $("#inventory-select-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            inventorySelectTable.search("").draw();
        }
    });

    $("#inventory-select-datatable tbody").on(
        "change",
        'input[name="checkBoxSelected"]',
        function () {
            let data = inventorySelectTable.row($(this).closest("tr")).data();
            if (
                typeof isSelectOneInventory != "undefined" &&
                isSelectOneInventory
            ) {
                if ($(this).is(":checked")) {
                    $(
                        '#inventory-select-datatable tbody input[name="checkBoxSelected"]'
                    ).prop("checked", false);
                    $(this).prop("checked", true);
                    inventoryIds = [data];
                } else {
                    inventoryIds = [];
                }
            } else {
                if ($(this).is(":checked")) {
                    inventoryIds.push(data);
                } else {
                    inventoryIds = inventoryIds.filter(
                        (el) => el.id !== data.id
                    );
                }
            }
        }
    );
});

function selectInventory() {
    if (inventoryIds.length > 0) {
        refreshInventory(inventoryIds);
    } else {
        notification(
            "error",
            trans("message.pleaseSelectAtLeast1item", {
                item: trans("translation.menu.inventory"),
            })
        );
    }
}
