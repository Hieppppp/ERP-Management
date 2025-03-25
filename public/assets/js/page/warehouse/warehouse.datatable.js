let warehouseTable;
$(function (e) {
    warehouseTable = $("#warehouse-datatable").DataTable({
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
            url: "/api/v1/warehouse",
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
                data: "code",
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.address);
                },
                name: "address",
            },
            {
                data: "shelves_count",
                name: "shelves_count",
            },
            {
                data: "contact",
                name: "contact",
            },
            {
                data: "updated_at",
                name: "updated_at",
            },
            {
                data: function (data, type, row) {
                    return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle rounded-pill"
                            data-bs-toggle="dropdown" aria-expanded="false"> ${trans(
                                "translation.action"
                            )} </button>
                            <ul class="dropdown-menu" style="">
                                <li><a class="dropdown-item" href="/warehouse/${
                                    data.id
                                }" title="${trans(
                        "translation.detail"
                    )}">${trans("translation.detail")}</a></li>
                                <li><button class="dropdown-item" onclick="showEditModal(${
                                    data.id
                                })" title="${trans(
                        "translation.edit"
                    )}">${trans("translation.edit")}</button></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteWarehouse" title="${trans(
                                    "translation.delete"
                                )}" onclick="confirmRemove(${data.id})">${trans(
                        "translation.delete"
                    )}</button></li>
                            </ul>
                    </div>`;
                },
                width: "10%",
                className: "text-center",
            },
        ],
        columnDefs: [
            { targets: "no-sort", sortable: false, orderable: false },
            {
                className: "max-width-address",
                targets: 3,
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
            });
        },
    });

    $("#warehouse-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            warehouseTable.search("").draw();
        }
    });
});

function confirmRemove(id) {
    $("#deleteBtn").attr("onclick", `deleteWarehouse(${id})`);
}

function deleteWarehouse(id) {
    showLoadingSpinner();
    $.ajax({
        type: "DELETE",
        url: `/api/v1/warehouse/${id}`,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
            warehouseTable.draw(false);
            $("#deleteWarehouse").modal("hide");
            notification("success", trans("message.deleteSuccess"));
            hideLoadingSpinner();
        },
        error: function () {
            warehouseTable.draw(false);
            $("#deleteWarehouse").modal("hide");
            notification("error", trans("message.modal_not_found"));
            hideLoadingSpinner();
        },
    });
}

function showCreateModal() {
    showLargeModal(
        trans("translation.formTitle.warehouseCreate"),
        `/warehouse/create`
    );
}

function warehouseCreate() {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#warehouse_create").valid()) {
            $("#contact").val(telInput.getNumber());
            var formData = $("#warehouse_create").serialize();
            $.ajax({
                url: "/api/v1/warehouse",
                method: "POST",
                data: formData,
                success: function (response) {
                    hideLoadingSpinner();
                    warehouseTable.draw(false);
                    notification("success", trans("message.success"));
                    closeLargeModal();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner();
                    notification("error", trans("message.error"));
                },
            });
        } else {
            hideLoadingSpinner();
        }
    }, 100);
}

function showEditModal(id) {
    showLargeModal(
        trans("translation.formTitle.warehouseEdit"),
        `/warehouse/${id}/edit`
    );
}

function warehouseEdit() {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#warehouse_update").valid()) {
            const warehouseIdEdit = $("#warehouseId").val();
            var formData = $("#warehouse_update").serializeArray();
            formData.push({ name: "contact", value: telInputEdit.getNumber() });
            var finalData = $.param(formData);
            $.ajax({
                url: `/api/v1/warehouse/${warehouseIdEdit}`,
                method: "PUT",
                data: finalData,
                success: function (response) {
                    hideLoadingSpinner();
                    warehouseTable.draw(false);
                    notification("success", trans("message.success"));
                    closeLargeModal();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner();
                    warehouseTable.draw(false);
                    if (jqXHR?.responseJSON) {
                        if (Array.isArray(jqXHR.responseJSON?.message)) {
                            return notification(
                                "error",
                                jqXHR.responseJSON?.message[0]
                            );
                        }
                    }
                    notification("error", trans("message.modal_not_found"));
                },
            });
        } else {
            hideLoadingSpinner();
        }
    }, 100);
}
