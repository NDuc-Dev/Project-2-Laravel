document.addEventListener("DOMContentLoaded", function () {
    var inputQuantityfood = document.getElementById("quantityfood");
    var inputQuantitydrink = document.getElementById("quantitydrink");
    var btnMinusDrink = document.querySelector(".drink-minus");
    var btnPlusDrink = document.querySelector(".drink-plus");
    var btnMinusFood = document.querySelector(".food-minus");
    var btnPlusFood = document.querySelector(".food-plus");
    var addToCartButtonsfood = document.querySelectorAll("#add-to-cart-food");
    var addToCartButtonsdrink = document.querySelectorAll("#add-to-cart-drink");
    addToCartButtonsfood.forEach(function (button) {
        button.addEventListener("click", function () {
            var productId = this.getAttribute("data-id");
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "/get-product-food-" + productId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            var product = response.product;
                            document
                                .querySelector("#product-id-food")
                                .setAttribute("value", product.product_id);
                            document
                                .querySelector("#href-img-food")
                                .setAttribute("href", product.product_images);
                            document
                                .querySelector("#img-food")
                                .setAttribute("src", product.product_images);
                            document.getElementById(
                                "product-name-food"
                            ).innerText = product.product_name;
                            document.getElementById("priceSpanfood").innerText =
                                product.unit_price + " VND";
                            document.getElementById(
                                "product-descriptions-food"
                            ).innerText = product.descriptions;
                        } catch (error) {
                            console.error(
                                "Error parsing JSON response:",
                                error
                            );
                        }
                    } else {
                        console.error(
                            "Request failed with status:",
                            xhr.status
                        );
                    }
                }
            };
            xhr.send();
        });
    });
    
    addToCartButtonsdrink.forEach(function (button) {
        var prices;
        button.addEventListener("click", function () {
            var productId = this.getAttribute("data-id");
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "/get-product-drink-" + productId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            var product = response.product;
                            prices = response.prices;
                            document
                                .querySelector("#product-id-drink")
                                .setAttribute("value", product.product_id);
                            document
                                .querySelector("#href-img-drink")
                                .setAttribute("href", product.product_images);
                            document
                                .querySelector("#img-drink")
                                .setAttribute("src", product.product_images);
                            document.getElementById(
                                "product-name-drink"
                            ).innerText = product.product_name;
                            document.getElementById(
                                "product-descriptions-drink"
                            ).innerText = product.descriptions;

                            var sizeSelect =
                                document.getElementById("sizeSelect");
                            var priceSpan =
                                document.getElementById("priceSpandrink");

                            sizeSelect.addEventListener("change", function () {
                                var selectedSizeId = this.value;
                                var selectedPrice = prices.find(function (
                                    price
                                ) {
                                    return price.size_id == selectedSizeId;
                                });
                                if (selectedPrice) {
                                    priceSpan.textContent =
                                        selectedPrice.unit_price + " VND";
                                } else {
                                    console.log(
                                        "Giá cho kích thước đã chọn không tồn tại."
                                    );
                                }
                            });
                            sizeSelect.dispatchEvent(new Event("change"));
                        } catch (error) {
                            console.error(
                                "Error parsing JSON response:",
                                error
                            );
                        }
                    } else {
                        console.error(
                            "Request failed with status:",
                            xhr.status
                        );
                    }
                }
            };
            xhr.send();

            // sizeSelect.dispatchEvent(new Event("change"));
        });
    });

    btnMinusFood.addEventListener("click", function () {
        var currentValue = parseInt(inputQuantityfood.value);
        if (!isNaN(currentValue) && currentValue > 1) {
            inputQuantityfood.value = currentValue - 1;
        }
    });

    btnPlusFood.addEventListener("click", function () {
        var currentValue = parseInt(inputQuantityfood.value);
        if (
            !isNaN(currentValue) &&
            currentValue < parseInt(inputQuantityfood.max)
        ) {
            inputQuantityfood.value = currentValue + 1;
        }
    });

    btnMinusDrink.addEventListener("click", function () {
        var currentValue = parseInt(inputQuantity.value);
        if (!isNaN(currentValue) && currentValue > 1) {
            inputQuantity.value = currentValue - 1;
        }
    });

    btnPlusDrink.addEventListener("click", function () {
        var currentValue = parseInt(inputQuantitydrink.value);
        if (
            !isNaN(currentValue) &&
            currentValue < parseInt(inputQuantitydrink.max)
        ) {
            inputQuantitydrink.value = currentValue + 1;
        }
    });

    inputQuantitydrink.addEventListener("input", function () {
        var value = inputQuantitydrink.value;
        if (
            isNaN(value) ||
            parseInt(value) < parseInt(inputQuantitydrink.min)
        ) {
            inputQuantitydrink.value = inputQuantitydrink.min;
        }
        if (parseInt(value) > parseInt(inputQuantitydrink.max)) {
            inputQuantitydrink.value = inputQuantitydrink.max;
        }
    });

    inputQuantityfood.addEventListener("input", function () {
        var value = inputQuantityfood.value;
        if (isNaN(value) || parseInt(value) < parseInt(inputQuantityfood.min)) {
            inputQuantityfood.value = inputQuantityfood.min;
        }
        if (parseInt(value) > parseInt(inputQuantityfood.max)) {
            inputQuantityfood.value = inputQuantityfood.max;
        }
    });

    document.getElementById('submit-add-to-cart-drink').addEventListener('click', function (event) {
        event.preventDefault();
        var csrfToken = document.getElementById('csrf-token').value;
        var productId = document.getElementById('product-id-drink').value;
        var sizeId = document.getElementById('sizeSelect').value;
        var quantity = document.getElementById('quantitydrink').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', "/add-to-cart", true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                var closeButton = document.getElementById('close-drink');
                if (closeButton) {
                    closeButton.click();
                }
            } else {
                console.error('Error:', xhr.responseText);
            }
        };
        xhr.send(JSON.stringify({
            product_id: productId,
            size_id: sizeId,
            quantity: quantity
        }));
    });

    document.getElementById('submit-add-to-cart-food').addEventListener('click', function (event) {
        event.preventDefault();
        var csrfToken = document.getElementById('csrf-token').value;
        var productId = document.getElementById('product-id-food').value;
        var sizeId = 4;
        var quantity = document.getElementById('quantityfood').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', "/add-to-cart", true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                var closeButton = document.getElementById('close-food');
                if (closeButton) {
                    closeButton.click();
                }
            } else {
                console.error('Error:', xhr.responseText);
            }
        };
        xhr.send(JSON.stringify({
            product_id: productId,
            size_id: sizeId,
            quantity: quantity
        }));
    });

});
