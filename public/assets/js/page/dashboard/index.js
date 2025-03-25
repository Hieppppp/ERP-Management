let dataStatusOrder;
let dataSaleOrderStatus;
$(function (e) {
    dataSaleOrderStatus = [
        {
            id: "1",
            text: trans("translation.saleOrder.draft"),
        },
        {
            id: "2",
            text: trans("translation.saleOrder.confirmed"),
        },
        {
            id: "3",
            text: trans("translation.saleOrder.inTransit"),
        },
        {
            id: "4",
            text: trans("translation.saleOrder.delivered"),
        },
        {
            id: "5",
            text: trans("translation.saleOrder.cancel"),
        },
    ];
    dataStatusOrder = [
        {
            id: "pending",
            text: trans("translation.order.pending"),
        },
        {
            id: "awaiting_payment",
            text: trans("translation.order.awaiting_payment"),
        },
        {
            id: "pending_shipment",
            text: trans("translation.order.pending_shipment"),
        },
    ];
    let dataShippingMethod = [
        {
            id: "1",
            text: trans("translation.saleOrder.standardShipping"),
        },
        {
            id: "2",
            text: trans("translation.saleOrder.inStorePickup"),
        },
    ];
    getCurrentStock();
    getLowStock();
    getRecentOrders();
    getTopSellingItems();
    getStatistic();
    $("#pending-order-datatable").DataTable({
        responsive: false,
        searching: false,
        bLengthChange: false,
        serverSide: true,
        info: false,
        ordering: false,
        ajax: {
            url: "/api/v1/dashboard/pending-order",
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
        paging: false,
        language: datatableLanguage(),
        columns: [
            {
                data: function (data, type, row) {
                    return `<div class="step-circle">0${data.id}</div>`;
                },
            },
            {
                data: function (data, type, row) {
                    return hasPermission("sale_order")
                        ? `<a target="_blank" href="/sale-order/${data.id}">${data.code}</a>`
                        : data.code;
                },
            },
            {
                data: function (data, type, row) {
                    const date = new Date(data.created_at);
                    return moment(date).format("YYYY-MM-DD");
                },
            },
            {
                data: function (data, type, row) {
                    return hasPermission("customer")
                        ? `<a target="_blank" href="/customer/${data.customer_id
                        }">${truncateText(data.customer_name, 30)}</a>`
                        : truncateText(data.customer_name, 30);
                },
                width: "10%",
            },
            {
                data: (data) => {
                    return `${numberFormat(data.total_quantity, 2)}$`;
                },
                className: "text-end",
                width: "5%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.deliver_address, 30);
                },
            },
            {
                data: function (data, type, row) {
                    return getTrans(data.delivery_method, dataShippingMethod);
                },
            },
            {
                data: function (data, type, row) {
                    return `<span class="rounded-5 purchase-order-status btn width-status status-${data.order_status
                        }">${getTrans(
                            data.order_status,
                            dataSaleOrderStatus
                        )}</span>`;
                },
                className: "text-center",
            },
        ],
    });

    $("#filter-day").change(function () {
        getTopSellingItems();
    });
});

function getCurrentStock () {
    $.ajax({
        url: "/api/v1/dashboard/current-stock",
        type: "GET",
        success: function (res) {
            if (res.data.length > 0) {
                let html = "";
                res.data.forEach((item, index) => {
                    html += getHtmlProduct(
                        index + 1,
                        truncateText(item.name),
                        `${numberFormat(item.quantity, 2)} (${truncateText(
                            item.symbol,
                            10,
                            false
                        )})`,
                        item.id
                    );
                });
                $("#current-stock").html(html);
            }
        },
    });
}

function getLowStock () {
    $.ajax({
        url: "/api/v1/dashboard/low-stock",
        type: "GET",
        success: function (res) {
            if (res.data.length > 0) {
                let html = "";
                res.data.forEach((item, index) => {
                    html += getHtmlProduct(
                        index + 1,
                        truncateText(item.name),
                        `${numberFormat(item.quantity, 2)} (${truncateText(
                            item.symbol,
                            10,
                            false
                        )})`,
                        item.id
                    );
                });
                $("#low-stock").html(html);
            }
        },
    });
}

