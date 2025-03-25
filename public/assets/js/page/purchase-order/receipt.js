$(function (e) {
    const status = [
        { id: "cancel", text: trans("translation.purchaseOrder.cancel") },
        { id: "pending", text: trans("translation.purchaseOrder.ready") },
        { id: "pending_shelve", text: trans("translation.purchaseOrder.done") },
        { id: "done", text: trans("translation.purchaseOrder.done") },
    ];

    const statusOut = [
        { id: "cancel", text: trans("translation.purchaseOrder.cancel") },
        { id: "ready", text: trans("translation.purchaseOrder.ready") },
        { id: "done", text: trans("translation.purchaseOrder.done") },
    ];
    let receiptIn = $("#receipt-in-datatable").DataTable({
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
            url: "/api/v1/receipt-in",
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
                data: function (data, type, row) {
                    return `<a href="/purchase-order/${data.id}/receive?page=receipt">${data.receipt_code}</a>`;
                },
                name: "receipt_code",
                width: "10%",
            },
            {
                data: "created_at",
                name: "created_at",
                width: "10%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.supplier_name, 20);
                },
                name: "supplier_name",
                width: "15%",
            },
            {
                data: "quantity_demand",
                name: "quantity_demand",
                width: "10%",
            },
            {
                data: "quantity_received",
                name: "quantity_received",
                width: "10%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.shipping_address);
                },
                name: "shipping_address",
                className: "max-width-address text-start",
                width: "25%",
            },
            {
                data: function (data, type, row) {
                    return `<span class="rounded-5 purchase-order-status btn width-status ${
                        data.status
                    }">${getTrans(data.status, status)}</span> `;
                },
                name: "status",
                className: "text-center",
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
                select: {
                    "status-in": {
                        dataType: "fix",
                        data: [
                            {
                                id: "cancel",
                                text: trans("translation.purchaseOrder.cancel"),
                            },
                            {
                                id: "pending",
                                text: trans("translation.purchaseOrder.ready"),
                            },
                            {
                                id: "done",
                                text: trans("translation.purchaseOrder.done"),
                            },
                        ],
                        placeholder: trans(
                            "translation.placeHolder.selectStatus"
                        ),
                    },
                },
            });
        },
    });

    $("#receipt-in-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            receiptIn.search("").draw();
        }
    });

    let receiptOut = $("#receipt-out-datatable").DataTable({
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
            url: "/api/v1/receipt-out",
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
                data: function (data, type, row) {
                    return `<a href="/return-order/${data.id}/detail?page=receipt">${data.code}</a>`;
                },
                name: "code",
                width: "10%",
            },
            {
                data: "created_at",
                name: "created_at",
                width: "10%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.supplier_name);
                },
                name: "supplier_name",
                width: "20%",
            },
            {
                data: "quantity_demand",
                name: "quantity_demand",
                width: "10%",
            },
            {
                data: "quantity_returned",
                name: "quantity_returned",
                width: "10%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.shipping_address);
                },
                name: "shipping_address",
                width: "30%",
            },
            {
                data: function (data, type, row) {
                    return `<span class="rounded-5 purchase-order-status btn width-status ${
                        data.status
                    }">${getTrans(data.status, statusOut)}</span> `;
                },
                name: "status",
                className: "text-center",
                width: "10%",
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
                select: {
                    "status-out": {
                        dataType: "fix",
                        data: statusOut,
                        placeholder: trans(
                            "translation.placeHolder.selectStatus"
                        ),
                    },
                },
            });
        },
    });

    $("#receipt-out-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            receiptOut.search("").draw();
        }
    });
});

function getTrans(key, mapData) {
    const val = mapData.find((e) => e.id == key);
    return val ? val.text : key;
}
