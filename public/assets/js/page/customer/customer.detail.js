let phoneTelInput;
let companyTelInput;
$(document).ready(function () {
    let phoneNumber = document.querySelector("#phone_number");
    phoneTelInput = window.intlTelInput(phoneNumber, {
        showFlags: false,
        initialCountry: "ca",
        separateDialCode: true,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js"
    });
    phoneTelInput.setNumber(customer.phone);

    let companyPhone = document.querySelector("#company_phone_number");
    companyTelInput = window.intlTelInput(companyPhone, {
        initialCountry: "ca",
        separateDialCode: true,
        showFlags: false,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js"
    });
    companyTelInput.setNumber(customer.company_phone);

});
