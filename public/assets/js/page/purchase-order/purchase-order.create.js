let dataSupplier = supplier ? [supplier] : [];
let isSelectOneSupplier = true;
let productDatatable;
let dataProduct = product ? [product] : [];
let isDataChanged = false;
let includeSupplierInProductList = true; // check if supplierIdForProductList is included in the product list
let supplierIdForProductList = supplier?.id ? [supplier.id] : []; // supplierIdForProductList is included in the product list
let dataSupplierTemporary = [];
let dataWarehouse = {};
$(function (e) {
    initDatePicker([
        ['scheduled_date', new Date()]
    ]);
    
    $('#scheduled_date').datepicker('setDate', new Date()).datepicker('update');
    $('#supplier_name').on('click', function () {
        showLargeModal(trans('translation.supplier.selectSupplier'), '/supplier/select')
    });
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
                data: "id",
            },
            {
                data: function (data, type, row) {
                    let imageUrl = '/assets/images/no-image.png';
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    if (data.hasOwnProperty('unit_name')) {
                        return truncateText(data.unit_name);
                    }
                    return truncateText(`${data?.unit?.name}(${data?.unit?.symbol})`);
                },
            },
            {
                data: function (data, type, row) {
                    if (data.hasOwnProperty('category_name')) {
                        return truncateText(data.category_name);
                    }
                    return truncateText(data?.category?.name);
                },
            },
            {
                data: function (data, type, row) {
                    return `<input type="number" class="form-control" name="quantity[]" min="0" required id='quantity_${data.id}'>`;
                },
            },
            {
                data: function (data, type, row) {
                    const unitCost = getUnitCost(data.suppliers);
                    return `${unitCost}$<input type="number" class="form-control" style="border:0px !important;" name="unit_cost[]" min="0" required id='unit_cost_${data.id}' value='${getUnitCost(data.suppliers)}' hidden>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `<span id='total_cost_${data.id}'></span>`;
                },
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeProduct('${data.id}', this)"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
            {
                className: 'text-end',
                targets: -2
            },
            {
                className: 'text-end',
                targets: -3
            },
        ],
    });
    $("#purchase_order_create").validate({
        rules: {
            supplier_name: {
                required: true
            },
            scheduled_date: {
                notPastDate: true
            },
            "quantity[]": {
                required: true,
                min: 1
            },
            "unit_cost[]": {
                required: true
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
    $('#scheduled_date').on('change', function () {
        $(this).valid();
    });
    $('#product_datatable').on('input', 'input[name="unit_cost[]"], input[name="quantity[]"]', function () {
        var productId = $(this).attr('id').split('_');
        productId = productId[productId.length - 1];
        if (productId) {
            var quantity = $(`#quantity_${productId}`).val();
            var unitCost = $(`#unit_cost_${productId}`).val();
            if (quantity && unitCost) {
                $(`#total_cost_${productId}`).text(`${numberFormat(quantity * unitCost, 2)}$`);
                calculateTotalAmount();
            }
        }
    });
    $('#warehouse_name').on('click', function () {
        showLargeModal(trans('translation.warehouse.selectWarehouse'), '/warehouse/select')
    });
    calculateTotalAmount()
});

function getUnitCost (data) {
    const supplierId = dataSupplier[0].id;
    data.filter((item) => {
        return item.id == supplierId;
    });
    if (data.length > 0) {
        return data[0]?.pivot?.unit_cost;
    }
    return 0;
}

function calculateTotalAmount () {
    var totalAmount = 0;
    $('#product_datatable span[id^="total_cost_"]').each(function () {
        let totalCost = $(this).text().replace('$', '').replace(/,/g, '');
        if (typeof totalCost === 'string' && !isNaN(totalCost) && !isNaN(parseFloat(totalCost))) {
            totalAmount += parseFloat(totalCost);
        }
    });
    $('#totalAmount').text(`${numberFormat(totalAmount, 2)}$`);
}

function updateSupplier () {
    if (dataSupplier[0]?.id != dataSupplierTemporary[0].id) {
        isDataChanged = true;
        if (dataSupplierTemporary.length > 0) {
            dataSupplier = JSON.parse(JSON.stringify(dataSupplierTemporary));
            $('#supplier_id').val(dataSupplierTemporary[0].id);
            $('#supplier_name').val(dataSupplierTemporary[0].name);
            $('#supplier_name').valid();
            dataSupplierTemporary = [];
            updateDatatableProduct([])
            calculateTotalAmount()
        }
    }
    closeConfirmModal();
    closeLargeModal();
}

