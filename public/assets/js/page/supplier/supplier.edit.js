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
    telInput.setNumber(supplier.phone);
    let supplierId = $("#supplierId").val();

    $("#supplier_update").validate({
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
                    `unique:suppliers,email,${supplierId},id,deleted_at,NULL`,
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
    initAjaxSelect2(
        "country",
        "/api/v1/country/search",
        false,
        supplier?.country,
        supplier?.country
    );
    initAjaxSelect2(
        "province",
        `/api/v1/province/search?countryName=${supplier?.country}`,
        false,
        supplier?.province,
        supplier?.province
    );
    initAjaxSelect2(
        "city",
        `/api/v1/city/search?provinceName=${supplier?.province}&countryName=${supplier?.country}`,
        false,
        supplier?.city,
        supplier?.city
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

function updateSupplier () {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#supplier_update").valid()) {
            $("#phone").val(telInput.getNumber());
            $("#supplier_update").submit();
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
        showBackModal("/supplier");
    } else {
        window.location.href = "/supplier";
    }
}
