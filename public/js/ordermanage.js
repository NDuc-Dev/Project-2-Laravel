$(document).ready(function () {
    //sử dụng typeahead để tạo thanh search cho sản phẩm
    var suggestions = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("product_name"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: products,
    });
    console.log(suggestions);
    suggestions.initialize();

    $(".typeahead")
        .typeahead(
            {
                minLength: 1,
                limit: 10,
                hint: true,
                highlight: true,
            },
            {
                name: "suggestions",
                displayKey: "product_name",
                templates: {
                    empty: [
                        '<div class="list-group search-results-dropdown"><div class="list-group-item">Không có kết quả phù hợp.</div></div>',
                    ],
                    header: [
                        '<div class="ms-2 mt-0 list-group search-results-dropdown"> Product',
                    ],
                    suggestion: function (data) {
                        var status =
                            data.status_in_stock == 1
                                ? '<label class="badge badge-success">On Sale</label>'
                                : '<label class="badge badge-danger">Sold Out</label>';
                        var details = `<div class="product-dropdown d-flex align-items-center" style="border-top:1px solid #2c2e33;" data-product-id="${data.product_id}">
                    <img src="${data.product_images}" width="50" height="50" style="border-radius: 5px;">
                    <div class="product-inf ms-2 me-auto">
                        <div id="product_name" name="product_name" >${data.product_name}</div>
                        <p >Category: ${data.product_category}</p>
                    </div>
                    <div class="product-status ms-2 me-3">
                    ${status}
                    </div>
                    
                </div>`;
                        return details;
                    },
                },
                source: suggestions.ttAdapter(),
            }
        )
        .on("typeahead:selected", function ($e, datum) {
            console.log("Sản phẩm được chọn:", datum);

            var productId = datum.product_id;
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            // Gửi yêu cầu Ajax để lấy dữ liệu ProductSize
            $.ajax({
                url: `get-data-product-size`,
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                data: {
                    product_id: productId,
                },
                success: function (response) {
                    // Xử lý dữ liệu ProductSize được trả về
                    if (response.success) {
                        var productSize = response.productSize;
                        // console.log(productSize);
                        addDataToTable(productSize, datum);
                    } else {
                    }
                },
            });
        });

    function addDataToTable(productSize, datum) {
        var data = [];
        var count = productSize.length;
        console.log(count);
        // Duyệt qua các phần tử ProductSize
        // for (var i = 0; i < productSize.length; i++) {
        //     var product = productSize[i];

        //     // Tạo đối tượng dữ liệu cho mỗi phần tử ProductSize
            var rowData = {
                product_name: datum.product_name, // Lấy tên sản phẩm từ datum
                size: product.size,
                unit_price: product.price,
                quantity: 1, // Khởi tạo số lượng mặc định là 1
                amount: product.price, // Tính toán giá trị ban đầu
            };

        //     // Thêm đối tượng dữ liệu vào mảng
        //     data.push(rowData);
        // }

        // var table = $("#productOrder").DataTable();

        // // Thêm dữ liệu vào bảng
        // table.rows.add(data).draw();

        // // Cập nhật giao diện bảng
        // table.draw();

        // // Hiển thị dropdown size nếu có nhiều hơn 1 phần tử
        // if (productSize.length > 1) {
        //     // Hiển thị dropdown size
        //     $("#productSizeDropdown").show();
        // } else {
        //     // Ẩn dropdown size
        //     $("#productSizeDropdown").hide();

        //     // Hiển thị giá trị size trực tiếp
        //     $("#productSize").text(productSize[0].size);
        // }

        // // Cập nhật giá trị unit_price và amount
        // updateUnitPriceAndAmount();
    }

    $("#productOrder").DataTable({
        data: null,
        scrollY: "200px",
        info: false,
        ordering: false,
        paging: false,
        lengthChange: false,
        autoWidth: true,
        responsive: true,
        searching: false,
        columns: [
            { title: "Product Name" },
            { title: "Size" },
            { title: "Unit Price" },
            { title: "Quantity" },
            { title: "Amount" },
        ],
    });

    //sử dụng datatable để in ra bảng product
    initializeDataTableProduct(products);
    function initializeDataTableProduct(data) {
        $("#productTable").DataTable({
            data: data,
            scrollY: "200px",
            ordering: false,
            paging: true,
            lengthChange: false,
            autoWidth: true,
            responsive: true,
            searching: false,
            columns: [
                {
                    data: "product_images",
                    title: "Image",
                    render: function (data, type, row) {
                        if (!data || data.length === 0) {
                            return "";
                        }
                        const firstImagePath = data;
                        if (firstImagePath) {
                            return `<img src="${firstImagePath}" alt="${row.product_name}" style="width: 50px; height: 50px; object-fit: cover; border-radius:5px" onerror="this.src='https://via.placeholder.com/50x50';">`;
                        } else {
                            return "";
                        }
                    },
                },
                {
                    data: "product_name",
                    title: "Name",
                },
                {
                    data: "status_in_stock",
                    title: "Status In Stock",
                    render: function (data, type, row) {
                        if (data == 1) {
                            return '<div class="badge badge-success">In Stock</div>';
                        } else if (data == 0) {
                            return '<div class="badge badge-danger">Out Of Stock</div>';
                        } else {
                            return "";
                        }
                    },
                },
            ],
        });
    }
});
