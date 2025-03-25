let dataSupplier = [purchaseOrder?.supplier];
let isSelectOneSupplier = true;
let productDatatable;
let dataProduct = products;
let isDataChanged = false;
let includeSupplierInProductList = true; // check if supplierIdForProductList is included in the product list
let supplierIdForProductList = [purchaseOrder?.supplier.id]; // supplierIdForProductList is included in the product list
let dataSupplierTemporary = [];
let dataWarehouse = purchaseOrder?.warehouse;
$(function (e) {
    initDatePicker([
        'scheduled_date'
    ]);
    let columns = [
        {
            data: function (data, type, row) {
                return data.code;
            },
            "createdCell": function (td, cellData, rowData, row, col) {
                if (rowData.deleted_at) {
                    $(td).addClass('deleted-text-item'); 
                }
            }
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
            }
        },
        {
            data: function (data, type, row) {
                return `<a href="/product/${data.id}" target="_blank">${truncateText(data.name, 20)}</a>`;
            },
            "createdCell": function (td, cellData, rowData, row, col) {
                if (rowData.deleted_at) {
                    $(td).addClass('deleted-text-item')
                }
            }
        },
        {
            data: function (data, type, row) {
                if (data.hasOwnProperty('unit_name')) {
                    return truncateText(data.unit_name, 20);
                }
                return truncateText(`${data?.unit?.name, 20}(${data?.unit?.symbol})`);
            },
        },
        {
            data: function (data, type, row) {
                if (data.hasOwnProperty('category_name')) {
                    return truncateText(data.category_name, 20);
                }
                return truncateText(data?.category?.name, 20);
            },
        },
        {
            data: function (data, type, row) {
                return data?.quantity;
            },
            name: 'demand'
        },
        {
            data: function (data, type, row) {
                return data?.received_quantity;
            },
            name: 'receive'
        },
        {
            data: function (data, type, row) {
                return data?.returned_quantity;
            },
            name: 'return'
        },
        {
            data: function (data, type, row) {
                const unitCost = getUnitCost(data.suppliers);
                return `${unitCost}$<input type="number" class="form-control" style="border:0px !important;" name="unit_cost[]" min="0" required id='unit_cost_${data.id}' value='${getUnitCost(data.suppliers)}' hidden>`;
            },
            className: 'text-end',
        },
        {
            data: function (data, type, row) {
                const unitCost = getUnitCost(data.suppliers);
                let quantity = data?.quantity;
                if (['pending_shelve', 'done'].includes(purchaseOrder.status)) {
                    quantity = data?.received_quantity - data?.returned_quantity;
                }
                return `${numberFormat(quantity * unitCost, 2)}$`;
            },
            className: 'text-end',
        }
    ];

    if (['draft', 'pending', 'cancel'].includes(purchaseOrder.status)) {
        columns = columns.filter(item => {
            return !['receive', 'return'].includes(item.name);
        })
    }

    if (purchaseOrder.status == 'pending_shelve') {
        columns = columns.filter(item => {
            return item.name != 'return';
        })
    }
    
    if (['pending_shelve', 'done'].includes(purchaseOrder.status)) {
        columns.push({
            data: function (data, type, row) {
                if (data?.pivot?.received_quantity <= 0) {
                    return '';
                }
                let html = `<div style="position:relative"> <a href="/purchase-order/${purchaseOrder?.id}/product/${data?.id}">
                                <i class="fa fa-align-justify"></i>
                            </a>`;
                if (purchaseOrder?.purchase_product_shelves) {
                    const purchaseProductShelve = purchaseOrder?.purchase_product_shelves.filter((item) => {
                        return item.product_id == data.id;
                    });
                    if (purchaseProductShelve.length <= 0) {
                        html = `${html} <i class="mdi mdi-alert-circle-outline text-danger warning-icon tooltip-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="${trans('message.productNotShelved')}"></i>`;
                    }
                } else {
                    html = `${html} <i class="mdi mdi-alert-circle-outline text-danger warning-icon tooltip-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="${trans('message.productNotShelved')}"></i>`;
                }
                return html + '</div>';
            },
            className: "text-center",
            "createdCell": function (td, cellData, rowData, row, col) {
                if (rowData.deleted_at) {
                    $(td).css({
                        'pointer-events': 'none'
                    });
                }
            }
        });
    }
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
        columns: columns,
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false }
        ],
    });
});

function confirmSendPurchaseOrder () {
    showConfirmModal('sendPurchaseOrder()', '', trans('message.confirmSendPurchaseOrder'))
}

function sendPurchaseOrder () {
    showLoadingSpinner();
    if (purchaseOrder?.status === 'draft') {
        $.ajax({
            processData: false,
            contentType: false,
            url: `/api/v1/purchase-order/${purchaseOrder.id}/send-order`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            success: function (response) {
                hideLoadingSpinner()
                location.reload();
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
    }
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

function cancel () {
    showConfirmModal('cancelPurchaseOrder()', '', trans('message.confirmCancelBackOrder'));
}

function cancelPurchaseOrder () {
    showLoadingSpinner();
    $.ajax({
        processData: false,
        contentType: false,
        url: `/api/v1/purchase-order/${purchaseOrder.id}/cancel`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        success: function (response) {
            hideLoadingSpinner()
            location.reload();
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
}

function confirmFinishPurchaseOrder () {
    showConfirmModal('finishPurchaseOrder()', '', trans('message.confirmFinishPurchaseOrder'));
}

function finishPurchaseOrder () {
    showLoadingSpinner();
    $.ajax({
        processData: false,
        contentType: false,
        url: `/api/v1/purchase-order/${purchaseOrder.id}/finish`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        success: function (response) {
            hideLoadingSpinner()
            window.location.href = "/purchase-order";
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
    closeConfirmModal();
}
