let saleProductDataTable;
let saleOrderProducts = saleOrderDetails;

$(function (e) {
    saleProductDataTable = $("#sale_product_datatable").DataTable({
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
                    return `<a href="/product/${
                        data.id
                    }" target="_blank">${truncateText(data.name, 20)}</a>`;
                },
                createdCell: function (td, cellData, data, row, col) {
                    if (data.deleted_at) {
                        $(td).addClass("deleted-text-item");
                    }
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name);
                },
            },
            {
                data: function (data, type, row) {
                    return data.order_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    let html = `<div style="position:relative"> <a href="/sale-order/${saleOrderId}/product/${data.id}/select-stock">
                                <i class="fa fa-align-justify"></i>
                            </a>`;
                    if (!data.stock_validated) {
                        html += `<i class="mdi mdi-alert-circle-outline text-danger warning-icon tooltip-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="${trans(
                            "message.productNotShelved"
                        )}"></i>`;
                    }
                    return html;
                },
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image",
                targets: 1,
            },
            {
                className: "text-center",
                targets: [1, -1],
            },
        ],
    });
});

function confirmValidateReceiptStatus() {
    showConfirmModal(
        `validateReceiptStatus()`,
        "",
        trans("message.confirmValidate")
    );
}

const urlParams = new URLSearchParams(window.location.search);
const orderStatus = urlParams.get("order_status");

function validateReceiptStatus() {
    closeConfirmModal();
    let allStockValidated = true;
    saleOrderProducts.forEach(function (item) {
        if (!item.stock_validated) {
            allStockValidated = false;
        }
    });
    if (!allStockValidated) {
        notification("error", trans("message.allProductNeedValidate"));
        return;
    }
    data = {};
    data.note = $("#note").val();
    if (orderStatus == inTransitStatus) {
        data.order_status = inTransitStatus;
    }
    showLoadingSpinner();
    $.ajax({
        processData: false,
        contentType: false,
        url: `/api/v1/sale-order/${saleOrderId}/validate`,
        method: "POST",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json",
        },
        success: function (response) {
            hideLoadingSpinner();
            window.location.href = `/sale-order/${saleOrderId}`;
            notification("success", trans("message.success"));
        },
        error: function (jqXHR, textStatus, errorThrown) {
            hideLoadingSpinner();
            if (jqXHR?.responseJSON) {
                if (Array.isArray(jqXHR.responseJSON?.message)) {
                    return notification("error", jqXHR.responseJSON.message[0]);
                }
            }
        },
    });
}
