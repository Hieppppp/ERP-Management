let dataProduct = batch?.products ?? '[]';
let totalAmountPurchaserOrder = dataProduct.reduce((sum, item) => {
    return sum + (item.received_quantity * item.unit_cost);
}, 0);
let dataPurchaseOrder = [
    {
        code: batch?.code ?? '',
        id: batch?.id,
        created_at: batch?.created_at,
        scheduled_date: batch?.scheduled_date,
        total_amount: totalAmountPurchaserOrder,
        status: batch?.status
    }
];
$(function (e) {
    const purchaseOrderStatus = [
        { id: 'cancel', text: trans('translation.purchaseOrder.cancel') },
        { id: 'draft', text: trans('translation.purchaseOrder.draft') },
        { id: 'pending', text: trans('translation.purchaseOrder.pending') },
        { id: 'pending_shelve', text: trans('translation.purchaseOrder.pendingShelve') },
        { id: 'done', text: trans('translation.purchaseOrder.done') },
    ]
    shelveDatatable = $("#product_datatable").DataTable({
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
                "createdCell": function(td, cellData, rowData, row, col) {
                    if (rowData.deleted_at) {
                        $(td).addClass('deleted-text-item'); 
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return `<img src="${data.image ? '/storage/' + data.image : '/assets/images/no-image.png'}" width="50" height="50" class="img-overlay-light-box">`
                },
                className: 'min-w-image text-center',
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
                        $(td).addClass('deleted-text-item');  
                    }
                }
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
            },
            {
                data: 'quantity',
            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ],
    });
    purchaseOrderDatatable = $("#purchaser_order_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataPurchaseOrder,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: function (data, type, row) {
                    return `<a href="/purchase-order/${data.id}">${data.code}</a>`
                }
            },
            {
                data: 'created_at',
            },
            {
                data: 'scheduled_date'
            },
            {
                data: function (data, type, row) {
                    return `${numberFormat(data.total_amount, 2)}$`
                },
                className: 'text-end'
            },
            {
                data: function (data, type, row) {
                    return `<span class="rounded-5 purchase-order-status btn width-status ${data.status}">${getTrans(data.status, purchaseOrderStatus)
                        }</span> `
                },
                className: 'text-center'
            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ],
    });
});

function getTrans (key, mapData) {
    const val = mapData.find(e => e.id == key);
    return val ? val.text : key;
}
