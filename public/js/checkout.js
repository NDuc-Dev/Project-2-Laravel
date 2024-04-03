$(document).ready(function () {
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


    $('.billing-form').validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            address: {
                required: true,
            },
            phone: {
                required: true,
                regexPhone:true
            },
            email: {
                regexEmail:true,
            }
        },
        messages: {
            name: {
                required: "Please enter your name.",
                minlength: "Your name must be at least 2 characters long."
            },
            address: {
                required: "Please enter your delivery address."
            },
            phone: {
                required: "Please enter your phone number.",
                regexPhone: "Invalid Phone number"
            },
            email: {
                regexEmail  : "Please enter a valid email address."
            }
        }, errorPlacement: function (error, element) {
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


    $('#place-order-btn').click(function(event) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        if ($('#form-validate').valid()) {
            console.log("true");
            var formData = $('#form-validate').serialize();

            $.ajax({
                url: 'check-total-amount-in-cart',
                type: 'GET',
                success: function(response) {
                    var total = parseInt(response.totalAmt);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        } else {
            // Form không hợp lệ, bạn có thể thực hiện xử lý thông báo lỗi ở đây
            console.log('Form is invalid');
        }
    });
});