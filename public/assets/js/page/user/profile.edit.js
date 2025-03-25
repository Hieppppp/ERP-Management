$(document).ready(function () {
    const userId = $("#userId").val();
    $("#info_update").validate({
        onfocusout: false,
        rules: {
            email: {
                required: true,
                regexEmail: true,
                validate: `unique:users,email,${userId}`,
            },
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
        },
        messages: {
            email: {
                required: trans("validation.required"),
                regexEmail: trans("validation.email"),
                validate: trans("validation.unique", { field: "email" }),
            },
            first_name: {
                required: trans("validation.required"),
            },
            last_name: {
                required: trans("validation.required"),
            },
        }
    });

    $("#password_update").validate({
        onfocusout: false,
        rules: {
            password: {
                required: true,
                checkPassword: true
            },
            new_password: {
                required: true,
                minlength: 8,
                passwordRegex: true,
            },
            new_confirm_password: {
                required: true,
                equalTo: '#new_password'
            },
        },
        messages: {
            password: {
                required: trans("validation.required"),
                checkPassword: trans("validation.incorrectPassword"),
            },
            new_password: {
                required: trans("validation.required"),
                minlength: trans("validation.minlength", { length: 8 }),
                passwordRegex: trans("validation.passwordRegex"),
            },
            new_confirm_password: {
                required: trans("validation.required"),
                equalTo: trans("validation.equalTo", { field: trans('translation.user.newPassword') }),
            },
        }
    });

    $.validator.addMethod("checkPassword", function(value, element) {
        let isValid = false;
        $.ajax({
            url: `/api/v1/validation/check-password`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type: "POST",
            dataType: "json",
            data: {
                password: value
            },
            async: false,
            success: function(response) {
                isValid = response;
            }
        });
        return isValid;
    });
});
