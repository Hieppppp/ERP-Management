$(document).ready(function () {
    inputTrimStart();
    let categoriesId = $('#categoryId').val();
    $("#category_update").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                validate: [`unique:categories,name,${categoriesId},id,deleted_at,NULL`, trans('translation.category.name')]
            },
            description: {
                maxlength: 1000
            }
        }
    });
});
function editCategory () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#category_update").valid()) {
            const categoryIdEdit = $('#categoryId').val();
            var formData = $('#category_update').serialize();
            $.ajax({
                url: `/api/v1/category/${categoryIdEdit}`,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshCategoryTable === 'function') {
                        refreshCategoryTable()
                    }
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshCategoryTable === 'function') {
                        refreshCategoryTable()
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
