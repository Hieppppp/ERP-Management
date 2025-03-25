let topSellerList;

$(function (e) {
    topSellerList = $("#best-seller-table").DataTable({
        responsive: false,
        searching: false,
        bLengthChange: false,
        info: false,
        ordering: false,
        data: [],
        paging: false,
        language: datatableLanguage(),
        columns: [
            { data: "noEmploye" },
            { data: "nomEmploye" },
            { data: "dep" },
            { data: "totalBill" },
            { 
                data: (data) => {
                    return numberFormat(data.totalRevenue, 2);
                } 
            },
        ]
    });
});

function setTopSellerData(startDate, endDate) {
    if (startDate && endDate) {
        $.ajax({
            type: "GET",
            url: `/api/dashboard/topSeller`,
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function (respone) {
                topSellerList.clear();
                topSellerList.rows.add(respone.data).draw();
            },
            error: function () {
                notification('error', trans('message.there_was_an_error_trying_to_get_list_please_try_again_later'));
            },
        });
    }
}