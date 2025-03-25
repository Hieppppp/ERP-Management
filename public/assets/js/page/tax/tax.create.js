$(document).ready(function () {
    inputTrimStart();
    $("#tax_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 200
            },
            rate: {
                required: true,
                number: true,
                max: 100,
                min: 0,
                step: 0.0001
            },
            code: {
                required: true,
                maxlength: 50,
                validate: [`unique:taxes,code`, trans('translation.tax.code')],
            },
            description: {
                maxlength: 1000
            }
        },
        messages: {
            rate: {
                step: trans('validation.please_enter_up_to_digits_after_the_decimal_point', { number: 4 })
            }
        }
    });
});

function createTax () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#tax_create").valid()) {
            var formData = $('#tax_create').serialize();
            $.ajax({
                url: '/api/v1/tax',
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    notification("success", trans("message.success"));
                    closeSmallModal();
                    if (typeof refreshTaxTable === 'function') {
                        refreshTaxTable()
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner()
                    notification("error", trans("message.error"));
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}
