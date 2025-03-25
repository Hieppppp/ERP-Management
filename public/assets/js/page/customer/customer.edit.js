let phoneTelInput;
let companyTelInput;
let isDataChanged = false;
$(document).ready(function () {
    let phoneNumber = document.querySelector("#phone_number");
    phoneTelInput = window.intlTelInput(phoneNumber, {
        initialCountry: "ca",
        separateDialCode: true,
        showFlags: false,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js",
    });
    phoneTelInput.setNumber(customer.phone);

    let companyPhone = document.querySelector("#company_phone_number");
    companyTelInput = window.intlTelInput(companyPhone, {
        initialCountry: "ca",
        showFlags: false,
        separateDialCode: true,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js",
    });
    if (customer.company_phone) {
        companyTelInput.setNumber(customer.company_phone);
    }

    let customerId = $("#customerId").val();

    $("#customer_update").validate({
        onfocusout: false,
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            email: {
                required: true,
                regexEmail: true,
                validate: [
                    `unique:customers,email,${customerId},id,deleted_at,NULL`,
                    trans("translation.customer.email"),
                ],
            },
            phone_number: {
                required: true,
                validPhone: [phoneTelInput],
            },
            country: {
                required: true,
            },
            province: {
                required: true,
            },
            city: {
                required: true,
            },
            postal_code: {
                required: true,
            },
            detail_address: {
                maxlength: 1000,
                required: true,
            },
            contact_url: {
                validURL: true,
            },
            company_email: {
                required: true,
                regexEmail: true,
                validate: [
                    `unique:customers,company_email,${customerId},id,deleted_at,NULL`,
                    trans("translation.company.email"),
                ],
            },
            company_phone_number: {
                validPhone: [companyTelInput],
            },
            company_site: {
                validURL: true,
            },
        },
        errorPlacement: function (error, element) {
            if (
                element.hasClass("phone") ||
                element.hasClass("profile-img-file-input")
            ) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });
    // Customer address
    initAjaxSelect2(
        "country",
        "/api/v1/country/search",
        false,
        customer?.country,
        customer?.country
    );
    initAjaxSelect2(
        "province",
        `/api/v1/province/search?countryName=${customer?.country}`,
        false,
        customer?.province,
        customer?.province
    );
    initAjaxSelect2(
        "city",
        `/api/v1/city/search?provinceName=${customer?.province}&countryName=${customer?.country}`,
        false,
        customer?.city,
        customer?.city
    );
    $(`#country`).on("select2:select", function (e) {
        initAjaxSelect2(
            "province",
            `/api/v1/province/search?countryId=${e.params?.data?.country_id}`
        );
    });
    $("#country").on("change", function (data) {
        if ($(this).val() != "") {
            if ($("#province").val() != null) {
                $("#province").val("initialValue");
            }
            if ($("#city").val() != null) {
                $("#city").val("initialValue");
            }
            $("#province").prop("disabled", false);
        } else {
            $("#province").prop("disabled", true);
        }
    });
    $("#province").on("change", function (data) {
        const selectedValue = $(this).val();
        if (selectedValue != "") {
            if ($("#city").val() != null) {
                $("#city").val("initialValue");
            }
            $("#city").prop("disabled", false);
        } else {
            $("#city").prop("disabled", true);
        }
    });
    $(`#province`).on("select2:select", function (e) {
        initAjaxSelect2(
            "city",
            `/api/v1/city/search?provinceId=${e.params?.data?.province_id}`
        );
    });
    $("input, textarea, select").on("change input", function (event) {
        isDataChanged = true;
    });

    // Company address
    initAjaxSelect2(
        "company_country",
        "/api/v1/country/search",
        false,
        customer?.company_country,
        customer?.company_country
    );
    initAjaxSelect2(
        "company_province",
        `/api/v1/province/search?countryName=${customer?.company_country}`,
        false,
        customer?.company_province,
        customer?.company_province
    );
    initAjaxSelect2(
        "company_city",
        `/api/v1/city/search?provinceName=${customer?.company_province}&countryName=${customer?.company_country}`,
        false,
        customer?.company_city,
        customer?.company_city
    );
    $(`#company_country`).on("select2:select", function (e) {
        initAjaxSelect2(
            "company_province",
            `/api/v1/province/search?countryId=${e.params?.data?.country_id}`
        );
    });
    $("#company_country").on("change", function (data) {
        if ($(this).val() != "") {
            if ($("#company_province").val() != null) {
                $("#company_province").val("initialValue");
            }
            if ($("#company_city").val() != null) {
                $("#company_city").val("initialValue");
            }
            $("#company_province").prop("disabled", false);
        } else {
            $("#company_province").prop("disabled", true);
        }
    });
    $("#company_province").on("change", function (data) {
        const selectedValue = $(this).val();
        if (selectedValue != "") {
            if ($("#company_city").val() != null) {
                $("#company_city").val("initialValue");
            }
            $("#company_city").prop("disabled", false);
        } else {
            $("#company_city").prop("disabled", true);
        }
    });
    $(`#company_province`).on("select2:select", function (e) {
        initAjaxSelect2(
            "company_city",
            `/api/v1/city/search?provinceId=${e.params?.data?.province_id}`
        );
    });
    $("input, textarea, select").on("change input", function (event) {
        isDataChanged = true;
    });

    $(`#payment_method`).select2({
        placeholder: "Please select",
    });
    $(`#payment_term`).select2({
        placeholder: "Please select",
    });
});
// Customer address
$(`#country`).on("change", function () {
    if ($(this).valid()) {
        $("#country-error").hide();
    }
});

$(`#province`).on("change", function () {
    if ($(this).valid()) {
        $("#province-error").hide();
    }
});
$("#city").on("change", function (data) {
    if ($(this).valid()) {
        $("#city-error").hide();
    }
});

// Company address
$(`#company_country`).on("change", function () {
    if ($(this).valid()) {
        $("#country-error").hide();
    }
});

$(`#company_province`).on("change", function () {
    if ($(this).valid()) {
        $("#province-error").hide();
    }
});
$("#company_city").on("change", function (data) {
    if ($(this).valid()) {
        $("#city-error").hide();
    }
});

function updateCustomer () {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#customer_update").valid()) {
            $("#phone").val(phoneTelInput.getNumber());
            $("#company_phone").val(companyTelInput.getNumber());
            $("#customer_update").submit();
        } else {
            hideLoadingSpinner();
        }
    }, 100);
}

function showModalCreate (id) {
    if (id == "country") {
        showSmallModal_v2(
            trans("translation.country.create"),
            `/country/create`
        );
    }
    if (id == "province") {
        showSmallModal(
            trans("translation.province.create"),
            `/province/create`
        );
    }
    if (id == "city") {
        showSmallModal(trans("translation.city.create"), `/city/create`);
    }
}

function back () {
    if (isDataChanged) {
        showBackModal("/customer");
    } else {
        window.location.href = "/customer";
    }
}
