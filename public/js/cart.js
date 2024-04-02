document.addEventListener("DOMContentLoaded", function () {
    var quantityInputs = document.querySelectorAll(".quantity");
    quantityInputs.forEach(function (input) {
        input.addEventListener("keypress", function (event) {
            var charCode = event.which ? event.which : event.keyCode;
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }
        });
        input.addEventListener("input", function () {
            updateTotal(input);
        });
        input.addEventListener("blur", function () {
            if (input.value.trim() === "" || parseInt(input.value) === 0) {
                input.value = "1";
                updateTotal(input);
            }
        });
    });

    function updateTotal(input) {
        var quantity = parseInt(input.value);
        if (input.value === "") {
            quantity = 0;
        }
        if (quantity > 10) {
            quantity = 10;
            input.value = 10;
        }
        var unitPrice = parseInt(input.dataset.productPrice);
        if (!isNaN(unitPrice) && !isNaN(quantity)) {
            var total = quantity * unitPrice;
            var parentRow = input.closest("tr");
            var totalCell = parentRow.querySelector(".total");
            totalCell.textContent = formatCurrency(total) + " VND";
            calculateAndDisplayTotal();
        }
    }

    function formatCurrency(amount) {
        return amount.toLocaleString("en-US");
    }
    var removeButtons = document.querySelectorAll(".remove-product");

    removeButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            var productIdAndSizeId = button.getAttribute(
                "data-productIdAndSizeId"
            );
            console.log("click" + productIdAndSizeId);
            removeProduct(productIdAndSizeId);
        });
    });
    function removeProduct(productIdAndSizeId) {
        var productRow = document.getElementById(
            "product_row_" + productIdAndSizeId
        );
        if (productRow) {
            var xhr = new XMLHttpRequest();
            xhr.open("DELETE", "remove-form-cart-" + productIdAndSizeId, true);
            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            );
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log("Product removed successfully from cart");
                        productRow.remove();
                        calculateAndDisplayTotal();
                    } else {
                        console.error("Error:", response.message);
                    }
                } else {
                    console.error("Error:", xhr.statusText);
                }
            };
            xhr.send();
        }
    }

    calculateAndDisplayTotal();

    function calculateAndDisplayTotal() {
        var totalPrice = 0;

        // Lặp qua từng hàng trong bảng
        var rows = document.querySelectorAll(".cart-list tbody tr");
        rows.forEach(function (row) {
            var totalCell = row.querySelector(".total");
            var total = parseFloat(
                totalCell.textContent.replace(" VND", "").replace(",", "")
            );
            totalPrice += total;
        });

        // Hiển thị tổng giá trị
        var totalSpan = document.getElementById("total");
        var subTotalSpan = document.getElementById("sub-total");

        totalSpan.textContent = formatCurrency(totalPrice) + " VND";
        subTotalSpan.textContent = formatCurrency(totalPrice) + " VND";
    }
});
