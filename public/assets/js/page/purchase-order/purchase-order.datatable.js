let purchaseOrderTable;
$(function (e) {
    const purchaseOrderStatus = [
        { id: 'cancel', text: trans('translation.purchaseOrder.cancel') },
        { id: 'draft', text: trans('translation.purchaseOrder.draft') },
        { id: 'pending', text: trans('translation.purchaseOrder.pending') },
        { id: 'pending_shelve', text: trans('translation.purchaseOrder.pendingShelve') },
        { id: 'done', text: trans('translation.purchaseOrder.done') },
    ]
    purchaseOrderTable = $("#purchase-order-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[0, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: "/api/v1/purchase-order",
            type: "GET",
            error: function () {
                notification('error', trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                ));
            },
        },
        columns: [
            {
                data: function (data, type, row) {
                    return `<a href="/purchase-order/${data.id}">${data.code}</a>`;
                },
                name: "code",
            },
            {
                data: "created_at",
                name: "created_at"
            },
            {
                data: function (data, type, row) {
                    const date = new Date(data.scheduled_date);
                    return moment(date).format('YYYY-MM-DD');
                },
                name: "scheduled_date",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.supplier_name, 20);
                },
                name: "supplier_name",
            },
            {
                data: function (data, type, row) {
                    return `${numberFormat(data.total_amount, 2)}$`;

                },
                name: "total_amount",
                className: 'text-end',
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.warehouse_name, 20);
                },
                name: "warehouse_name",
            },
            {
                data: function (data, type, row) {
                    return `<span class="rounded-5 purchase-order-status btn width-status ${data.status}">${getTrans(data.status, purchaseOrderStatus)
                        }</span> `
                },
                name: "status",
            },
            {
                data: function (data, type, row) {
                    let html = `<a class= "btn btn-primary fs-14 text-white" href = "/purchase-order/${data.id}" title = "${trans('translation.detail')}"><i class="fe fe-eye"></i></a>`;
                    if (data.status == 'draft') {
                        html = `${html} <a class= "btn btn-primary fs-14 text-white edit-icn" href = "/purchase-order/${data.id}/edit" title = "Edit" > <i class="fe fe-edit"></i></a>
                    <button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deletePurchaseOrder" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`
                    }
                    return html;
                },
                width: "10%",
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'text-center',
                targets: -2
            }
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
                'select': {
                    'purchaseOrderStatus': {
                        'dataType': 'fix',
                        'data': purchaseOrderStatus,
                        'placeholder': trans('translation.purchaseOrder.selectStatus')
                    }
                }
            })
        }
    });

    $("#purchase-order-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            purchaseOrderTable.search("").draw();
        }
    });
});

function confirmRemove (id) {
    $("#deleteBtn").attr("onclick", `deletePurchaseOrder(${id})`);
}

function deletePurchaseOrder (id) {
    showLoadingSpinner()
    $.ajax({
        type: "DELETE",
        url: `/api/v1/purchase-order/${id}`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            purchaseOrderTable.draw(false);
            $("#deletePurchaseOrder").modal("hide");
            hideLoadingSpinner()
            notification("success", trans("message.deleteSuccess"));
        },
        error: function (jqXHR) {
            purchaseOrderTable.draw(false);
            $("#deletePurchaseOrder").modal("hide");
            hideLoadingSpinner()
            if (jqXHR.responseJSON?.message) {
                return notification('error', jqXHR.responseJSON?.message);
            }
            notification('error', trans('message.modal_not_found'));
        },
    });
}

function getTrans (key, mapData) {
    const val = mapData.find(e => e.id == key);
    return val ? val.text : key;
}
