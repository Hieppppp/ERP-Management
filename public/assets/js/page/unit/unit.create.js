$(document).ready(function () {
    inputTrimStart();
    $("#unit_create").validate({
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            symbol: {
                validate: [`unique:units,symbol,NULL,id,deleted_at,NULL`, trans('translation.unit.symbol')],
                required: true,
                maxlength: 100
            },
            description: {
                maxlength: 1000
            }
        }
    });
});

function createUnit () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#unit_create").valid()) {
            var formData = $('#unit_create').serialize();
            $.ajax({
                url: '/api/v1/unit',
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    closeSmallModal();
                    if (typeof refreshUnitTable === 'function') {
                        refreshUnitTable()
                    }
                    notification("success", trans("message.success"));

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner()
                    notification("success", trans("message.error"));
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}
