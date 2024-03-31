$(document).ready(function () {
    $("#sizeSelect").change(function () {
        // Lấy giá trị đã chọn từ thẻ select
        var selectedSizeId = $(this).val();

        var selectedPrice = prices.find(function (price) {
            return price.size_id == selectedSizeId;
        });

        if (selectedPrice) {
            $("#priceSpan").text(selectedPrice.unit_price + ' VND');
        } else {
            console.log("Giá cho kích thước đã chọn không tồn tại.");
        }
    });
    $("#sizeSelect").trigger("change");
});
