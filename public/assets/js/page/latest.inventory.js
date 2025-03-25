let latestInvList;

$(function (e) {
    latestInvList = $("#latest-qty-table").DataTable({
        responsive: false,
        searching: false,
        bLengthChange: false,
        info: false,
        ordering: false,
        data: [],
        paging: false,
        language: datatableLanguage(),
        columns: [
            { data: "noProd" },
            { data: "descrAbreFranc" },
            { data: "created_at" },
            { data: "mouvQte" }
        ]
    });
});

function setLatestInvData(startDate, endDate) {
    if (startDate && endDate) {
        $.ajax({
            type: "GET",
            url: `/api/dashboard/latestInventory`,
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function (respone) {
                latestInvList.clear();
                latestInvList.rows.add(respone.data).draw();
            },
            error: function () {
                notification('error', trans('message.there_was_an_error_trying_to_get_list_please_try_again_later'));
            },
        });
    }
}