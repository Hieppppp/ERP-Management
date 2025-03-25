if (typeof telInput === 'undefined') {
    let telInput;
}
$(document).ready(function () {
    let phoneNumber = document.querySelector("#phone_number");
    telInput = window.intlTelInput(phoneNumber, {
        initialCountry: "ca",
        separateDialCode: true,
        showFlags: false,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js"
    });
    inputTrimStart();
    $("#warehouse_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
            },
            country: {
                required: true
            },
            province: {
                required: true
            },
            city: {
                required: true
            },
            detail_address: {
                required: true
            },
            postal_code: {
                required: true
            },
            phone_number: {
                required: true,
                validPhone: [telInput],
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('phone')) {
                error.insertAfter(element.parent());
            }
            else {
                error.insertAfter(element);
            }
        },
    });
    initAjaxSelect2('country', '/api/v1/country/search');
    initAjaxSelect2('province', '/api/v1/province/search');
    initAjaxSelect2('city', '/api/v1/city/search');
    $('#country').on('change', function (data) {
        if ($(this).val() != '') {
            if ($('#province').val() != null) {
                $('#province').val('initialValue');
            }
            if ($('#city').val() != null) {
                $('#city').val('initialValue');
            }
            $('#province').prop('disabled', false);
        } else {
            $('#province').prop('disabled', true);
        }

    })
    $(`#country`).on('select2:select', function (e) {
        initAjaxSelect2('province', `/api/v1/province/search?countryId=${e.params?.data?.country_id}`);
    });
    $('#province').on('change', function (data) {
        const selectedValue = $(this).val();
        if (selectedValue != '') {
            if ($('#city').val() != null) {
                $('#city').val('initialValue');
            }
            $('#city').prop('disabled', false);
        } else {
            $('#city').prop('disabled', true);
        }
    });
    $(`#province`).on('select2:select', function (e) {
        initAjaxSelect2('city', `/api/v1/city/search?provinceId=${e.params?.data?.province_id}`);
    });
});

$(`#country`).on('change', function () {
    if ($(this).valid()) {
        $('#country-error').hide();
    }
})

$(`#province`).on('change', function () {
    if ($(this).valid()) {
        $('#province-error').hide();
    }
})
$('#city').on('change', function (data) {
    if ($(this).valid()) {
        $('#province-error').hide();
    }
})

function showModalCreate (id) {
    if (id == 'country') {
        showSmallModal_v2(trans('translation.country.create'), `/country/create`);
    }
    if (id == 'province') {
        showSmallModal(trans('translation.province.create'), `/province/create`);
    }
    if (id == 'city') {
        showSmallModal(trans('translation.city.create'), `/city/create`);
    }
}
