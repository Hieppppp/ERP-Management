function registerPayment () {
    showSmallModal(trans('translation.invoice.registerPayment'), `/sale-order/register-payment/${saleOrder.id}`);
}

function registerPaymentSubmit () {
    showLoadingSpinner()
    if ($("#registerPayment").valid()) {
        var form = $('#registerPayment')[0];
        var formData = new FormData(form);
        $.ajax({
            processData: false,
            contentType: false,
            url: `/api/v1/sale-order/register-payment/${saleOrder.id}`,
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                hideLoadingSpinner();
                $('#confirmModal').modal('hide');
                modalStack = [];
                window.location.reload();
                notification("success", trans("message.success"));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                hideLoadingSpinner();
                $('#confirmModal').modal('hide');
                notification("error", trans("message.error"));
            }
        });
    } else {
        hideLoadingSpinner();
    }
}
function openPdf () {
    let loading = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border " style="color: var(--primary-bg-color) !important;" role="status">
                <span class="visually-hidden" >Loading...</span>
            </div>
        </div>`;
    $(`#invoiceModal`).modal('show');
    $(`#invoice_content`).html(loading);
    $.ajax({
        type: "GET",
        url: `/api/v1/invoice/${saleOrder.id}/pdf`,
        success: function (res) {
            const iframe = document.getElementById('invoice_content');
            iframe.contentWindow.document.open();
            iframe.contentWindow.document.write(res);
            iframe.contentWindow.document.close();

        },
        error: function (xhr, err) {
            closeSmallModal()
            if (xhr?.responseJSON) {
                if (Array.isArray(xhr.responseJSON?.message)) {
                    return notification('error', xhr.responseJSON?.message[0]);
                }
            }
            notification('error', trans(
                "message.error"
            ));
        },
    });
}

function printPDF () {
    showLoadingSpinner()
    $.ajax({
        type: "GET",
        url: `/api/v1/invoice/${saleOrder.id}/pdf?type=print`,
        xhrFields: {
            responseType: 'blob' // Đảm bảo rằng phản hồi được xử lý như một blob (tệp nhị phân)
        },
        success: function (res) {
            const url = window.URL.createObjectURL(new Blob([res]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'invoice.pdf');
            document.body.appendChild(link);
            link.click();
            link.parentNode.removeChild(link);
            hideLoadingSpinner();
        },
        error: function (xhr, err) {
            hideLoadingSpinner();
            closeSmallModal()
            if (xhr?.responseJSON) {
                if (Array.isArray(xhr.responseJSON?.message)) {
                    return notification('error', xhr.responseJSON?.message[0]);
                }
            }
            notification('error', trans(
                "message.error"
            ));
        },
    });
}

function sendInvoice () {
    showLoadingSpinner()
    $.ajax({
        type: "GET",
        url: `/api/v1/invoice/${saleOrder.id}/pdf?type=send`,
        success: function (res) {
            hideLoadingSpinner();
            notification("success", trans("message.success"));
            $(`#invoiceModal`).modal('hide');
        },
        error: function (xhr, err) {
            hideLoadingSpinner();
            closeSmallModal()
            if (xhr?.responseJSON) {
                if (Array.isArray(xhr.responseJSON?.message)) {
                    return notification('error', xhr.responseJSON?.message[0]);
                }
            }
            notification('error', trans(
                "message.error"
            ));
        },
    });
}
