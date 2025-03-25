$(document).ready(function () {
    inputTrimStart();
    let unitId = $('#unitId').val();
    $("#unit_update").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            symbol: {
                validate: [`unique:units,symbol,${unitId},id,deleted_at,NULL`, trans('translation.unit.symbol')],
                required: true,
                maxlength: 100
            },
            description: {
                maxlength: 1000
            }
        }
    })

});

function editUnit () {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#unit_update").valid()) {
            const unitIdEdit = $('#unitId').val();
            var formData = $('#unit_update').serialize();
            $.ajax({
                url: `/api/v1/unit/${unitIdEdit}`,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshUnitTable === 'function') {
                        refreshUnitTable()
                    }
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    closeSmallModal();
                    hideLoadingSpinner()
                    if (typeof refreshUnitTable === 'function') {
                        refreshUnitTable()
                    }
                    if (jqXHR?.responseJSON) {
                        if (Array.isArray(jqXHR.responseJSON?.message)) {
                            return notification('error', xhr.responseJSON?.message[0]);
                        }
                    }
                    notification('error', trans('message.modal_not_found'));
                }
            })
        } else {
            hideLoadingSpinner();
        }
    }, 100);
}
