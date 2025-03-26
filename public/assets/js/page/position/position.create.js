$(document).ready(function () {
    inputTrimStart();
    $("#position_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                validate: ['unique:positions,name,NULL,id,deleted_at,NULL', trans('translation.position.name')]
            },
            description: {
                maxlength: 1000
            }
        }
    });
});

function createPosition () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#position_create").valid()) {
            var formData = $('#position_create').serialize();
            $.ajax({
                url: '/api/v1/position',
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    notification("success", trans("message.success"));
                    closeSmallModal();
                    if (typeof refreshPositionTable === 'function') {
                        refreshPositionTable()
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
