document.addEventListener("DOMContentLoaded", function () {
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
});
