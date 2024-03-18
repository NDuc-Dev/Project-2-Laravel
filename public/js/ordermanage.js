$(document).ready(function () {
    var orderDateElement = $("#order-date");
    var orderTableElement = $("#order-table");
    var orderCodeElement = $("#order-code");
    var dataOrders = [];
    var totalNoformat = 0;
    var orderCode = "";
    var orderTable = "";
    var orderDate = "";
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var formattedTime = "";

    //sử dụng bloodhound tạo index cho search
    var suggestions = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("product_name"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: products,
    });
    suggestions.initialize();

    //tạo thanh search sử dụng typeahead
    $(".typeahead").typeahead(
        {
            minLength: 1,
            limit: 10,
            // hint: true,
            highlight: true,
        },
        {
            name: "suggestions",
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
            source: suggestions.ttAdapter(),
        }
    );

    var dataresult = [];

    //kiểm tra kết quả liên tục khi nhập query
    $(".typeahead").on("input", function () {
        // Lấy giá trị hiện tại của thanh search
        var searchValue = $(this).val();
        var tableSearch = $("#result").DataTable();
        //nếu xuất hiện thay đổi giá trị trên thanh search mà giá trị query của phần tử đầu tiên không thay đổi, tức là không thấy sản phẩm cho lần query này
        if (
            (dataresult.length != 0 &&
                dataresult[0]._query != searchValue &&
                searchValue != "") ||
            (dataresult.length == 0 && searchValue != "")
        ) {
            tableSearch.clear();
            tableSearch.draw();
            dataresult = [];
            $("#result").find(".dataTables_empty").text("There are no matches");
        }
        if (searchValue == "") {
            tableSearch.destroy();
            dataresult = [];
            initializeDataTableProduct(products);
        }
    });

    //kiểm tra dữ liệu từ các lần query trước khi push dữ liệu vào mảng, xem có sự trùng lặp hay không
    function changeSearchResults(data) {
        if (dataresult.length == 0) {
            dataresult.push(data);
        } else {
            if (data._query === dataresult[0]._query) {
                dataresult.push(data);
            } else {
                dataresult.length = 0;
                dataresult.push(data);
            }
        }
        var table = $("#result").DataTable();
        table.clear();
        table.rows.add(dataresult).draw();
    }

    //hàm khởi tạo bảng product khi trang được tải
    initializeDataTableProduct(products);

    function initializeDataTableProduct(data) {
        $("#result").DataTable({
            data: data,
            scrollY: "200px",
            ordering: false,
            paging: true,
            lengthChange: false,
            // autoWidth: true,
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
                    title: "Product",
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

    $("#result").on("click", "tr", function () {
        // Xử lý sự kiện click vào hàng của DataTable
        var rowData = $("#result").DataTable().row(this).data();
        var productId = rowData.product_id;
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
                            <input type="number" id="quantity-input" class="form-control" placeholder="Input quantity (default:1)">
                        </div>`;
                    } else {
                        dropdownbtn = `
                    <div class="form-group d-none">
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
                        title: rowData.product_name,
                        html: dropdownbtn,
                        focusConfirm: false,
                        preConfirm: () => {
                            const quantity =
                                document.getElementById("quantity-input").value;
                            if (quantity <= 0 && quantity != "") {
                                swal.showValidationMessage(
                                    "Quantity must large than 0 !"
                                );
                            }
                            return { quantity: quantity };
                        },
                        showCancelButton: true,
                        confirmButtonText: "Add to Order",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var sizeId = $("#size-select").val();
                            var subSize = "";
                            if (sizeId == 1) {
                                subSize = "S";
                            } else if (sizeId == 2) {
                                subSize = "M";
                            } else if (sizeId == 3) {
                                subSize = "L";
                            } else {
                                subSize = "";
                            }
                            var quantity = $("#quantity-input").val();
                            if (quantity == "") {
                                quantity = 1;
                            }
                            for (var i = 0; i < productSize.length; i++) {
                                if (productSize[i].size_id == sizeId) {
                                    unit_price = productSize[i].unit_price;
                                    product_size_id =
                                        productSize[i].product_size_id;
                                }
                            }
                            var rowDataProduct = {
                                product_name:
                                    rowData.product_name + " " + subSize,
                                size: sizeId,
                                product_size_id: product_size_id,
                                unit_price: unit_price,
                                quantity: quantity,
                                amount: unit_price * quantity,
                            };
                            if (dataOrders.length == 0) {
                                addDataToTable(rowDataProduct);
                            } else {
                                var duplicateProduct = findDuplicateProduct(
                                    dataOrders,
                                    rowDataProduct
                                );
                                if (duplicateProduct) {
                                    duplicateProduct.quantity =
                                        parseInt(duplicateProduct.quantity) +
                                        parseInt(rowDataProduct.quantity);

                                    duplicateProduct.amount =
                                        duplicateProduct.unit_price *
                                        duplicateProduct.quantity;
                                    var table = $("#productOrder").DataTable();
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
                                    formatTotal(checkTotal(dataOrders));
                                } else {
                                    addDataToTable(rowDataProduct);
                                }
                            }
                        }
                    });
                } else {
                }
            },
        });
    });

    $("#productOrderBefore").DataTable({
        data: [],
        scrollY: false,
        scrollX: false,
        info: false,
        ordering: false,
        paging: false,
        lengthChange: false,
        autoWidth: true,
        responsive: true,
        searching: false,
        columns: [
            { data: "product_name", title: "Product" },
            // { data: "unit_price", title: "Unit Price" },
            { data: "quantity", title: "Quantity" },
            { data: "amount", title: "Amount" },
        ],
    });

    function addDataToTable(rowData) {
        dataOrders.push(rowData);
        if (dataOrders.length == 1) {
            generateOrderInfo();
            var table = $("#productOrder").DataTable({
                data: dataOrders,
                scrollY: false,
                scrollX: true,
                info: false,
                ordering: false,
                paging: false,
                lengthChange: false,
                autoWidth: true,
                responsive: true,
                searching: false,
                columns: [
                    { data: "product_name", title: "Product" },
                    // { data: "unit_price", title: "Unit Price" },
                    { data: "quantity", title: "Quantity" },
                    {
                        data: "amount",
                        title: "Amount",
                        render: function (data, type, row) {
                            return formatCurrency(data); // Gọi hàm định dạng tiền tệ và trả về giá trị đã định dạng
                        },
                    },
                ],
            });
        } else if (dataOrders.length > 1) {
            var table = $("#productOrder").DataTable();
            table.clear();
            table.rows.add(dataOrders).draw();
        }

        formatTotal(checkTotal(dataOrders));
    }

    function findDuplicateProduct(dataOrders, rowDataProducts) {
        for (var i = 0; i < dataOrders.length; i++) {
            if (
                dataOrders[i].product_name === rowDataProducts.product_name &&
                dataOrders[i].size === rowDataProducts.size
            ) {
                return dataOrders[i];
            }
        }
        return null;
    }

    function generateOrderInfo() {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        if (orderTableElement.text() === "") {
            $.ajax({
                url: `generate-table-id`,
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    orderTableElement.text(response);
                    orderTable = response;
                },
            });
        }
        if (orderDateElement.text() === "") {
            var currentTimestamp = $.now();

            var currentDate = new Date(currentTimestamp);
            var dd = currentDate.getDate();
            var MM = currentDate.getMonth() + 1;
            var yyyy = currentDate.getFullYear().toString();
            var hh = currentDate.getHours();
            var ii = currentDate.getMinutes();
            var ss = currentDate.getSeconds();

            dd = dd < 10 ? "0" + dd : dd;
            ii = ii < 10 ? "0" + ii : ii;
            MM = MM < 10 ? "0" + MM : MM;
            ss = ss < 10 ? "0" + ss : ss;

            formattedTime = hh + ":" + ii + " " + dd + "/" + MM + "/" + yyyy;
            orderDateElement.text(formattedTime);
            orderDate =
                yyyy + "-" + MM + "-" + dd + " " + hh + ":" + ii + ":" + ss;
        }
        $("#order-details-after").removeClass("d-none");
        $("#order-details-before").addClass("d-none");
    }

    function formatCurrency(data) {
        var formatter = new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        });
        var formattedValue = formatter.format(data);
        formattedValue = formattedValue.replace("₫", "");
        return formattedValue;
    }

    var element = $("#productOrderBefore").find(".dataTables_empty");
    if (element) {
        element.text("Welcome to NDC Coffee");
    }

    $("#productOrder").on("click", "tr", function () {
        var rowIndex = $("#productOrder").DataTable().row(this).index();
        var rowDataProduct = $("#productOrder")
            .DataTable()
            .row(rowIndex)
            .data();
        Swal.fire({
            title: rowDataProduct.product_name,
            html: `
            <div class="form-group">
                <label for="quantity-input-change"> Input new quantity </label>
                <input type="number" class="form-control" id="quantity-input-change" placeholder="New quantity">
            </div>
            <div class="swal-buttons">
            <button id="btn-delete" class="btn btn-danger py-3 mx-1 my-1">Delete Product</button>
            <button id="btn-confirm" class="btn btn-primary py-3 mx-1 my-1">Change</button>
                <button id="btn-cancel" class="btn btn-dark py-3 mx-1 my-1">Cancel</button>
            </div>
            `,
            showCancelButton: false,
            showConfirmButton: false,
            preConfirm: () => {
                const quantity = document.getElementById(
                    "quantity-input-change"
                ).value;
                if (quantity <= 0 && quantity != "") {
                    swal.showValidationMessage("Quantity must large than 0 !");
                }
                return { quantity: quantity };
            },
        }).then((result) => {
            if (result.isConfirmed) {
                var quantityChange = $("#quantity-input-change").val();
                rowDataProduct.quantity = quantityChange;
                rowDataProduct.amount =
                    quantityChange * rowDataProduct.unit_price;
                $("#productOrder")
                    .DataTable()
                    .row(rowIndex)
                    .data(rowDataProduct)
                    .draw();

                formatTotal(checkTotal(dataOrders));
            }
        });

        $(".swal-buttons").on("click", "#btn-cancel", function () {
            Swal.close();
        });

        $(".swal-buttons").on("click", "#btn-delete", function () {
            Swal.fire({
                title: "Delete ?",
                text:
                    "Are you sure you want to delete " +
                    rowDataProduct.product_name +
                    " from order ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#productOrder")
                        .DataTable()
                        .row(rowIndex)
                        .remove()
                        .draw();

                    var index = dataOrders.findIndex(
                        (item) =>
                            item.product_name === rowDataProduct.product_name
                    );
                    if (index !== -1) {
                        dataOrders.splice(index, 1);
                    }
                    Swal.fire("Success", "Delete product success", "success");
                    if (dataOrders.length == 0) {
                        removeDataProductTable();
                    }
                    formatTotal(checkTotal(dataOrders));
                }
            });
        });
        $(".swal-buttons").on("click", "#btn-confirm", function () {
            Swal.clickConfirm();
        });
    });

    function formatTotal(total) {
        totalFormat = formatCurrency(total);
        var totalElement = $("#total");
        totalElement.text(totalFormat);
    }

    function checkTotal(dOrder) {
        var total = 0;
        for (var i = 0; i < dOrder.length; i++) {
            total += dOrder[i].amount;
        }
        return total;
    }

    $("#order-details-after").on("click", "#cancel-order-btn", function () {
        Swal.fire({
            title: "Cancel ?",
            icon: "question",
            text: "Do you want to cancel this order ?",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                removeDataProductTable();
            }
        });
    });

    $("#order-details-after").on("click", "#submit-order-btn", function () {
        var Total = checkTotal(dataOrders);
        Swal.fire({
            title: "Payment",
            input: "text",
            inputLabel: "Enter the amount received from the customer.",
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return "This field is required !";
                }
                if (!validateInputAmount(value)) {
                    return "The input field contains only numbers and is divisible by 1000 ";
                }
                if (Total > value) {
                    return "The receipt must be larger than the total amount to be paid";
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                const receivedAmount = result.value;
                console.log(dataOrders);
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    url: "create-order",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    data: {
                        products: dataOrders,
                        orderDate,
                        orderTable,
                        formattedTime,
                    },
                    success: function (response) {
                        if (response.success) {
                            receipt_path = response.receipt_path;
                            Swal.fire({
                                title: "Success",
                                icon: "success",
                                text: "Create Order Successfully !",
                                showConfirmButton: false,
                                timer: 1000,
                            }).then(() => {
                                Swal.fire({
                                    title: "Return",
                                    icon: "info",
                                    text:
                                        "Return the customer " +
                                        (receivedAmount - Total) +
                                        " VND",
                                    showConfirmButton: true,
                                }).then((result1) => {
                                    if (result1.isConfirmed) {
                                        fetch(receipt_path)
                                            .then((response1) =>
                                                response1.blob()
                                            )
                                            .then((blob) => {
                                                const reader = new FileReader();
                                                reader.onload = function () {
                                                    const base64data =
                                                        reader.result;
                                                    Swal.fire({
                                                        title: "RECEIPT",
                                                        html:
                                                            '<div style="height: 70vh;"><embed width="100%" height="100%" src="' +
                                                            base64data +
                                                            '" type="application/pdf"></div>',
                                                        showCloseButton: false,
                                                        showConfirmButton: true,
                                                        allowOutsideClick: true,
                                                    });
                                                };
                                                reader.readAsDataURL(blob);
                                            })
                                            .catch((error) => {
                                                console.error(
                                                    "Error loading PDF:",
                                                    error
                                                );
                                            });
                                    }
                                });
                            });
                        }
                    },
                });
            }
        });
    });

    function validateInputAmount(input) {
        if (isNaN(input)) {
            return false;
        }

        if (input % 1000 !== 0) {
            return false;
        }

        return true;
    }

    function removeDataProductTable() {
        dataOrders = [];
        $("#productOrder").DataTable().destroy();
        $("#order-details-after").addClass("d-none");
        $("#order-details-before").removeClass("d-none");
        orderDateElement.text("");
        orderTableElement.text("");
        orderCodeElement.text("");
    }
});
