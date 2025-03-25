$(document).ready(function () {
    $(`#date`).datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        startDate: invoiceCreationDate,
        endDate: currentDate
    });
    $("#registerPayment").validate({
        onfocusout: false,
        rules: {
            payment_method: {
                required: true,
            },
            date: {
                required: true,
                date: true,
                min: invoiceCreationDate,
                max: currentDate
            }
        },
    });
});
function confirm () {
    if ($("#registerPayment").valid()) {
        $('#confirmModal').modal('show');
    }
}