function getRecentOrders () {
    $.ajax({
        url: "/api/v1/dashboard/recent-order",
        type: "GET",
        success: function (res) {
            if (res.data.length > 0) {
                let html = "";
                res.data.forEach((item, index) => {
                    console;
                    html += `<div class="row pb-4">
                            <div class="col-sm-1">
                                <div class="step-circle">0${index + 1}</div>
                            </div>
                            <div class="col-sm-7 d-flex align-items-center">
                            ${hasPermission("sale-order")
                            ? `<a href="/sale-order/${item.id}" target="_blank">${item.code}</a>`
                            : `<span>${item.code}</span>`
                        }
                            </div>
                            <div class="col-sm-4 d-flex align-items-center justify-content-end">
                                <span class="rounded-5 purchase-order-status btn width-status status-${item.order_status
                        }">${getTrans(
                            item.order_status,
                            dataSaleOrderStatus
                        )}</span>
                            </div>
                        </div>`;
                });
                $("#recent-orders").html(html);
            }
        },
    });
}

function getTopSellingItems () {
    let filterDay = $("#filter-day").val();
    if (!filterDay) {
        filterDay = "this_month";
    }
    let startTime = moment()
        .subtract(1, "months")
        .format("YYYY-MM-DD 00:00:00");
    let endTime = moment().format("YYYY-MM-DD 23:59:59");
    if (filterDay == "today") {
        startTime = moment().format("YYYY-MM-DD 00:00:00");
    }
    if (filterDay == "this_week") {
        startTime = moment().subtract(7, "days").format("YYYY-MM-DD 00:00:00");
    }
    if (filterDay == "this_month") {
        startTime = moment()
            .subtract(1, "months")
            .format("YYYY-MM-DD 00:00:00");
    }
    $.ajax({
        url: `/api/v1/dashboard/top-selling?startTime=${startTime}&endTime=${endTime}`,
        type: "GET",
        success: function (res) {
            let html = "";
            if (res.data.length > 0) {
                res.data.forEach((item, index) => {
                    html += getHtmlProduct(
                        index + 1,
                        truncateText(item.name),
                        `${numberFormat(
                            item.total_quantity,
                            2
                        )} (${truncateText(item.symbol, 10, false)})`,
                        item.id
                    );
                });
            }
            $("#top-selling").html(html);
        },
    });
}

function getHtmlProduct (number, name, stock, id) {
    return `<div class="row pb-4">
                <div class="col-sm-1">
                    <div class="step-circle">0${number}</div>
                </div>
                <div class="col-sm-7 d-flex align-items-center">
                ${hasPermission("product")
            ? `<a href="/product/${id}" target="_blank">${name}</a>`
            : `<span>${name}</span>`
        }
                </div>
                <div class="col-sm-4 d-flex align-items-center justify-content-end">
                    <span>${stock}</span>
                </div>
            </div>`;
}

function getTrans (key, mapData) {
    const val = mapData.find((e) => e.id == key);
    return val ? val.text : key;
}

function getStatistic () {
    $.ajax({
        url: "/api/v1/dashboard/statistic",
        type: "GET",
        success: function (res) {
            $("#total_inventory_value").text(
                `${numberFormat(res?.data?.total_inventory_value ?? 0, 2)}$`
            );
            $("#total_inventory_value_child").text(
                `${numberFormat(res?.data?.total_inventory_value ?? 0, 2)}$`
            );
            $("#average_inventory_value").text(
                `${numberFormat(
                    res?.data?.average_inventory_value ?? 0,
                    2
                )}$ / ${trans("translation.dashboard.units")}`
            );
            $("#total_purchase_order").text(res?.data?.total_purchase_order);
            $("#total_sales_order").text(res?.data?.total_sales_order);
        },
    });
}

function getHtmlOrder (number, name, stock) {
    return `<div class="row pb-4">
                <div class="col-sm-1">
                    <div class="step-circle">0${number}</div>
                </div>
                <div class="col-sm-7 d-flex align-items-center">
                <span>${name}</span>
                </div>
                <div class="col-sm-4 d-flex align-items-center justify-content-end">
                    <span>${stock}</span>
                </div>
            </div>`;
}
