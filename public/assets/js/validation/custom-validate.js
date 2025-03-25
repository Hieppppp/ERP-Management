$(document).ready(function () {
    $.validator.prototype.checkForm = function () {
        this.prepareForm();
        for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
            if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
                for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                    this.check(this.findByName(elements[i].name)[cnt]);
                }
            } else {
                this.check(elements[i]);
            }
        }
        return this.valid();
    };
    let timerValidateUnique;
    $.validator.addMethod("validate", function (value, element, arg) {
        let isValid = false;
        let rule = arg;
        if (typeof arg !== "string") {
            rule = arg[0];
        }
        $.ajax({
            url: `/api/v1/validation/check-validate`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': `Bearer ${$('meta[name="token-api"]').attr('content')}`
            },
            type: "POST",
            dataType: "json",
            data: {
                key: value,
                rule: rule
            },
            async: false,
            success: function (response) {
                isValid = response;
            }
        });
        return isValid;
    });

    $.validator.addMethod("uniqueProvince", function (value, element, arg) {
        let isValid = false;
        let country = arg;
        if (typeof arg !== "string") {
            country = arg[0];
        }
        if (country && $(`#${country}`).val()) {
            let countryId = $(`#${country}`).val();
            $.ajax({
                url: `/api/v1/validation/check-validate`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': `Bearer ${$('meta[name="token-api"]').attr('content')}`
                },
                type: "POST",
                dataType: "json",
                data: {
                    key: value,
                    rule: `unique:provinces,name,null,null,country_id,${countryId}`
                },
                async: false,
                success: function (response) {
                    isValid = response;
                },
            });
        } else {
            return true
        }
        return isValid;
    }, trans('validation.uniqueProvince'));

    $.validator.addMethod("uniqueCity", function (value, element, arg) {
        let isValid = false;
        let province = arg;
        if (typeof arg !== "string") {
            province = arg[0];
        }
        if (province && $(`#${province}`).val()) {
            let provinceId = $(`#${province}`).val();
            $.ajax({
                url: `/api/v1/validation/check-validate`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': `Bearer ${$('meta[name="token-api"]').attr('content')}`
                },
                type: "POST",
                dataType: "json",
                data: {
                    key: value,
                    rule: `unique:cities,name,null,null,province_id,${provinceId}`
                },
                async: false,
                success: function (response) {
                    isValid = response;
                },
            });
        } else {
            return true
        }
        return isValid;
    }, trans('validation.uniqueProvince'));

    $.validator.addMethod("noSpecialChars", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\u00C0-\u024F\u1E00-\u1EFF\s]+$/.test(value);
    }, trans('validation.noSpecialChars'));


    $.validator.addMethod("validPostalCode", function (value, element) {
        return this.optional(element) || /^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]\d[ABCEGHJKLMNPRSTVWXYZ]\d$/.test(value);
    });

    $.validator.addMethod("validPhone", function (value, element, args) {
        return this.optional(element) || args[0].isValidNumber();
    });

    $.validator.addMethod("passwordRegex", function (value, element) {
        return /^(?=.*[A-Z])(?=.*\d).+$/.test(value);
    });

    $.validator.addMethod("validURL", function (value, element) {
        const regex = /^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(\/[^\s]*)?$/i;
        return this.optional(element) || regex.test(value);
    });

    $.validator.addMethod("regexEmail", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9._%]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i.test(value);
    });

    $.validator.addMethod("validMargin", function (value, element) {
        return this.optional(element) || /^-?\d+(\.\d{1,2})?$/.test(value);
    });

    $.validator.addMethod("validDecimal", function (value, element) {
        return this.optional(element) || /^-?\d+(\.\d+)?$/.test(value);
    });

    $.validator.addMethod("greaterThan", function (value, element, args) {
        return this.optional(element) || (parseFloat(value) > args);
    });

    $.validator.addMethod("dateGreaterThan",
        function (value, element, params) {
            if ($(params[0]).val() && value) {
                const enDate = moment(value, params[2]);
                const startDate = moment($(params[0]).val(), params[2]);
                return enDate >= startDate;
            }
            return true;
        }, trans('validation.greaterThan', { field: '{1}' }
        ));

    $.validator.addMethod("accept",
        function (value, element, params) {
            var file = element.files[0];
            var pattern = /^image\//;
            if (file && !pattern.test(file.type)) {
                return false;
            }
            return true;
        }, trans('validation.extension'));

    $.extend($.validator.messages, {
        required: trans("validation.required"),
        email: trans('validation.email'),
        digits: trans("validation.digits"),
        number: trans("validation.number"),
        equalTo: trans("validation.equalTo", {
            field: '{1}'
        }),
        maxlength: jQuery.validator.format(trans('validation.maxlength', {
            length: '{0}'
        })
        ),
        minlength: jQuery.validator.format(trans('validation.minlength', {
            length: '{0}'
        })
        ),
        validate: jQuery.validator.format(trans("validation.unique", { field: '{1}' })),
        validPostalCode: jQuery.validator.format(trans("validation.invaliRegex", { field: '{0}' })),
        passwordRegex: trans("validation.passwordRegex"),
        regexEmail: trans("validation.email"),
        step: jQuery.validator.format(trans("validation.step", { value: '{0}' })),
        validMargin: jQuery.validator.format(trans("validation.invaliRegex", { field: '{0}' })),
        validDecimal: jQuery.validator.format(trans("validation.invaliRegex", { field: '{0}' })),
        validPhone: trans("validation.invalid_phone_number"),
        validURL: trans("validation.invalid_url"),
        greaterThan: jQuery.validator.format(trans("validation.greaterThan", { field: '{0}' })),
    });

    $.validator.setDefaults({
        onsubmit: false,
        onfocusout: false,
        onkeyup: function (element) {
            $(element).removeClass('error');
            $(element).next('label.error').remove();
        },
        onclick: false,

    });
    $.validator.addMethod('filesize', function (value, element, arg) {
        var file = element.files[0];
        if (file && file.size > arg * 1024 * 1024) {
            return false;
        }
        return true;
    }, trans('validation.largerThanFile', {
        field: '{0}'
    }));

    $.validator.addMethod('validateImage', function (value, element, arg) {
        arg = arg.split('|');
        if (value && !arg.includes(value.split('.').pop().toLowerCase())) {
            return false;
        }
        return true;
    }, trans('validation.importPhotos', {
        field: '{0}'
    }));

    $.validator.addMethod("notPastDate", function (value, element, arg) {
        let format = "YYYY-MM-DD"
        if (typeof arg === "string") {
            format = arg;
        }
        if (!moment(value, format, true).isValid()) {
            return true;
        }
        var inputDate = moment(value, format);
        var today = moment().startOf('day');
        return this.optional(element) || inputDate.isSameOrAfter(today);
    }, trans('validation.notPastDate'));
});
