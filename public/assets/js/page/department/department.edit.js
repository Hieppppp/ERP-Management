$(document).ready(function () {
    inputTrimStart();
    let departmentsId = $('#departmentId').val();
    $("#department_update").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                validate: [`unique:departments,name,${departmentsId},id,deleted_at,NULL`, trans('translation.department.name')]
            },
            description: {
                maxlength: 1000
            }
        }
    });
});
function editDepartment () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#department_update").valid()) {
            const departmentIdEdit = $('#departmentId').val();
            var formData = $('#department_update').serialize();
            $.ajax({
                url: `/api/v1/department/${departmentIdEdit}`,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshDepartmentTable === 'function') {
                        refreshDepartmentTable()
                    }
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshDepartmentTable === 'function') {
                        refreshDepartmentTable()
                    }
                    if (jqXHR?.responseJSON) {
                        if (Array.isArray(jqXHR.responseJSON?.message)) {
                            return notification('error', xhr.responseJSON?.message[0]);
                        }
                    }
                    notification('error', trans('message.modal_not_found'));
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}
