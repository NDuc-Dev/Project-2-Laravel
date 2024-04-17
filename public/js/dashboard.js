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
                            "mdi-arrow-down-left"
                        );
                        $("#daily-income-val").text(
                            formatCurrency(response.current_day_revenue) + "VND"
                        );
                        $("#daily-income-growth-rate").text(
                            "-" + response.growth_rate_formatted + "%"
                        );
                        $(".icon-box-daily-income").addClass(
                            "icon-box-danger"
                        );
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

    DailyIncome();
});
