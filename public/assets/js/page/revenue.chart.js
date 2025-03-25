function setChart(revenueData) {
    setTimeout(() => {
        var options = {
            series: [
                {
                    name: trans('translation.dashboard.totalSale'),
                    data: revenueData
                },
            ],
            chart: {
                id: "revenueChart",
                type: "area",
                height: 345,
                toolbar: {
                    show: false,
                },
            },
            colors: [myVarVal],
            dataLabels: {
                enabled: false,
            },
            markers: {
                size: 0,
                style: "hollow",
            },
            grid: {
                borderColor: "#f7f9fa",
            },
            xaxis: {
                type: "datetime",
                axisBorder: {
                    show: true,
                    color: "rgba(119, 119, 142, 0.05)",
                    offsetX: 0,
                    offsetY: 0,
                },
                axisTicks: {
                    show: true,
                    borderType: "solid",
                    color: "rgba(119, 119, 142, 0.05)",
                    width: 6,
                    offsetX: 0,
                    offsetY: 0,
                },
                labels: {
                    show: true,
                    rotate: -90,
                    style: {
                        fontSize: "11px",
                        fontFamily: "Helvetica, Arial, sans-serif",
                        fontWeight: 400,
                        cssClass: "apexcharts-xaxis-label",
                    },
                },
                tooltip: {
                    enabled: false,
                },
            },
            yaxis: {
                title: {
                    text: "",
                    style: {
                        color: "#adb5be",
                        fontSize: "14px",
                        fontFamily: "poppins, sans-serif",
                        fontWeight: 600,
                        cssClass: "apexcharts-yaxis-label",
                    },
                },
                labels: {
                    formatter: function (y) {
                        return y.toFixed(0) + "";
                    },
                },
            },
            tooltip: {
                x: {
                    format: "dd MMM yyyy",
                },
            },
            stroke: {
                show: true,
                curve: "smooth",
                lineCap: "butt",
                colors: undefined,
                width: 1,
                dashArray: 0,
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.75,
                    opacityTo: 0.5,
                    stops: [0, 200],
                },
            },
            legend: {
                position: "top",
                show: true,
            },
        };
        document.getElementById("revenueChart").innerHTML = "";
        var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
        chart.render();
    }, 300);
}

function setRevenueChart(startDate, endDate) {
    if (startDate && endDate) {
        $.ajax({
            type: "GET",
            url: `/api/dashboard/revenue`,
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function (respone) {
                const dataRevenue = respone.data;
                let chartData = [];
                if (dataRevenue) {
                    let initialData = createDateRangeArray(startDate, endDate);
                    chartData = initialData.map((value, index) => {
                        const matchData = dataRevenue.find(item => item.date === value.date);
                        return [
                            value.date,
                            matchData ? matchData.totalRevenue : value.totalRevenue ?? 0
                        ];
                    })
                }
                setChart(chartData);
            },
            error: function () {
                notification('error', trans('message.there_was_an_error_trying_to_get_list_please_try_again_later'));
            },
        });
    }
}

function createDateRangeArray(startDate, endDate) {
    const start = moment(startDate);
    const end = moment(endDate);
    const days = end.diff(start, "days") + 1;

    return Array.from({ length: days }, (_, index) => ({
        date: start.clone().add(index, "days").format('YYYY-MM-DD'),
        totalRevenue: "0.00",
    }));
}