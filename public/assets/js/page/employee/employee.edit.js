if (typeof telInputEdit === 'undefined') {
    let telInputEdit;
}
let employeeId = $('#employeeId').val();
$(document).ready(function () {
    let phoneNumberUpdate = document.querySelector("#phone_number_update");
    telInputEdit = window.intlTelInput(phoneNumberUpdate, {
        initialCountry: "ca",
        separateDialCode: true,
        showFlags: false,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js"
    });
    if (employee?.phone) {
        telInputEdit.setNumber(employee.phone);
    }
    inputTrimStart();
    $("#employee_update").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                regexEmail: true,
                validate: [`unique:employees,email,${employeeId},id,deleted_at,NULL`, trans('translation.employee.name')]
            },
            unit_id: {
                required: true,
            },
            department_id: {
                required: true
            },
            position_id: {
                required: true
            },
            province_id: {
                required: true
            },
            city_id: {
                required: true
            },
            address_detail: {
                required: true
            },
            postal_code: {
                required: true
            },
            phone_number: {
                required: true,
                validPhone: [telInputEdit],
            }

        }, errorPlacement: function (error, element) {
            if (element.hasClass('phone')) {
                error.insertAfter(element.parent());
            }
            else {
                error.insertAfter(element);
            }
        },
    });
    initAjaxSelect2('department_id', '/api/v1/department/search', false, employee?.department_id);
    initAjaxSelect2('position_id', '/api/v1/position/search', false, employee?.position_id);
    
    $('input, textarea, select').on('change', function (event) {
        isDataChanged = true
    });
    initAjaxSelect2('country', '/api/v1/country/search', false, employee?.country, employee?.country);
    initAjaxSelect2('province', `/api/v1/province/search?countryName=${employee?.country}`, false, employee?.province, employee?.province);
    initAjaxSelect2('city', `/api/v1/city/search?provinceName=${employee?.province}&countryName=${employee?.country}`, false, employee?.city, employee?.city);
    $(`#country`).on('select2:select', function (e) {
        initAjaxSelect2('province', `/api/v1/province/search?countryId=${e.params?.data?.country_id}`);
    });
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

$(`#department`).on('change', function () {
    if ($(this).valid()) {
        $('#department_id-error').hide();
    }
})

$(`#position`).on('change', function () {
    if ($(this).valid()) {
        $('#position_id-error').hide();
    }
})

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
        $('#city-error').hide();
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
