let isDataChanged = false;
$(document).ready(function () {
    $("#user_create").validate({
        rules: {
            username: {
                required: true,
                validate: `unique:users,username`,
                maxlength: 50,
            },
            email: {
                required: true,
                regexEmail: true,
                validate: `unique:users,email`,
            },
            password: {
                required: true,
                minlength: 8,
                passwordRegex: true,
            },
            role: {
                required: true,
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
        },
        messages: {
            username: {
                required: trans("validation.required"),
                validate: trans("validation.unique", { field: "username" }),
            },
            email: {
                required: trans("validation.required"),
                regexEmail: trans("validation.email"),
                validate: trans("validation.unique", { field: "email" }),
            },
            password: {
                required: trans("validation.required"),
                minlength: trans("validation.minlength", { length: 8 }),
                passwordRegex: trans("validation.passwordRegex"),
            },
            role_id: {
                required: trans("validation.required"),
            },
            first_name: {
                required: trans("validation.required"),
            },
            last_name: {
                required: trans("validation.required"),
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass("select2-show-search")) {
                error.insertAfter(element.next(".select2-container"));
            } else {
                error.insertAfter(element);
            }
        },
    });

    $(".select2-show-search").select2({
        minimumResultsForSearch: "",
        width: "100%",
    });

    $("#role").on("change", function () {
        if ($(this).valid()) {
            if ($(this).val() == "user") {
                $("#rolePermission").show();
                $('#rolePermission input[type="checkbox"]').prop(
                    "checked",
                    false
                );
            } else {
                $("#rolePermission").hide();
            }
            $("#role_id-error").hide();
        }
    });

    $("input, textarea, select").on("change input", function (event) {
        isDataChanged = true;
    });

    $("#check-all").on("change", function () {
        const checked = $(this).is(":checked");
        $('input[name="permission_ids[]"]').prop("checked", checked);
    });

    $('input[name="permission_ids[]"]').on("change", function () {
        let checkAll = true;
        $('input[name="permission_ids[]"]').each((i, e) => {
            if (!$(e).is(":checked")) {
                checkAll = false;
            }
        });
        $("#check-all").prop("checked", checkAll);
    });
});
function back() {
    if (isDataChanged) {
        showBackModal("/user");
    } else {
        window.location.href = "/user";
    }
}
