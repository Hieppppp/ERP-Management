$(document).ready(function () {
    inputTrimStart();
    let positionsId = $('#positionId').val();
    $("#position_update").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                validate: [`unique:positions,name,${positionsId},id,deleted_at,NULL`, trans('translation.position.name')]
            },
            description: {
                maxlength: 1000
            }
        }
    });
});
function editPosition () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($("#position_update").valid()) {
            const positionIdEdit = $('#positionId').val();
            var formData = $('#position_update').serialize();
            $.ajax({
                url: `/api/v1/position/${positionIdEdit}`,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshPositionTable === 'function') {
                        refreshPositionTable()
                    }
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshPositionTable === 'function') {
                        refreshPositionTable()
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
