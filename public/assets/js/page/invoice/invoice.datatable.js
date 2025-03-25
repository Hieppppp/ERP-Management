let invoiceTable;
$(function (e) {
    const paymentStatus = [
        { id: "paid", text: trans("translation.invoice.paid") },
        { id: "unpaid", text: trans("translation.invoice.unpaid") },
        {
            id: "partially_paid",
            text: trans("translation.invoice.partially_paid"),
        },
    ];

    const invoiceStatus = [
        { id: "draft", text: trans("translation.invoice.draft") },
        { id: "posted", text: trans("translation.invoice.posted") },
    ];
    invoiceTable = $("#invoice-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: false,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[0, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: "/api/v1/invoice",
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
                    return `<a href="/invoice/${data.id}">${data.invoice_code}</a>`;
                },
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return `<a href="/customer/${
                        data.customer_id
                    }">${truncateText(data.customer_name)}</a>`;
                },
                name: "customer_name",
            },
            {
                data: "created_at",
                name: "created_at",
            },
            {
                data: function (data, type, row) {
                    return getScheduledDate(data.created_at, data.payment_term);
                },
                name: "payment_term",
            },
            {
                data: function (data, type, row) {
                    return `$${numberFormat(data.total_amount, 2)}`;
                },
                name: "total_amount",
                className: "text-end",
            },
            {
                data: function (data, type, row) {
                    let payment_status = "unpaid";
                    if (
                        data.total_paid_amount > 0 &&
                        data.total_paid_amount < data.total_amount
                    ) {
                        payment_status = "partially_paid";
                    }
                    if (data.total_paid_amount >= data.total_amount) {
                        payment_status = "paid";
                    }
                    return `<span class="rounded-5 purchase-order-status btn width-status ${payment_status}">${getTrans(
                        payment_status,
                        paymentStatus
                    )}</span>`;
                },
                name: "payment_status",
                className: "text-center",
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
                select: {
                    payment_status: {
                        dataType: "fix",
                        data: paymentStatus,
                        placeholder: trans(
                            "translation.placeHolder.selectStatus"
                        ),
                    },
                },
            });
        },
    });

    $("#invoice-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            invoiceTable.search("").draw();
        }
    });
});

function getTrans(key, mapData) {
    const val = mapData.find((e) => e.id == key);
    return val ? val.text : key;
}

function getScheduledDate(createdAt, paymentTerm) {
    let html = "";
    let date = calculatePaymentTerm(createdAt, paymentTerm);
    let currentDate = new Date();
    const dayDifference = Math.ceil(
        (date.getTime() - currentDate.getTime()) / (1000 * 60 * 60 * 24)
    );
    if (dayDifference < 0) {
        html = `<span class="text-danger"> ${trans(
            "translation.invoice.daysAgo",
            {
                number: Math.abs(dayDifference),
            }
        )} </span>`;
    } else if (dayDifference === 0) {
        html = `<span class="text-warning"> ${trans(
            "translation.invoice.today"
        )} </span>`;
    } else {
        html = `<span> ${trans("translation.invoice.inDay", {
            number: Math.abs(dayDifference),
        })} </span>`;
    }
    return html;
}