function refreshSupplier (data) {
    dataSupplierTemporary = data;
    if (dataSupplier.length > 0 && dataProduct.length > 0 && dataSupplier[0].id != dataSupplierTemporary[0].id) {
        showConfirmModal('updateSupplier()', '', trans('message.confirmChangeSupplierBatchOrder'))
        return;
    }
    updateSupplier()
}

function selectModal () {
    if (dataSupplier.length <= 0) {
        return notification('error', trans('message.pleaseSelectAtLeast1item', { item: trans('translation.menu.supplier') }))
    }
    supplierIdForProductList = []
    supplierIdForProductList.push(dataSupplier[0].id);
    showLargeModal(trans('translation.batchOrder.selectProduct'), '/product/select')
}

function refreshProduct (data) {
    if (data != dataProduct) {
        isDataChanged = true;
    }
    updateDatatableProduct(data);
    closeLargeModal();
}

function updateDatatableProduct (data) {
    var currentDataMap = {};
    dataProduct.forEach(function (item) {
        currentDataMap[item.id] = item;
    });

    var newDataProduct = [];
    var newDataMap = {};

    data.forEach(function (newItem) {
        var id = newItem.id;
        if (currentDataMap[id]) {
            newDataProduct.push(currentDataMap[id]);
            newDataMap[id] = true;
        } else {
            newDataProduct.push(newItem);
            productDatatable.row.add(newItem).draw();
            newDataMap[id] = true;
        }
    });
    dataProduct.forEach(function (oldItem) {
        var id = oldItem.id;
        if (!newDataMap[id]) {
            productDatatable.rows(function (idx, data, node) {
                return data.id === id;
            }).remove().draw();
        }
    });
    dataProduct = data;
    productDatatable.draw();
    calculateTotalAmount()
}

function removeProduct (id, e) {
    isDataChanged = true;
    dataProduct = dataProduct.filter(el => el.id != id);
    var row = $(e).closest('tr');
    productDatatable.row(row).remove().draw();
    calculateTotalAmount()
}

function back () {
    if (isDataChanged) {
        showBackModal('/purchase-order')
    } else {
        window.location.href = "/purchase-order";
    }
}

function renderWarehouse (data) {
    if (data?.id != dataWarehouse.id) {
        isDataChanged = true;
        dataWarehouse = data;
        $('#warehouse_id').val(data.id);
        $('#warehouse_name').val(data.name);
        $("#warehouse-address").text(data.address);
        $("#warehouse-contact").text(`${trans('translation.warehouse.contact')}: ${data?.contact ?? ''}`);
        $('#warehouse_name').valid();
    }
    closeLargeModal();
}

function confirmSendPurchaseOrder () {
    showConfirmModal('createPurchaseOrder(true)', '', trans('message.confirmSendPurchaseOrder'))
}

function confirmCreatePurchaseOrder () {
    showConfirmModal('createPurchaseOrder()', '', trans('message.confirmCreatePurchaseOrder'));
}

function createPurchaseOrder (isSendOrder = false) {
    showLoadingSpinner();
    setTimeout(() => {
        if ($('#purchase_order_create').valid()) {
            if (dataProduct.length <= 0) {
                hideLoadingSpinner();
                notification('error', trans('message.pleaseSelectAtLeast1item', { item: trans('translation.menu.product') }));
                return;
            }
            var form = $('#purchase_order_create')[0];
            var formData = new FormData(form);
            if (dataProduct.length > 0) {
                dataProduct.forEach((item, index) => {
                    formData.append(`products[${index}][id]`, item.id);
                    formData.append(`products[${index}][quantity]`, $(`#quantity_${item.id}`).val());
                    formData.append(`products[${index}][unit_cost]`, $(`#unit_cost_${item.id}`).val());
                });
            }
            formData.append('is_send_order', isSendOrder ? 1 : 0);
            $.ajax({
                processData: false,
                contentType: false,
                url: '/api/v1/purchase-order',
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    window.location.href = !isSendOrder ? "/purchase-order" : `/purchase-order/${response.data.id}`;
                    notification("success", trans("message.success"));
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
    closeConfirmModal();
}

function getUnitCost (data) {
    const supplierId = dataSupplier[0].id;
    data = data.filter((item) => {
        return item.id == supplierId;
    });
    if (data.length > 0) {
        return data[0]?.pivot?.unit_cost;
    }
    return 0;
}
