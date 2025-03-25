let userTable;
$(function (e) {
    const UserRole = [
        { id: "admin", text: trans("translation.user.admin") },
        { id: "user", text: trans("translation.user.user") },
    ];
    userTable = $("#user-datatable").DataTable({
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
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/api/v1/user",
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
                data: "id",
                name: "id",
            },
            {
                data: function (data, type, row) {
                    let image_url = data.profile_picture
                        ? data.profile_picture
                        : "/assets/images/no-image.png";
                    return `<img src="${image_url}" width="50" height="50" class="img-overlay-light-box">`;
                },
                name: "avatar",
                className: "min-w-image text-center",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.username, 20);
                },
                name: "username",
            },
            {
                data: function (data, type, row) {
                    return getTrans(data.role, UserRole);
                },
                name: "role",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.email, 20);
                },
                name: "email",
            },
            {
                data: function (data, type, row) {
                    return dateFormat(data.created_at || "");
                },
                name: "created_at",
            },
            {
                data: function (data, type, row) {
                    let editBtn = "";
                    let deleteBtn = "";
                    let detailBtn = `<a class="btn btn-primary fs-14 text-white edit-icn" href="/user/${
                        data.id
                    }" title="${trans(
                        "translation.detail"
                    )}"><i class="fe fe-eye"></i></a>`;
                    if (
                        (data.role == "admin" && isSuperAdmin == "1") ||
                        data.role == "user"
                    ) {
                        editBtn = `<a class="btn btn-primary fs-14 text-white edit-icn" href="/user/${data.id}/edit" title="Edit"><i class="fe fe-edit"></i></a>`;
                        deleteBtn = `<button class="btn btn-danger fs-14 text-white edit-icn" data-bs-toggle="modal" data-bs-target="#deleteUser" onclick="confirmRemove(${data.id})"><i class="fe fe-trash"></i></button>`;
                    }
                    return `${detailBtn} ${editBtn} ${deleteBtn}`;
                },
                width: "10%",
            },
        ],
        columnDefs: [{ targets: "no-sort", sortable: false, orderable: false }],
        drawCallback: function (settings) {
            var pageInfo = userTable.page.info();
            if (pageInfo.page >= pageInfo.pages && pageInfo.pages > 1) {
                userTable.page(pageInfo.pages - 1).draw(false);
            }
        },
        initComplete: function (settings) {
            setDatatableFilters({
                api: this.api(),
                tableId: settings.sTableId,
                select: {
                    "user-role": {
                        dataType: "fix",
                        data: UserRole,
                        placeholder: trans(
                            "translation.placeHolder.selectRole"
                        ),
                    },
                },
            });
        },
    });

    $("#user-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            userTable.search("").draw();
        }
    });
});

function confirmRemove(id) {
    $("#deleteBtn").attr("onclick", `deleteUser(${id})`);
}

function deleteUser(id) {
    showLoadingSpinner();
    $.ajax({
        type: "DELETE",
        url: `/api/v1/user/${id}`,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function () {
            userTable.draw(false);
            $("#deleteUser").modal("hide");
            notification("success", trans("message.deleteSuccess"));
            $(".loading-overlay").hide();
        },
        error: function () {
            userTable.draw(false);
            $("#deleteUser").modal("hide");
            notification("error", trans("message.modal_not_found"));
            $(".loading-overlay").hide();
        },
    });
}

function getTrans(key, mapData) {
    const val = mapData.find((e) => e.id == key);
    return val ? val.text : key;
}
