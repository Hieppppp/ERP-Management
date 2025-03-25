let dataSupplier = [purchaseOrder?.supplier];
let isSelectOneSupplier = true;
let productDatatable;
let dataProduct = purchaseOrder?.products;
let isDataChanged = false;
let includeSupplierInProductList = true; // check if supplierIdForProductList is included in the product list
let supplierIdForProductList = [purchaseOrder?.supplier.id]; // supplierIdForProductList is included in the product list
let dataSupplierTemporary = [];
let dataWarehouse = purchaseOrder?.warehouse ?? [];
$(function (e) {
    initDatePicker([
        ['scheduled_date', new Date()]
    ]);
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
                data: "code",
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
                    return `<input type="number" class="form-control" name="quantity[]" min="0" required id='quantity_${data.id}' value="${data?.pivot?.quantity}">`;
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
                    const unitCost = getUnitCost(data.suppliers);
                    return `<span id='total_cost_${data.id}'>${numberFormat(data?.pivot?.quantity * unitCost, 2)}$</span>`;
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
    $("#purchase_order_edit").validate({
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

    getTotalAmount();
});

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
    dataProduct = data;
    productDatatable.clear();
    productDatatable.rows.add(dataProduct);
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
        $('#warehouse_name').val(data.address);
        $('#warehouse_name').valid();
    }
    closeLargeModal();
}

function confirmSendPurchaseOrder () {
    showConfirmModal('editPurchaseOrder(true)', '', trans('message.confirmSendPurchaseOrder'))
}

function confirmEditPurchaseOrder () {
    showConfirmModal('editPurchaseOrder()', '', trans('message.confirmEditPurchaseOrder'));
}

function editPurchaseOrder (isSendOrder = false) {
    showLoadingSpinner();
    setTimeout(() => {
        if ($('#purchase_order_edit').valid()) {
            if (dataProduct.length <= 0) {
                hideLoadingSpinner();
                notification('error', trans('message.pleaseSelectAtLeast1item', { item: trans('translation.menu.product') }));
                return;
            }
            var data = {
                supplier_id: $('#supplier_id').val(),
                scheduled_date: $('#scheduled_date').val(),
                warehouse_id: $('#warehouse_id').val(),
                is_send_order: isSendOrder,
            };
            if (dataProduct.length > 0) {
                data.products = dataProduct.map((item, index) => {
                    return {
                        id: item.id,
                        quantity: $(`#quantity_${item.id}`).val(),
                        unit_cost: $(`#unit_cost_${item.id}`).val()
                    }
                });
            }
            $.ajax({
                processData: false,
                contentType: false,
                url: `/api/v1/purchase-order/${purchaseOrder.id}`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(data),
                success: function (response) {
                    hideLoadingSpinner()
                    window.location.href = !isSendOrder ? "/purchase-order" : `/purchase-order/${response.data.id}`;
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

function getUnitCost (data) {
    data = data.filter((item) => {
        return item.id == dataSupplier[0].id;
    });
    if (data.length > 0) {
        return data[0]?.pivot?.unit_cost;
    }
    return 0;
}

function getTotalAmount () {
    let totalAmount = 0;
    dataProduct.forEach((item) => {
        totalAmount += item.pivot.quantity * getUnitCost(item.suppliers);
    });
    $("#totalAmount").text(`${numberFormat(totalAmount, 2)}$`);
}

