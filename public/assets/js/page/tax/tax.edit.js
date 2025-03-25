$(document).ready(function () {
    inputTrimStart();
    let taxId = $('#taxId').val();
    $("#tax_update").validate({
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
                validate: [`unique:taxes,code,${taxId}`, trans('translation.tax.code')],
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

function editTax () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#tax_update").valid()) {
            const taxIdEdit = $('#taxId').val();
            var formData = $('#tax_update').serialize();
            $.ajax({
                url: `/api/v1/tax/${taxIdEdit}`,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    notification("success", trans("message.success"));
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshTaxTable === 'function') {
                        refreshTaxTable()
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshTaxTable === 'function') {
                        refreshTaxTable()
                    }
                    if (jqXHR?.responseJSON) {
                        if (Array.isArray(jqXHR.responseJSON?.message)) {
                            return notification('error', jqXHR.responseJSON?.message[0]);
                        }
                    }
                    notification('error', trans('message.modal_not_found'));

                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}

