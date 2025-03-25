$(document).ready(function () {
    inputTrimStart();
    $("#category_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                validate: ['unique:categories,name,NULL,id,deleted_at,NULL', trans('translation.category.name')]
            },
            description: {
                maxlength: 1000
            }
        }
    });
});

function createCategory () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#category_create").valid()) {
            var formData = $('#category_create').serialize();
            $.ajax({
                url: '/api/v1/category',
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    notification("success", trans("message.success"));
                    closeSmallModal();
                    if (typeof refreshCategoryTable === 'function') {
                        refreshCategoryTable()
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
