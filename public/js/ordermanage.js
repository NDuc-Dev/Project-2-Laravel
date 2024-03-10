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
                        console.log(productSize);
                        var dropdownbtn = ``;
                        if (productSize.length == 3) {
                            dropdownbtn = ` 
                            <div class="form-group">
                                <label for="size-select"> Size </label>
                                <select id="size-select" class="dropdown btn btn-info form-control">
                                    <option value="1">S</option>
                                    <option value="2">M</option>
                                    <option value="3">L</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quantity-input"> Quantity </label>
                                <input type="number" id="quantity-input" class="form-control" placeholder="Nhập số lượng">
                            </div>`;
                        } else {
                            dropdownbtn = ` 
                        <div class="form-group">
                            <label for="size-select"> Size </label>
                            <select id="size-select" class="dropdown btn btn-danger form-control disabled">
                                <option value="4" >Piece/Pack</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity-input"> Quantity </label>
                            <input type="number" class="form-control" id="quantity-input" placeholder="Input quantity (default:1)">
                        </div>`;
                        }
                        swal.fire({
                            title: datum.product_name,
                            html: dropdownbtn,
                            showCancelButton: true,
                            confirmButtonText: "Add to Order",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var sizeId = $("#size-select").val();
                                var quantity = $("#quantity-input").val();
                                console.log(sizeId);
                                if (quantity == "") {
                                    quantity = 1;
                                }
                                console.log(quantity);
                                for (var i = 0; i < productSize.length; i++) {
                                    if (productSize[i].size_id == sizeId) {
                                        unit_price = productSize[i].unit_price;
                                    }
                                }
                                var rowData = {
                                    product_name: datum.product_name,
                                    size: sizeId,
                                    unit_price: unit_price,
                                    quantity: quantity,
                                    amount: unit_price * quantity,
                                };

                                if (dataOrders.length == 0) {
                                    addDataToTable(rowData);
                                } else {
                                    var duplicateProduct = findDuplicateProduct(
                                        dataOrders,
                                        rowData
                                    );
                                    if (duplicateProduct) {
                                        duplicateProduct.quantity =
                                            parseInt(
                                                duplicateProduct.quantity
                                            ) + parseInt(rowData.quantity);
                                        duplicateProduct.amount =
                                            duplicateProduct.unit_price *
                                            duplicateProduct.quantity;

                                        var table =
                                            $("#productOrder").DataTable();
                                        table.clear();
                                        table.rows.add(dataOrders).draw();
                                        var total = 0;
                                        for (
                                            var i = 0;
                                            i < dataOrders.length;
                                            i++
                                        ) {
                                            total += dataOrders[i].amount;
                                        }
                                        var totalElement = $("#total");
                                        totalElement.text(
                                            "Total : " + total + " VND"
                                        );
                                    } else {
                                        addDataToTable(rowData);
                                    }
                                }
                            }
                        });
                    } else {
                    }
                },
            });
        });

    var dataOrders = [];

    function findDuplicateProduct(dataOrders, rowData) {
        for (var i = 0; i < dataOrders.length; i++) {
            if (
                dataOrders[i].product_name === rowData.product_name &&
                dataOrders[i].size === rowData.size
            ) {
                return dataOrders[i];
            }
        }
        return null;
    }

    function addDataToTable(rowData) {
        dataOrders.push(rowData);
        if (dataOrders.length > 0) {
            var orderCodeElement = $("#order-code");
            if (orderCodeElement.text() === "") {
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    url: `generate-unique-order-id`,
                    method: "GET",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        orderCodeElement.text("Order Code: " + response);
                    },
                });
            }
            var orderTableElement = $("#order-table");
            if (orderTableElement.text() === "") {
                $.ajax({
                    url: `generate-table-id`,
                    method: "GET",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        orderTableElement.text("Order Table : " + response);
                    },
                });
            }
            var orderDateElement = $("#order-date");
            if (orderDateElement.text() === "") {
                var currentTimestamp = $.now();

                var currentDate = new Date(currentTimestamp);
                var dd = currentDate.getDate();
                var MM = currentDate.getMonth() + 1;
                var yyyy = currentDate.getFullYear().toString();
                var hh = currentDate.getHours();
                var mm = currentDate.getMinutes();

                dd = dd < 10 ? "0" + dd : dd;
                mm = mm < 10 ? "0" + mm : mm;

                var formattedTime =
                    hh + ":" + mm + " " + dd + "/" + MM + "/" + yyyy;
                orderDateElement.text("Order Date : " + formattedTime);
            }
            $("#order-details").removeClass("d-none");
        }
        if (dataOrders.length == 1) {
            var table = $("#productOrder").DataTable({
                data: dataOrders,
                scrollY: "200px",
                scrollX: true,
                info: false,
                ordering: false,
                paging: false,
                lengthChange: false,
                autoWidth: true,
                responsive: true,
                searching: false,
                columns: [
                    { data: "product_name", title: "Product Name" },
                    {
                        data: "size",
                        title: "Size",
                        render: function (data, type, row) {
                            if (data == 1) {
                                return "S";
                            } else if (data == 2) {
                                return "M";
                            } else if (data == 3) {
                                return "L";
                            } else if (data == 4) {
                                return "Pack/Piece";
                            }
                        },
                    },
                    { data: "unit_price", title: "Unit Price" },
                    { data: "quantity", title: "Quantity" },
                    { data: "amount", title: "Amount" },
                ],
            });
        } else if (dataOrders.length > 1) {
            var table = $("#productOrder").DataTable();
            table.clear();
            table.rows.add(dataOrders).draw();
        }
        var total = 0;
        for (var i = 0; i < dataOrders.length; i++) {
            total += dataOrders[i].amount;
        }
        var totalElement = $("#total");
        totalElement.text("");
        totalElement.text("Total : " + total + " VND");
    }

    // sử dụng datatable để in ra bảng product
    initializeDataTableProduct(products);
    function initializeDataTableProduct(data) {
        $("#productTable").DataTable({
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
