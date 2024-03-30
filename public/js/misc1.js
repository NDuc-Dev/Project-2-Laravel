$(function () {
    var current = location.pathname
        .split("/")
        .slice(-1)[0]
        .replace(/^\/|\/$/g, ""); // Lấy đường dẫn hiện tại của trang web
    $(".navbar a").each(function () {
        addActiveClass($(this));
    });

    function addActiveClass(element) {
        if (current === "") {
            //for root url
            if (element.attr("href") === "{{ route('home') }}") {
                // Kiểm tra href có phải là đường dẫn của trang chủ không
                element.parents(".nav-item").addClass("active"); // Thêm class active cho phần tử <li> chứa phần tử <a> đó
            }
        } else {
            //for other url
            if (element.attr("href").indexOf(current) !== -1) {
                // Kiểm tra href có chứa đường dẫn hiện tại không
                element.parents(".nav-item").addClass("active"); // Thêm class active cho phần tử <li> chứa phần tử <a> đó
            }
        }
    }
});
