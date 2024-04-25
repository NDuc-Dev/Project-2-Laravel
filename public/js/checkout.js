$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const responseCode = urlParams.get("vnp_ResponseCode");
    const orderId = urlParams.get("vnp_TxnRef");
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    if (responseCode) {
        if (responseCode !== null && responseCode === "00") {
            Swal.fire({
                title: "SUCCESS",
                text: "Payment Successfully",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
            });
            $.ajax({
                url: "clear-cart",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    $.ajax({
                        url: "send-mail",
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        data: {
                            order_id: orderId,
                        },
                        success: function (response) {
                            window.location.href = "/cart";
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                        },
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                },
            });
        } else {
            Swal.fire({
                title: "ERROR",
                text: "Payment failed",
                icon: "error",
                showConfirmButton: false,
                timer: 2000,
            });
            $.ajax({
                url: "change-status-payment",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                data: {
                    order_id: orderId,
                },
                success: function (response) {},
                error: function (xhr, status, error) {
                    console.error(error);
                },
            });
        }
    }
    calculateAndDisplayTotal();

    function formatCurrency(amount) {
        return amount.toLocaleString("en-US");
    }
    
    function calculateAndDisplayTotal() {
        var totalPrice = 0;
        $(".cart-detail tbody tr").each(function () {
            var totalCell = $(this).find(".total");
            var total = parseFloat(
                totalCell.text().replace(" VND", "").replace(",", "")
            );
            totalPrice += total;
        });
        var totalSpan = $("#total");
        var subTotalSpan = $("#sub-total");
        var totalAmt = $("#totalAmt");

        totalAmt.val(totalPrice);
        totalSpan.text(formatCurrency(totalPrice) + " VND");
        subTotalSpan.text(formatCurrency(totalPrice) + " VND");
    }

    $(".billing-form").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
            },
            address: {
                required: true,
            },
            phone: {
                required: true,
                regexPhone: true,
            },
            email: {
                regexEmail: true,
            },
        },
        messages: {
            name: {
                required: "Please enter your name.",
                minlength: "Your name must be at least 2 characters long.",
            },
            address: {
                required: "Please enter your delivery address.",
            },
            phone: {
                required: "Please enter your phone number.",
                regexPhone: "Invalid Phone number",
            },
            email: {
                regexEmail: "Please enter a valid email address.",
            },
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
            element.next().addClass("error text-danger py-2");
        },
        success: function (label, element) {
            $(element).next().remove();
        },
    });

    //email
    $.validator.addMethod("regexEmail", function (value, element) {
        return /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(value);
    });

    //phone
    $.validator.addMethod("regexPhone", function (value, element) {
        return /(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b/.test(value);
    });

    $("#form-validate").on("submit", function (e) {
        if (!$(this).valid()) {
            e.preventDefault();
        }
    });
});
