$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    function formatCurrency(data) {
        var formatter = new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        });
        var formattedValue = formatter.format(data);
        formattedValue = formattedValue.replace("₫", "");
        return formattedValue;
    }

    function DailyIncome() {
        $.ajax({
            url: "daily-income-calc",
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if (response.success) {
                    if (response.growth == true) {
                        $("#daily-income-icon-down-up").addClass(
                            "mdi-arrow-top-right"
                        );
                        $("#daily-income-val").text(
                            formatCurrency(response.current_day_revenue) + "VND"
                        );
                        $("#daily-income-growth-rate").text(
                            "+" + response.growth_rate_formatted + "%"
                        );
                        $(".icon-box-daily-income").addClass(
                            "icon-box-success"
                        );
                        $("#daily-income-growth-rate").addClass("text-success");
                    } else {
                        $("#daily-income-icon-down-up").addClass(
                            "mdi-arrow-bottom-left"
                        );
                        $("#daily-income-val").text(
                            formatCurrency(response.current_day_revenue) + "VND"
                        );
                        $("#daily-income-growth-rate").text(
                            response.growth_rate_formatted + "%"
                        );
                        $(".icon-box-daily-income").addClass("icon-box-danger");
                        $("#daily-income-growth-rate").addClass("text-danger");
                    }
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = "An unexpected error occurred";
                if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else {
                    errorMessage = error;
                    hideSpinner();
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage,
                });
            },
        });
    }

    function DailyOrder() {
        $.ajax({
            url: "daily-order-calc",
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if (response.success) {
                    if (response.growth == true) {
                        $("#daily-order-icon-down-up").addClass(
                            "mdi-arrow-top-right"
                        );
                        $("#daily-order-val").text(response.currentOrderInday);
                        $("#daily-order-growth-rate").text(
                            "+" + response.growth_rate_formatted + "%"
                        );
                        $(".icon-box-daily-order").addClass("icon-box-success");
                        $("#daily-order-growth-rate").addClass("text-success");
                    } else {
                        $("#daily-order-icon-down-up").addClass(
                            "mdi-arrow-bottom-left"
                        );
                        $("#daily-order-val").text(response.currentOrderInday);
                        $("#daily-order-growth-rate").text(
                            response.growth_rate_formatted + "%"
                        );
                        $(".icon-box-daily-order").addClass("icon-box-danger");
                        $("#daily-order-growth-rate").addClass("text-danger");
                    }
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = "An unexpected error occurred";
                if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else {
                    errorMessage = error;
                    hideSpinner();
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage,
                });
            },
        });
    }

    $.ajax({
        url: "transacion-history",
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            var Total =
                parseInt(response.direct_revenue) +
                parseInt(response.online_revenue);
            $("#direct-revenue-val").text(
                formatCurrency(response.direct_revenue) + " VND"
            );
            $("#online-revenue-val").text(
                formatCurrency(response.online_revenue) + " VND"
            );
            $("#total-revenue-val").text(formatCurrency(Total) + " VND");
            $("#direct-revenue-val-1").text(
                formatCurrency(response.direct_revenue) + " VND"
            );
            $("#online-revenue-val-1").text(
                formatCurrency(response.online_revenue) + " VND"
            );
            $("#total-revenue-val-1").text(formatCurrency(Total) + " VND");
            var doughnutPieData = {
                datasets: [
                    {
                        data: [
                            response.direct_revenue,
                            response.online_revenue,
                        ],
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.5)",
                            "rgba(54, 162, 235, 0.5)",
                            "rgba(255, 206, 86, 0.5)",
                            "rgba(75, 192, 192, 0.5)",
                            "rgba(153, 102, 255, 0.5)",
                            "rgba(255, 159, 64, 0.5)",
                        ],
                        borderColor: [
                            "rgba(255,99,132,1)",
                            "rgba(54, 162, 235, 1)",
                            "rgba(255, 206, 86, 1)",
                            "rgba(75, 192, 192, 1)",
                            "rgba(153, 102, 255, 1)",
                            "rgba(255, 159, 64, 1)",
                        ],
                    },
                ],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: ["Online", "Direct"],
            };

            var doughnutPieOptions = {
                responsive: true,
                animation: {
                    animateScale: true,
                    animateRotate: true,
                },
            };

            if ($("#transaction-history").length) {
                var doughnutChartCanvas = $("#transaction-history")
                    .get(0)
                    .getContext("2d");
                var doughnutChart = new Chart(doughnutChartCanvas, {
                    type: "doughnut",
                    data: doughnutPieData,
                    options: doughnutPieOptions,
                });
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = "An unexpected error occurred";
            if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else {
                errorMessage = error;
                hideSpinner();
            }
            Swal.fire({
                icon: "error",
                title: "Error",
                text: errorMessage,
            });
        },
    });

    var data = {
        labels: [
            "8:00",
            "9:00",
            "10:00",
            "11:00",
            "12:00",
            "13:00",
            "14:00",
            "15:00",
            "16:00",
            "17:00",
            "18:00",
            "19:00",
            "20:00",
            "21:00",
        ],
        datasets: [
            {
                label: "# of Votes",
                data: [10, 19, 3, 5, 2, 3, 5, 8, 12, 20, 11, 6,10,14],
                backgroundColor: [
                    "rgba(255, 99, 132, 0.2)",
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(255, 206, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(153, 102, 255, 0.2)",
                    "rgba(255, 159, 64, 0.2)",
                ],
                borderColor: [
                    "rgba(255,99,132,1)",
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 206, 86, 1)",
                    "rgba(75, 192, 192, 1)",
                    "rgba(153, 102, 255, 1)",
                    "rgba(255, 159, 64, 1)",
                ],
                borderWidth: 1,
                fill: false,
            },
        ],
    };

    var options = {
        scales: {
            yAxes: [
                {
                    ticks: {
                        beginAtZero: true,
                    },
                    gridLines: {
                        color: "rgba(204, 204, 204,0.1)",
                    },
                },
            ],
            xAxes: [
                {
                    gridLines: {
                        color: "rgba(204, 204, 204,0.1)",
                    },
                },
            ],
        },
        legend: {
            display: false,
        },
        elements: {
            point: {
                radius: 0,
            },
        },
    };
    if ($("#lineChart").length) {
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas, {
            type: "line",
            data: data,
            options: options,
        });
    }

    DailyIncome();
    DailyOrder();
});
