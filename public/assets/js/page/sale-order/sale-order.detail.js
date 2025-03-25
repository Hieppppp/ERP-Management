let saleProductDatatable;
let saleOrderProducts = saleOrderDetails;
let customerTaxes = JSON.parse(saleOrder.tax_info);

$(function (e) {
    saleProductDatatable = $("#sale_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: saleOrderProducts,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return data.code;
                },
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`;
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
            },
            {
                data: "quantity",
            },
            {
                data: function (data, type, row) {
                    return data.order_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
            },
            {
                data: function (data, type, row) {
                    return data.discount_rate;
                },
            },
            {
                data: function (data, type, row) {
                    return `<span id='total_cost_${data.id}'>${numberFormat(
                        (data.order_quantity *
                            data.unit_price *
                            (100 - data.discount_rate)) /
                            100,
                        2
                    )}$</span>`;
                },
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image text-center",
                targets: 1,
            },
            {
                className: "text-end",
                targets: [-1, -2, -3],
            },
        ],
    });
    calculateTotalAmount();
});

function calculateTotalAmount() {
    var totalAmount = 0;
    $('#sale_product_datatable span[id^="total_cost_"]').each(function () {
        let totalCost = $(this).text().replace("$", "").replace(/,/g, "");
        if (
            typeof totalCost === "string" &&
            !isNaN(totalCost) &&
            !isNaN(parseFloat(totalCost))
        ) {
            totalAmount += parseFloat(totalCost);
        }
    });
    let html = "";
    if (customerTaxes.length) {
        const totalAmountBeforeTax = totalAmount;
        customerTaxes.forEach(function (value, index) {
            let taxAmount = numberFormat(
                (totalAmountBeforeTax * value.rate) / 100,
                2
            );
            totalAmount += parseFloat(taxAmount.replace(/,/g, ""));
            html += `<p>${value.code}(${value.rate}%) : ${taxAmount}$</p>`;
        });
    }
    $("#taxList").html(html);
    $("#totalAmount").text(`${numberFormat(totalAmount, 2)}$`);
}

function confirmOrderStatus() {
    showConfirmModal(
        `updateOrderStatus(${confirmStatus})`,
        "",
        trans("message.confirmSaleOrder")
    );
}

function confirmCancelOrder() {
    showConfirmModal(
        `updateOrderStatus(${cancelStatus})`,
        "",
        trans("message.confirmCancelSaleOrder")
    );
}

function confirmTransitOrder() {
    showConfirmModal(
        `updateOrderStatus(${inTransitStatus})`,
        "",
        trans("message.confirmTransitSaleOrder")
    );
}

function confirmDoneOrder() {
    showConfirmModal(
        `updateOrderStatus(${deliverdStatus})`,
        "",
        trans("message.confirmDoneSaleOrder")
    );
}

function updateOrderStatus(status) {
    closeConfirmModal();
    showLoadingSpinner();
    if (
        status == inTransitStatus &&
        saleOrder.receipt_status != receiptDoneStatus
    ) {
        window.location.href = `/sale-order/${saleOrderId}/receipt?order_status=${inTransitStatus}`;
        return;
    }
    let data = {};
    data.order_status = status;
    data.note = $("#note").val();
    $.ajax({
        processData: false,
        contentType: false,
        url: `/api/v1/sale-order/${saleOrderId}/update-status`,
        method: "POST",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json",
        },
        success: function (response) {
            hideLoadingSpinner();
            window.location.href =
                status != confirmStatus
                    ? `/sale-order/${saleOrderId}`
                    : `/sale-order/${saleOrderId}/receipt`;
            notification("success", trans("message.success"));
        },
        error: function (jqXHR, textStatus, errorThrown) {
            hideLoadingSpinner();
            if (jqXHR?.responseJSON) {
                if (Array.isArray(jqXHR.responseJSON?.message)) {
                    return notification(
                        "error",
                        jqXHR.responseJSON?.message[0]
                    );
                }
            }
            notification("error", trans("message.error"));
        },
    });
}
