$(document).ready(function () {
    var inputQuantityfood = $("#quantityfood");
    var inputQuantitydrink = $("#quantitydrink");
    var btnMinusDrink = $(".drink-minus");
    var btnPlusDrink = $(".drink-plus");
    var btnMinusFood = $(".food-minus");
    var btnPlusFood = $(".food-plus");
    var addToCartButtonsfood = $(".add-to-cart-food");
    var addToCartButtonsdrink = $(".add-to-cart-drink");
    console.log(addToCartButtonsdrink);

    addToCartButtonsfood.each(function () {
        $(this).click(function () {
            var productId = $(this).data("id");
            $.get("/get-product-food-" + productId, function (response) {
                var product = response.product;
                $("#product-id-food").val(product.product_id);
                $("#href-img-food").attr("href", product.product_images);
                $("#img-food").attr("src", product.product_images);
                $("#product-name-food").text(product.product_name);
                $("#priceSpanfood").text(product.unit_price + " VND");
                $("#product-descriptions-food").text(product.descriptions);
            }).fail(function (xhr, status, error) {
                console.error("Error:", error);
            });
        });
    });

    addToCartButtonsdrink.each(function () {
        $(this).click(function () {
            var productId = $(this).data("id");
            console.log(productId);
            $.get("/get-product-drink-" + productId, function (response) {
                var product = response.product;
                var prices = response.prices;
                $("#product-id-drink").val(product.product_id);
                $("#href-img-drink").attr("href", product.product_images);
                $("#img-drink").attr("src", product.product_images);
                $("#product-name-drink").text(product.product_name);
                $("#product-descriptions-drink").text(product.descriptions);
                $("#sizeSelect")
                    .change(function () {
                        var selectedSizeId = $(this).val();
                        var selectedPrice = prices.find(function (price) {
                            return price.size_id == selectedSizeId;
                        });
                        if (selectedPrice) {
                            $("#priceSpandrink").text(
                                selectedPrice.unit_price + " VND"
                            );
                        } else {
                            console.log(
                                "Giá cho kích thước đã chọn không tồn tại."
                            );
                        }
                    })
                    .change();
            }).fail(function (xhr, status, error) {
                console.error("Error:", error);
            });
        });
    });

    btnMinusFood.click(function () {
        var currentValue = parseInt(inputQuantityfood.val());
        if (!isNaN(currentValue) && currentValue > 1) {
            inputQuantityfood.val(currentValue - 1);
        }
    });

    btnPlusFood.click(function () {
        var currentValue = parseInt(inputQuantityfood.val());
        if (
            !isNaN(currentValue) &&
            currentValue < parseInt(inputQuantityfood.attr("max"))
        ) {
            inputQuantityfood.val(currentValue + 1);
        }
    });

    btnMinusDrink.click(function () {
        var currentValue = parseInt(inputQuantitydrink.val());
        if (!isNaN(currentValue) && currentValue > 1) {
            inputQuantitydrink.val(currentValue - 1);
        }
    });

    btnPlusDrink.click(function () {
        var currentValue = parseInt(inputQuantitydrink.val());
        if (
            !isNaN(currentValue) &&
            currentValue < parseInt(inputQuantitydrink.attr("max"))
        ) {
            inputQuantitydrink.val(currentValue + 1);
        }
    });

    inputQuantitydrink.on("input", function () {
        var value = parseInt($(this).val());
        if (isNaN(value) || value < parseInt(inputQuantitydrink.attr("min"))) {
            $(this).val(inputQuantitydrink.attr("min"));
        }
        if (value > parseInt(inputQuantitydrink.attr("max"))) {
            $(this).val(inputQuantitydrink.attr("max"));
        }
    });

    inputQuantityfood.on("input", function () {
        var value = parseInt($(this).val());
        if (isNaN(value) || value < parseInt(inputQuantityfood.attr("min"))) {
            $(this).val(inputQuantityfood.attr("min"));
        }
        if (value > parseInt(inputQuantityfood.attr("max"))) {
            $(this).val(inputQuantityfood.attr("max"));
        }
    });

    $("#submit-add-to-cart-drink").click(function (event) {
        event.preventDefault();
        var csrfToken = $("#csrf-token").val();
        var productId = $("#product-id-drink").val();
        var sizeId = $("#sizeSelect").val();
        var quantity = $("#quantitydrink").val();
        $.ajax({
            type: "POST",
            url: "/add-to-cart",
            data: {
                product_id: productId,
                size_id: sizeId,
                quantity: quantity,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if(response.success == true){
                    swal.fire({
                        icon: "success",
                        text: "Add product to cart success",
                        title: "Success",
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    swal.fire({
                        icon: "error",
                        text: "Add product to cart failed",
                        title: "Success",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
                var closeButton = $("#close-drink");
                if (closeButton.length) {
                    closeButton.click();
                }
                var cartItemCount = response.totalItems;
                $("#cartItemCount").html(
                    "<small>" + cartItemCount + "</small>"
                );
            },
            error: function (xhr, status, error) {
                console.error("Error:", xhr.responseText);
            },
        });
    });

    $("#submit-add-to-cart-food").click(function (event) {
        event.preventDefault();
        var csrfToken = $("#csrf-token").val();
        var productId = $("#product-id-food").val();
        var sizeId = 4;
        var quantity = $("#quantityfood").val();
        $.ajax({
            type: "POST",
            url: "/add-to-cart",
            data: {
                product_id: productId,
                size_id: sizeId,
                quantity: quantity,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if(response.success == true){
                    swal.fire({
                        icon: "success",
                        text: "Add product to cart success",
                        title: "Success",
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    swal.fire({
                        icon: "error",
                        text: "Add product to cart failed",
                        title: "Success",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
                var closeButton = $("#close-food");
                if (closeButton.length) {
                    closeButton.click();
                }
                var cartItemCount = response.totalItems;
                $("#cartItemCount").html(
                    "<small>" + cartItemCount + "</small>"
                );
            },
            error: function (xhr, status, error) {
                console.error("Error:", xhr.responseText);
            },
        });
    });
});
