$(document).ready(function () {
    inputTrimStart();
    let shelveId = $('#shelveId').val();
    $("#shelve_edit").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                validate: function () {
                    return [`unique:shelves,name,${shelveId},id,warehouse_id,${$('#warehouse_id').val()},deleted_at,NULL`, trans('translation.shelve.name')]
                }
            },
            warehouse_id: {
                required: true
            },
            location: {
                required: true,
                maxlength: 1000
            }
        },
    });
    initAjaxSelect2('warehouse_id', '/api/v1/warehouse/search', false, shelve?.warehouses?.id, shelve?.warehouses?.name);
});
$(`#warehouse_id`).on('change', function () {
    if ($(this).valid()) {
        $('#warehouse_id-error').hide();
    }
})
