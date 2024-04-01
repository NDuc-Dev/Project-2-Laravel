$(function () {
    var current = location.pathname
        .split("/")
        .slice(-1)[0]
        .replace(/^\/|\/$/g, "");
    $(".navbar a").each(function () {
        addActiveClass($(this));
    });

    function addActiveClass(element) {
        var href = element.attr("href");
        if (typeof href !== 'undefined') {
            if (current === "") {
                //for root url
                if (href === "{{ route('home') }}") {
                    element.parents(".nav-item").addClass("active"); 
                }
            } else {
                //for other url
                if (href.indexOf(current) !== -1) {
                    element.parents(".nav-item").addClass("active"); 
                }
            }
        }
    }
});
