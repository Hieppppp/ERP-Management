let telInput;
let isDataChanged = false;
$(document).ready(function () {
    let phoneNumber = document.querySelector("#phone_number");
    telInput = window.intlTelInput(phoneNumber, {
        initialCountry: "ca",
        separateDialCode: true,
        showFlags: false,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js",
    });
    $("#supplier_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
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
            detail_address: {
                maxlength: 1000,
                required: true,
            },
            phone_number: {
                required: true,
                validPhone: [telInput],
            },
            email: {
                required: true,
                regexEmail: true,
                validate: [
                    `unique:suppliers,email,NULL,id,deleted_at,NULL`,
                    trans("translation.supplier.email"),
                ],
            },
            site: {
                validURL: true,
            },
            postal_code: {
                required: true,
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
    initAjaxSelect2("country", "/api/v1/country/search");
    initAjaxSelect2("province", "/api/v1/province/search");
    initAjaxSelect2("city", "/api/v1/city/search");
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
    $(`#country`).on("select2:select", function (e) {
        initAjaxSelect2(
            "province",
            `/api/v1/province/search?countryId=${e.params?.data?.country_id}`
        );
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
});

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

function createSupplier() {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#supplier_create").valid()) {
            $("#phone").val(telInput.getNumber());
            $("#supplier_create").submit();
        } else {
            hideLoadingSpinner();
        }
    }, 100);
}

function showModalCreate(id) {
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

function back() {
    if (isDataChanged) {
        showBackModal("/supplier");
    } else {
        window.location.href = "/supplier";
    }
}
