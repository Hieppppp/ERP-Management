let inventoryDataTable;
let note;
$(document).ready(function () {
    inventoryDataTable = $("#inventory_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[5, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: `/api/v1/product/${productId}/inventory`,
            type: "GET",
            error: function () {
                notification(
                    "error",
                    trans(
                        "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                    )
                );
            },
        },
        columns: [
            {
                data: function (data, type, row) {
                    return `<a target="_blank" href="/shelve/${data.shelve_id}">${data.shelves_code}</a>`;
                },
                name: "shelves_code",
            },
            {
                data: function (data, type, row) {
                    return `<a target="_blank" href="/warehouse/${data.warehouse_id
                        }">${truncateText(data.warehouse_name, 20)}</a>`;
                },
                name: "warehouse_name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.location, 40);
                },
                name: "location",
            },
            {
                data: function (data, type, row) {
                    return `<a target="_blank" href="/supplier/${data.supplier_id
                        }">${truncateText(data.supplier_name, 20)}</a>`;
                },
                name: "supplier_name",
            },
            {
                data: "quantity",
                name: "quantity",
            },
            {
                data: "last_updated_date",
                name: "last_updated_date",
            },
            {
                data: function (data, type, row) {
                    return `<button class="btn btn-primary fs-14 text-white edit-icn" onclick="openUpdateInventoryModal(${data.id})" title="Edit"><i class="fe fe-edit"></i></button>
                    <a class="btn btn-primary fs-14 text-white edit-icn" href="/product/movement/${data.id}"><i class="fa fa-exchange"></i></a>`;
                },
                width: "10%",
                class: "text-center",
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
            });
        },
    });

    $("#inventory_datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            inventoryDataTable.search("").draw();
        }
    });
});

function openUpdateInventoryModal (productLocationId) {
    showSmallModal(
        trans("translation.inventory.update"),
        `/inventory/${productLocationId}/edit`
    );
}

function confirmEditInventory () {
    if ($("#inventory_edit").valid()) {
        $(`#confirmModal`).modal("show");
        $(`#confirmModal_title`).text(trans("translation.modal.confirm"));
        $(`#confirmModal_body`).html(`<p>${trans("message.confirm")}</p>`);
        $(`#confirmModalSubmitBtn`).attr("onclick", "editInventory()");
    }
}

function editInventory () {
    showLoadingSpinner();
    const invetoryId = $("#inventory_id").val();
    let data = {};
    $("#inventory_edit")
        .find(":input[name]")
        .each(function () {
            var input = $(this);
            data[input.attr("name")] = input.val();
        });
    data.note = note.root.innerHTML != "<p><br></p>" ? note.root.innerHTML : "";
    $.ajax({
        url: `/api/v1/inventory/${invetoryId}`,
        method: "PUT",
        processData: false,
        contentType: false,
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json",
        },
        success: function (response) {
            hideLoadingSpinner();
            inventoryDataTable.draw(false);
            notification("success", trans("message.success"));
            modalStack = [];
            $(`#confirmModal`).modal("hide");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            hideLoadingSpinner();
            inventoryDataTable.draw(false);
            modalStack = [];
            $(`#confirmModal`).modal("hide");
            if (jqXHR?.responseJSON?.message) {
                return notification("error", jqXHR.responseJSON?.message);
            }
            notification("error", trans("message.updateFailed"));
        },
    });
}
