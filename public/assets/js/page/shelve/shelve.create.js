$(document).ready(function () {
    inputTrimStart();
    let warehouseID = $('#warehouse_id').val();
    $("#shelve_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                validate: function () {
                    return [`unique:shelves,name,NULL,id,warehouse_id,${$('#warehouse_id').val()},deleted_at,NULL`, trans('translation.shelve.name')]
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
    if (warehouse) {
        initAjaxSelect2('warehouse_id', '/api/v1/warehouse/search', false, warehouse.id, warehouse.name);
    } else {
        initAjaxSelect2('warehouse_id', '/api/v1/warehouse/search');
    }

});
$(`#warehouse_id`).on('change', function () {
    if ($(this).valid()) {
        $('#warehouse_id-error').hide();
    }
})
