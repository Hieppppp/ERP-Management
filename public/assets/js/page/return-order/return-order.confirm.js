let productReturnTable;
$(document).ready(function () {
    initDatePicker([["scheduled_date", new Date()]]);

    productReturnTable = $("#return_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: returnOrderDetails,
        ordering: false,
        paging: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return `${data.product_locations?.product.code}`;
                },
                className: "text-start",
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product_locations?.product?.deleted_at) {
                        $(td).addClass('deleted-text-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    let imageUrl = "/assets/images/no-image.png";
                    if (data.product_locations?.product?.images[0]) {
                        imageUrl =
                            data.product_locations.product.images[0].image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product_locations?.product?.deleted_at) {
                        $(td).addClass('deleted-image-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return `<a href="/product/${data.product_locations?.product.id}" target="_blank">${truncateText(data.product_locations?.product?.name)}</a>`;
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.product_locations?.product?.deleted_at) {
                        $(td).addClass('deleted-text-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.product_locations?.product?.unit?.name);
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.product_locations?.product?.category?.name);
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return `${data.product_locations?.shelve?.code}`;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    return `${data.demand_quantity}`;
                },
                className: "text-start",
            },
            {
                data: function (data, type, row) {
                    const demandQuantity = data.demand_quantity || 0;
                    const stockQuantity = data.product_locations?.quantity || 0;
                    const maxQuantity =
                        demandQuantity > stockQuantity
                            ? stockQuantity
                            : demandQuantity;
                    return `<input type="number" class="form-control" name="quantity[]" max="${maxQuantity}" value="${demandQuantity}" required id='quantity_${data.id}'>`;
                },
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "min-w-image text-center",
                targets: 1,
            },
        ],
    });

    $("#return_order").validate({
        rules: {
            scheduled_date: {
                notPastDate: true,
            },
            "quantity[]": {
                required: true,
                min: 0,
                number: true,
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass("fc-datepicker")) {
                error.insertAfter(element.closest(".input-group"));
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: function (event, validator) {
            if (validator.numberOfInvalids()) {
                var invalidElement = $(validator.errorList[0].element);
                if (invalidElement.closest("table").length) {
                    var row = invalidElement.closest("tr");
                    var scrollBody = $("#return_product_datatable")
                        .parent()
                        .first();
                    var scrollTop = scrollBody.scrollTop();
                    var rowTop = $(row).position().top;
                    scrollBody.scrollTop(scrollTop + rowTop - 50);
                }
            }
        },
    });
});

function confirmSendReturnOrder() {
    showConfirmModal(
        "updateReturnPurchaseOrder()",
        "",
        trans("message.confirmSendPurchaseOrder")
    );
}

function updateReturnPurchaseOrder() {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#return_order").valid()) {
            var data = {
                scheduled_date: $("#scheduled_date").val(),
                status: "done",
                note: $("#note").val(),
            };

            data.products = returnOrderDetails.map((item, index) => {
                return {
                    return_order_detail_id: item.id,
                    quantity: $(`#quantity_${item.id}`).val(),
                };
            });

            $.ajax({
                processData: false,
                contentType: false,
                url: `/api/v1/return-order/${returnOrderId}`,
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    "Content-Type": "application/json",
                },
                data: JSON.stringify(data),
                success: function (response) {
                    hideLoadingSpinner();
                    window.location.href = `/purchase-order/${response.data.purchase_order_id}`;
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner();
                    notification("error", trans("message.error"));
                },
            });
        } else {
            hideLoadingSpinner();
        }
    }, 100);
    closeConfirmModal();
}

function cancelConfirm() {
    showConfirmModal(
        "cancelReturnOrder()",
        "",
        trans("message.confirmCancelReturnOrder")
    );
}

function cancelReturnOrder() {
    showLoadingSpinner();

    var data = {
        status: "cancel",
        note: $("#note").val(),
    };

    $.ajax({
        processData: false,
        contentType: false,
        url: `/api/v1/return-order/${returnOrderId}`,
        method: "PUT",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json",
        },
        data: JSON.stringify(data),
        success: function (response) {
            hideLoadingSpinner();
            window.location.href = `/purchase-order/${response.data.purchase_order_id}`;
            notification("success", trans("message.success"));
        },
        error: function (jqXHR, textStatus, errorThrown) {
            hideLoadingSpinner();
            notification("error", trans("message.error"));
        },
    });
}
