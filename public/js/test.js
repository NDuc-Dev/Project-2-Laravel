$(document).ready(function () {
    var suggestions_test = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("product_name"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: products,
    });
    console.log(suggestions_test);
    suggestions_test.initialize();

    $(".typeahead.test").typeahead(
        {
            minLength: 1,
            limit: 10,
            hint: true,
            highlight: true,
        },
        {
            name: "suggestions_test",
            displayKey: "product_name",
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown d-none"><div class="list-group-item">Không có kết quả phù hợp.</div></div>',
                ],
                header: [
                    '<div class="ms-2 mt-0 list-group search-results-dropdown d-none"> Product',
                ],
                suggestion: function (data) {
                    changeSearchResults(data);
                    // console.log(data);
                    var status =
                        data.status_in_stock == 1
                            ? '<label class="badge badge-success">On Sale</label>'
                            : '<label class="badge badge-danger">Sold Out</label>';
                    var details = `<div class="product-dropdown d-flex align-items-center d-none" style="border-top:1px solid #2c2e33;" data-product-id="${data.product_id}">
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
            source: suggestions_test.ttAdapter(),
        }
    );

    var dataresult = [];

    $(".typeahead.test").on("input", function () {
        // Lấy giá trị hiện tại của thanh search
        var searchValue = $(this).val();
        var tableTest = $("#result-test").DataTable();
        if (dataresult[0]._query != searchValue && searchValue != "") {
            console.log("none");
            tableTest.clear();
            tableTest.draw();
            dataresult = [];
            $(".dataTables_empty").text("There are no matches");
        }
        if (searchValue == "") {
            tableTest.destroy();
            dataresult = [];
            initializeDataTableProduct(products);
        }
    });

    function changeSearchResults(results) {
        console.log(results._query);
        if (dataresult.length == 0) {
            dataresult.push(results);
        } else {
            if (results._query === dataresult[0]._query) {
                dataresult.push(results);
            } else {
                dataresult.length = 0;
                dataresult.push(results);
            }
        }
        var table = $("#result-test").DataTable();
        table.clear();
        table.rows.add(dataresult).draw();
    }

    $("#result-test").DataTable({
        data: products,
        scrollY: "200px",
        info: false,
        ordering: false,
        paging: false,
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
            { data: "product_name", title: "Product Name" },
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

    function initializeDataTableProduct(data) {
        $("#result-test").DataTable({
            data: data,
            scrollY: "200px",
            ordering: false,
            paging: true,
            lengthChange: false,
            autoWidth: true,
            limit: 10,
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
                    title: "Product Name",
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
