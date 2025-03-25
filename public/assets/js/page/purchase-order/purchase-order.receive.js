let dataSupplier = [purchaseOrder?.supplier];
let isSelectOneSupplier = true;
let productDatatable;
let dataProduct = purchaseOrder?.products;
let isDataChanged = false;
let includeSupplierInProductList = true; // check if supplierIdForProductList is included in the product list
let supplierIdForProductList = [purchaseOrder?.supplier.id]; // supplierIdForProductList is included in the product list
let dataSupplierTemporary = [];
let dataWarehouse = purchaseOrder?.warehouse;
$(function (e) {
    $.validator.addMethod("validateSku", function (value, element, arg) {
        let isValid = false;
        var sku = $(`input[name='${$(element).attr('name')}']`).map(function () {
            return $(this).val();
        }).get();

        if (new Set(sku).size !== sku.length) {
            return false;
        }
        $.ajax({
            url: `/api/v1/validation/check-validate`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': `Bearer ${$('meta[name="token-api"]').attr('content')}`
            },
            type: "POST",
            dataType: "json",
            data: {
                key: value,
                rule: `unique:product_suppliers,sku`
            },
            async: false,
            success: function (response) {
                isValid = response;
            },
        });
        return isValid;
    }, trans('validation.uniqueSku'));
    productDatatable = $("#product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataProduct,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return data.code;
                },
                "createdCell": function (td, cellData, rowData, col, data) {
                    if (rowData.deleted_at) {
                        $(td).addClass('deleted-text-item');
                    }
                },
                width: "5%",
            },
            {
                data: function (data, type, row) {
                    let imageUrl = '/assets/images/no-image.png';
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`
                },
                "createdCell": function (td, cellData, rowData, row, col, data) {
                    if (rowData.deleted_at) {
                        $(td).addClass('deleted-image-item');
                    }
                },
                width: "10%",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                "createdCell": function (td, cellData, rowData, row, col, data) {
                    if (rowData.deleted_at) {
                        $(td).addClass('deleted-text-item');
                    }
                },
                width: "20%",
            },
            {
                data: function (data, type, row) {
                    if (data.hasOwnProperty('unit_name')) {
                        return truncateText(data.unit_name, 20);
                    }
                    return truncateText(`${data?.unit?.name}(${data?.unit?.symbol})`);
                },
                width: "15%",
            },
            {
                data: function (data, type, row) {
                    if (data.hasOwnProperty('category_name')) {
                        return truncateText(data.category_name, 20);
                    }
                    return truncateText(data?.category?.name, 20);
                },
                width: "15%",
            },
            {
                data: function (data, type, row) {
                    return data?.quantity
                },
                width: "10%",
            },
            {
                data: function (data, type, row) {
                    return purchaseOrder.status == 'cancel'
                        ? ''
                        : (purchaseOrder.status == 'pending'
                            ? `<input type="number" class="form-control" name="received_quantity[]" max="${data?.quantity}" required id='received_quantity_${data.id}' value="${data?.quantity}">`
                            : data?.received_quantity
                        );
                },
                width: "15%",
            },
            {
                data: function (data, type, row) {
                    let sku = getSku(data.suppliers);
                    return sku ? sku : (purchaseOrder.status == 'pending' ? `<input type="text" class="form-control" name="sku[]" required id='sku_${data.id}'>` : '');
                },
                width: "20%",
            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            }
        ],
    });
    $("#purchase_order_receive").validate({
        rules: {
            "received_quantity[]": {
                required: true,
                min: 0
            },
            'sku[]': {
                required: true,
                maxlength: 255,
                validateSku: true
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('fc-datepicker')) {
                error.insertAfter(element.closest('.input-group'));
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: function (event, validator) {
            if (validator.numberOfInvalids()) {
                var invalidElement = $(validator.errorList[0].element);
                if (invalidElement.closest('table').length) {
                    var row = invalidElement.closest('tr');
                    var scrollBody = $('#product_datatable').parent().first();
                    var scrollTop = scrollBody.scrollTop();
                    var rowTop = $(row).position().top;
                    scrollBody.scrollTop(scrollTop + rowTop - 50);
                }
            }
        }
    });
    $('input, textarea, select').on('change input', function (event) {
        isDataChanged = true
    });
});
function showModal () {
    showConfirmModal('receivePurchaseOrder()', '', trans('message.confirmReceiveProduct'))
}
function receivePurchaseOrder () {
    showLoadingSpinner();
    setTimeout(() => {
        if ($('#purchase_order_receive').valid()) {
            let data = {};
            if (dataProduct.length > 0) {
                data.products = dataProduct.map((item, index) => {
                    return {
                        id: item.id,
                        received_quantity: $(`#received_quantity_${item.id}`).val(),
                        sku: $(`#sku_${item.id}`).val()
                    }
                });
                data.received_note = $('#received_note').val();
            }
            $.ajax({
                processData: false,
                contentType: false,
                url: `/api/v1/purchase-order/${purchaseOrder.id}/receive`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(data),
                success: function (response) {
                    hideLoadingSpinner()
                    window.location.href = `/purchase-order/${purchaseOrder.id}`;
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner()
                    if (jqXHR.responseJSON?.message) {
                        return notification('error', jqXHR.responseJSON?.message);
                    }
                    notification("error", trans("message.error"));
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
    closeConfirmModal();
}

function getSku (data) {
    data = data.filter((item) => {
        return item.id == dataSupplier[0].id;
    });
    if (data.length > 0) {
        return data[0]?.pivot?.sku;
    }
    return '';
}
