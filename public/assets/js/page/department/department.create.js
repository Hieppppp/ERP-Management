$(document).ready(function () {
    inputTrimStart();
    $("#department_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                validate: ['unique:departments,name,NULL,id,deleted_at,NULL', trans('translation.department.name')]
            },
            description: {
                maxlength: 1000
            }
        }
    });
});

function createDepartment () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#department_create").valid()) {
            var formData = $('#department_create').serialize();
            $.ajax({
                url: '/api/v1/department',
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    notification("success", trans("message.success"));
                    closeSmallModal();
                    if (typeof refreshDepartmentTable === 'function') {
                        refreshDepartmentTable()
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner()
                    notification("error", trans("message.error"));
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}
