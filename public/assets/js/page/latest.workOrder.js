let workOrderList;

$(function (e) {
    workOrderList = $("#latest-work-table").DataTable({
        responsive: false,
        searching: false,
        bLengthChange: false,
        info: false,
        ordering: false,
        data: [],
        paging: false,
        language: datatableLanguage(),
        columns: [
            { data: "idBonTravail" },
            { data: "nomConseille" },
            { data: "nomClient" },
            { data: "dateEntree" },
            { 
                data: (data) => {
                    return numberFormat(data.totalDu, 2);
                } 
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
        ]
    });
});

function setWorkOrderData(startDate, endDate) {
    if (startDate && endDate) {
        $.ajax({
            type: "GET",
            url: `/api/dashboard/latestWork`,
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function (respone) {
                workOrderList.clear();
                workOrderList.rows.add(respone.data).draw();
            },
            error: function () {
                notification('error', trans('message.there_was_an_error_trying_to_get_list_please_try_again_later'));
            },
        });
    }
}