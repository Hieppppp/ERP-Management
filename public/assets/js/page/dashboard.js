function getStatisticValue(startDate, endDate) {
    if (startDate && endDate) {
        $.ajax({
            type: "GET",
            url: `/api/dashboard/statistic`,
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function (respone) {
                const data = respone.data;
                $('#totalRevenue').text(`$${data.totalRevenue}`);
                $('#totalBill').text(data.totalBill);
                $('#avgSale').text(`$${data.avgSale}`);
            },
            error: function () {
                notification('error', trans('message.there_was_an_error_trying_to_get_list_please_try_again_later'));
            },
        });
    }
}
