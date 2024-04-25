$(document).ready(function () {
    $(".quantity").each(function () {
        $(this).keypress(function (event) {
            var charCode = event.which ? event.which : event.keyCode;
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }
        });
        $(this).on("input", function () {
            updateTotal($(this));
        });
        $(this).blur(function () {
            var input = $(this);
            if (input.val().trim() === "" || parseInt(input.val()) === 0) {
                var quantity = input.val(1);
                var productIdAndSizeIdInput = input.attr(
                    "data-productIdAndSizeId"
                );
                updateQuantity(productIdAndSizeIdInput, quantity);
                updateTotal(input);
            } else {
                var productIdAndSizeIdInput = input.attr(
                    "data-productIdAndSizeId"
                );
                updateQuantity(productIdAndSizeIdInput, input.val());
            }
        });
    });

    function updateQuantity(productIdAndSizeId, quantity) {
        $.ajax({
            url:
                "change-quantity-product-cart-" +
                productIdAndSizeId +
                "-" +
                quantity,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "Content-Type": "application/json",
            },
            success: function (response) {
                if (response.success) {
                    console.log(response.message);
                } else {
                    console.error("Error:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            },
        });
    }

    function updateTotal(input) {
        var quantity = parseInt(input.val());
        if (input.val() === "") {
            quantity = 1;
        }
        if (quantity > 10) {
            quantity = 10;
            input.val(10);
        }
        var unitPrice = parseInt(input.data("productPrice"));
        if (!isNaN(unitPrice) && !isNaN(quantity)) {
            var total = quantity * unitPrice;
            var parentRow = input.closest("tr");
            var totalCell = parentRow.find(".total");
            totalCell.text(formatCurrency(total) + " VND");
            calculateAndDisplayTotal();
        }
    }

    function formatCurrency(amount) {
        return amount.toLocaleString("en-US");
    }

    $(".remove-product").click(function (event) {
        event.preventDefault();
        var productIdAndSizeId = $(this).attr("data-productIdAndSizeId");
        removeProduct(productIdAndSizeId);
    });

    function removeProduct(productIdAndSizeId) {
        var productRow = $("#product_row_" + productIdAndSizeId);
        if (productRow.length) {
            $.ajax({
                url: "remove-form-cart-" + productIdAndSizeId,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    "Content-Type": "application/json",
                },
                success: function (response) {
                    if (response.success) {
                        productRow.remove();
                        calculateAndDisplayTotal();
                    } else {
                        console.error("Error:", response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }
    }

    calculateAndDisplayTotal();

    function calculateAndDisplayTotal() {
        var totalPrice = 0;
        var rows = $(".cart-list tbody tr");
        if (rows.length != 0) {
            rows.each(function () {
                var totalCell = $(this).find(".total");
                var total = parseFloat(
                    totalCell.text().replace(" VND", "").replace(",", "")
                );
                totalPrice += total;
            });
            var totalSpan = $("#total");
            var subTotalSpan = $("#sub-total");
            if (totalSpan.length && subTotalSpan.length) {
                totalSpan.text(formatCurrency(totalPrice) + " VND");
                subTotalSpan.text(formatCurrency(totalPrice) + " VND");
            }
        } else {
            var totalElement = $(".cart-element");
            totalElement.addClass("d-none");
            var table = $("tbody");
            var newRow = table.append(
                "<tr class='text-center'><td colspan='6'>There are no products in cart</td></tr>"
            );
        }
    }

    $("#checkoutButton").click(function (event) {
        event.preventDefault();

        $.ajax({
            url: "/before-check-out",
            method: "GET",
            success: function (response) {
                if (response.success) {
                    window.location.href = "/checkout";
                } else {
                    swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
            },
            error: function (xhr, status, error) {
            },
        });
    });
});
