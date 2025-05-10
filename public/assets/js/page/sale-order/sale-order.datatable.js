let saleOrderTable;
$(function (e) {
    const saleOrderStatus = [
        { id: "1", text: trans("translation.saleOrder.draft") },
        { id: "2", text: trans("translation.saleOrder.confirm") },
        { id: "3", text: trans("translation.saleOrder.inTransit") },
        { id: "4", text: trans("translation.saleOrder.delivered") },
        { id: "5", text: trans("translation.saleOrder.cancel") },
    ];
    saleOrderTable = $("#sale-order-datatable").DataTable({
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
            url: "/api/v1/sale-order",
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
                    return `<a href="/sale-order/${data.id}">${data.code}</a>`;
                },
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return `<a target="_blank" href="/customer/${
                        data.customer_id
                    }">${truncateText(data.customer_name, 30)}</a>`;
                },
                name: "customer_name",
            },
            {
                data: function (data, type, row) {
                    const date = new Date(data.created_at);
                    return moment(date).format("YYYY-MM-DD");
                },
                name: "created_at",
            },
            {
                data: function (data, type, row) {
                    return getScheduledDate(data.created_at, data.payment_term);
                },
                name: "payment_term",
            },
            {
                data: "total_quantity",
                name: "total_quantity",
            },
            {
                data: function (data, type, row) {
                    return numberFormat(data.total_amount, 2);
                },
                name: "total_amount",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.deliver_address, 50);
                },
                name: "deliver_address",
            },
            {
                data: function (data, type, row) {
                    return `<span class="rounded-5 purchase-order-status btn width-status status-${
                        data.order_status
                    }">${getTrans(data.order_status, saleOrderStatus)}</span> `;
                },
                name: "order_status",
            },
            {
                data: function (data, type, row) {
                    let html = `<a class= "btn btn-primary fs-14 text-white" href = "/sale-order/${
                        data.id
                    }" title = "${trans(
                        "translation.detail"
                    )}"><i class="fe fe-eye"></i></a>`;
                    if (data.order_status == "1") {
                        html = `${html} <a class= "btn btn-primary fs-14 text-white edit-icn" href = "/sale-order/${data.id}/edit" title = "Edit" > <i class="fe fe-edit"></i></a>
                    <button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deleteSaleOrder" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`;
                    }
                    return html;
                },
                width: "10%",
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "text-center",
                targets: -1,
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
                select: {
                    saleOrderStatus: {
                        dataType: "fix",
                        data: saleOrderStatus,
                        placeholder: trans(
                            "translation.saleOrder.selectStatus"
                        ),
                    },
                },
            });
        },
    });

    $("#sale-order-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            saleOrderTable.search("").draw();
        }
    });
});

function confirmRemove(id) {
    $("#deleteBtn").attr("onclick", `deleteSaleOrder(${id})`);
}

function deleteSaleOrder(id) {
    showLoadingSpinner();
    $.ajax({
        type: "DELETE",
        url: `/api/v1/sale-order/${id}`,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
            saleOrderTable.draw(false);
            $("#deleteSaleOrder").modal("hide");
            hideLoadingSpinner();
            notification("success", trans("message.deleteSuccess"));
        },
        error: function (jqXHR) {
            saleOrderTable.draw(false);
            $("#deleteSaleOrder").modal("hide");
            hideLoadingSpinner();
            if (jqXHR.responseJSON?.message) {
                return notification("error", jqXHR.responseJSON?.message);
            }
            notification("error", trans("message.modal_not_found"));
        },
    });
}

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

