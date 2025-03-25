$(document).ready(function () {
    inputTrimStart()
    initAjaxSelect2('country_id', `/api/v1/country/search?value=id`);
    $("#province_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                uniqueProvince: 'country_id'
            },
            country_id: {
                required: true,
            }
        }
    });
    $(`#country_id`).on('change', function () {
        if ($(`#country_id`).valid()) {
            $('#country_id-error').hide();
        }
    })
});

function provinceCreate () {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#province_create").valid()) {
            var formData = $('#province_create').serialize();
            $.ajax({
                url: '/api/v1/province',
                method: 'POST',
                data: formData,
                success: function (response) {
                    notification("success", trans("message.success"));
                    closeSmallModal();
                    hideLoadingSpinner();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    notification("error", trans("message.error"));
                    hideLoadingSpinner();
                }
            });
        } else {
            hideLoadingSpinner();
        }
    }, 100);
}

