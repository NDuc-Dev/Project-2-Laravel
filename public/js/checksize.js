document.addEventListener('DOMContentLoaded', function () {
    var sizeSelect = document.getElementById('sizeSelect');
    var priceSpan = document.getElementById('priceSpan');

    sizeSelect.addEventListener('change', function () {
        // Lấy giá trị đã chọn từ thẻ select
        var selectedSizeId = this.value;

        // Lọc mảng prices để lấy giá trị tương ứng với selectedSizeId
        var selectedPrice = prices.find(function (price) {
            return price.size_id == selectedSizeId;
        });

        // Nếu tìm thấy giá trị, hiển thị nó trong thẻ span có id là priceSpan
        if (selectedPrice) {
            priceSpan.textContent = selectedPrice.unit_price + ' VND';
        } else {
            // Xử lý trường hợp không tìm thấy giá trị
            console.log('Giá cho kích thước đã chọn không tồn tại.');
        }
    });

    // Trigger sự kiện change khi trang được tải
    sizeSelect.dispatchEvent(new Event('change'));
});