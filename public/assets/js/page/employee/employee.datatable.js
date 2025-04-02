let employeeTable;
$(function (e) {
    employeeTable = $("#employee-datatable").DataTable({
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
            url: "/api/v1/employee",
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
                    return truncateText(data.department_name, 20);
                },
                name: "department_name"
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.position_name, 20);
                },
                name: "position_name"
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.email, 50);
                },
                name: "email"
            },
            {
                data: "phone",
                name: "phone",
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
                                <li><a class="dropdown-item" href="/employee/${
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
                                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteEmployee" title="${trans(
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
                'api': this.api(),
                'tableId': settings.sTableId,
                'select': {
                    'department-select-filter' : {
                        'dataType': 'ajax',
                        'url': '/api/v1/department/search',
                        'placeholder': trans('translation.placeHolder.select')
                    },
                    'position-select-filter' : {
                        'dataType': 'ajax',
                        'url': '/api/v1/position/search',
                        'placeholder': trans('translation.placeHolder.select')
                    }
                }
            });
        },
    });

    $("#employee-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            employeeTable.search("").draw();
        }
    });
});

function confirmRemove(id) {
    $("#deleteBtn").attr("onclick", `deleteEmployee(${id})`);
}

function deleteEmployee(id) {
    showLoadingSpinner();
    $.ajax({
        type: "DELETE",
        url: `/api/v1/employee/${id}`,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
            employeeTable.draw(false);
            $("#deleteEmployee").modal("hide");
            notification("success", trans("message.deleteSuccess"));
            hideLoadingSpinner();
        },
        error: function () {
            employeeTable.draw(false);
            $("#deleteEmployee").modal("hide");
            notification("error", trans("message.modal_not_found"));
            hideLoadingSpinner();
        },
    });
}

function showCreateModal() {
    showLargeModal(
        trans("translation.formTitle.employeeCreate"),
        `/employee/create`
    );
}

function employeeCreate() {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#employee_create").valid()) {
            $("#phone").val(telInput.getNumber());
            var formData = $("#employee_create").serialize();
            $.ajax({
                url: "/api/v1/employee",
                method: "POST",
                data: formData,
                success: function (response) {
                    hideLoadingSpinner();
                    employeeTable.draw(false);
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
        trans("translation.formTitle.employeeEdit"),
        `/employee/${id}/edit`
    );
}

function employeeEdit() {
    showLoadingSpinner();
    setTimeout(() => {
        if ($("#employee_update").valid()) {
            const employeeIdEdit = $("#employeeId").val();
            var formData = $("#employee_update").serializeArray();
            formData.push({ name: "contact", value: telInputEdit.getNumber() });
            var finalData = $.param(formData);
            $.ajax({
                url: `/api/v1/employee/${employeeIdEdit}`,
                method: "PUT",
                data: finalData,
                success: function (response) {
                    hideLoadingSpinner();
                    employeeTable.draw(false);
                    notification("success", trans("message.success"));
                    closeLargeModal();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner();
                    employeeTable.draw(false);
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
