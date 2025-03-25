$(document).ready(function () {
    inputTrimStart();
    initAjaxSelect2('country_id', `/api/v1/country/search?value=id`);
    initAjaxSelect2('province_id', `/api/v1/province/search?value=id`);
    $("#city_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                uniqueCity: 'province_id'
            },
            country_id: {
                required: true
            },
            province_id: {
                required: true
            }
        }
    });
    $('#country_id').on('change', function (data) {
        const selectedValue = $(this).val();
        if (selectedValue != '') {
            if ($('#province_id').val() != null) {
                $('#province_id').val('initialValue');
            }
            $('#province_id').prop('disabled', false);
        } else {
            $('#province_id').prop('disabled', true);
        }
    });
    $(`#country_id`).on('select2:select', function (e) {
        initAjaxSelect2('province_id', `/api/v1/province/search?value=id&countryId=${e.params?.data?.country_id}`);
    });
});

function cityCreate () {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#city_create").valid()) {
            var formData = $('#city_create').serialize();
            $.ajax({
                url: '/api/v1/city',
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
