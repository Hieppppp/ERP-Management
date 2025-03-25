let lastAttemptList;

$(function (e) {
    lastAttemptList = $("#latest-attempt-table").DataTable({
        responsive: false,
        searching: false,
        bLengthChange: false,
        info: false,
        ordering: false,
        data: [],
        paging: false,
        language: datatableLanguage(),
        columns: [
            { data: "username" },
            { data: "email" },
            { data: "role_name" },
            { data: "last_attempt" }
        ]
    });
});

function setLastAttemptData() {
    $.ajax({
        type: "GET",
        url: `/api/dashboard/lastAttempt`,
        success: function (respone) {
            lastAttemptList.clear();
            lastAttemptList.rows.add(respone.data).draw();
        },
        error: function () {
            notification('error', trans('message.there_was_an_error_trying_to_get_list_please_try_again_later'));
        },
    });
}