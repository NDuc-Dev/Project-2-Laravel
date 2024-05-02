$(document).ready(function () {
    var orderDateElement = $("#order-date");
    var orderTableElement = $("#order-table");
    var orderTypeElement = $("#order-type-err");
    var dataOrders = [];
    var orderTable = "";
    var orderDate = "";

    var orderTableErrElement = $("#order-table-err");
    var orderDateErrElement = $("#order-date-err");
    var orderIdErrElement = $("#order-code-err");
    var dataOrdersErr = [];
    var tableOrderErr;
    var orderTableErr = "";
    var orderIdErr = "";
    var orderDateErr = "";
    var cusInfElement = $("#cus-inf");
    var cusInf = $("#cus-inf-err");

    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var formattedTime = "";

    var resutlTable = $("#result").DataTable({
        ajax: {
            url: "get-data-products-active",
            dataSrc: "products",
        },
        scrollY: "250px",
        ordering: false,
        paging: true,
        lengthChange: false,
        // autoWidth: true,
        limit: 10,
        responsive: true,
        searching: true,
        language: {
            search: "", // Tùy chỉnh văn bản gợi ý trong thanh tìm kiếm
            searchPlaceholder: "Search Product", // Tùy chỉnh placeholder cho thanh tìm kiếm
        },
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
                data: "unit",
                title: "",
                render: function (data, type, row) {
                    if (data == "Piece/Pack") {
                        return '<i class="fa-solid fa-plus btn btn-outline-success p-2 px-4" id="food-product"></i>';
                    } else if (data == "Cup") {
                        return (
                            '<button class="btn btn-outline-success fa-solid fa-plus p-2 px-4" id="drink-product" type="button" id="dropdownMenuIconButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            "</button>" +
                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuIconButton1" style="min-width: 0 !important;">' +
                            '<h6 class="dropdown-header">Size</h6>' +
                            '<div class="dropdown-item" id="size" data-id="1" >S</div>' +
                            '<div class="dropdown-item" id="size" data-id="2" >M</div>' +
                            '<div class="dropdown-item" id="size" data-id="3" >L</div>' +
                            "</div>"
                        );
                    } else {
                        return "";
                    }
                },
            },
        ],
    });

    var resutlTableErr = $("#result-product-edit").DataTable({
        ajax: {
            url: "get-data-products-active",
            dataSrc: "products",
        },
        scrollY: "250px",
        ordering: false,
        paging: true,
        lengthChange: false,
        // autoWidth: true,
        limit: 10,
        responsive: true,
        searching: true,
        language: {
            search: "", // Tùy chỉnh văn bản gợi ý trong thanh tìm kiếm
            searchPlaceholder: "Search Product", // Tùy chỉnh placeholder cho thanh tìm kiếm
        },
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
                data: "unit",
                title: "",
                render: function (data, type, row) {
                    if (data == "Piece/Pack") {
                        return '<i class="fa-solid fa-plus btn btn-outline-success p-2 px-4" id="food-product"></i>';
                    } else if (data == "Cup") {
                        return (
                            '<button class="btn btn-outline-success fa-solid fa-plus p-2 px-4" id="drink-product" type="button" id="dropdownMenuIconButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            "</button>" +
                            '<div class="dropdown-menu" aria-labelledby="dropdownMenuIconButton1" style="min-width: 0 !important;">' +
                            '<h6 class="dropdown-header">Size</h6>' +
                            '<div class="dropdown-item" id="size" data-id="1" >S</div>' +
                            '<div class="dropdown-item" id="size" data-id="2" >M</div>' +
                            '<div class="dropdown-item" id="size" data-id="3" >L</div>' +
                            "</div>"
                        );
                    } else {
                        return "";
                    }
                },
            },
        ],
    });

    $("#result").on("click", "#food-product", function () {
        var table = $("#result").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        var productId = rowData.product_id;
        $.ajax({
            url: `get-data-product-size-{id}`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                product_id: productId,
            },
            success: function (response) {
                if (response.success) {
                    var rowDataProduct = {
                        product_name: rowData.product_name,
                        size: 4,
                        product_size_id:
                            response.productSize[0].product_size_id,
                        unit_price: response.productSize[0].unit_price,
                        quantity: 1,
                        amount: parseInt(response.productSize[0].unit_price),
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
                            for (var i = 0; i < dataOrders.length; i++) {
                                total += dataOrders[i].amount;
                            }
                            formatTotal(checkTotal(dataOrders));
                        } else {
                            addDataToTable(rowDataProduct);
                        }
                    }
                } else {
                }
            },
        });
    });

    $("#result").on("click", ".dropdown-item", function () {
        var table = $("#result").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        var productId = rowData.product_id;
        datasizeId = $(this).data("id");
        dataSize = $(this).text();
        $.ajax({
            url: `get-data-product-size-{id}`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                product_id: productId,
            },
            success: function (response) {
                if (response.success) {
                    var productSizes = response.productSize;
                    console.log(response);
                    for (var i = 0; i < productSizes.length; i++) {
                        if (
                            productSizes[i].size_id == datasizeId &&
                            productSizes[i].product_id == productId
                        ) {
                            var matches = productSizes[i];
                            var rowDataProduct = {
                                product_name:
                                    rowData.product_name + " " + dataSize,
                                size: matches.size_id,
                                product_size_id: matches.product_size_id,
                                unit_price: matches.unit_price,
                                quantity: 1,
                                amount: parseInt(matches.unit_price),
                            };
                            console.log(rowDataProduct);
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
                            matches.length = 0;
                        }
                        // rowDataProduct.length = 0;
                    }
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
                    {
                        data: "quantity",
                        title: "Quantity",
                        render: function (data, type, row) {
                            var html = '<div class="d-flex">';
                            html +=
                                '<button class="fa-solid fa-minus px-2 border mx-2 btn btn-outline-primary mb-1"></button>';
                            html += '<div class="mt-1">' + data + "</div>";
                            html +=
                                '<button class="fa-solid fa-plus px-2 border mx-2 btn btn-outline-primary mb-1"></button>';
                            html += "</div>";
                            return html;
                        },
                    },
                    {
                        data: "amount",
                        title: "Amount",
                        render: function (data, type, row) {
                            return formatCurrency(data); // Gọi hàm định dạng tiền tệ và trả về giá trị đã định dạng
                        },
                    },
                    {
                        data: null,
                        title: "",
                        render: function (data, type, row) {
                            return '<button class="btn btn-danger btn-sm status-btn py-1 btn-delete-product">Delete</button>';
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

    function generateOrderErrInfo(
        order_table,
        order_date,
        order_id,
        order_type,
        customer_inf
    ) {
        console.log(customer_inf);
        var deliveryArray = customer_inf.split("-");
        console.log(order_type);
        if (orderTableErrElement.text() === "") {
            orderTableErrElement.text(order_table);
            orderTableErr = order_table;
        }
        if (orderDateElement.text() === "") {
            orderDateErrElement.text(order_date);
            orderDateErr = order_date;
        }
        if (orderIdErrElement.text() === "") {
            orderIdErrElement.text(order_id);
            orderIdErr = order_date;
        }
        if (orderTypeElement.text() === "") {
            var order_type_text;
            if (order_type == 0) {
                orderTypeElement.addClass("text-success");
                orderTypeElement.removeClass("text-info");
                order_type_text = "Online";
            } else {
                orderTypeElement.addClass("text-info");
                orderTypeElement.removeClass("text-success");
                order_type_text = "Direct";
            }
            orderTypeElement.text(order_type_text);
        }
        if (order_type == 0) {
            cusInfElement.removeClass("d-none");
            cusInf.text(deliveryArray[0] + " - " + deliveryArray[1]);
        }
        formatTotalErr(checkTotal(dataOrdersErr));
        $("#order-err-card").removeClass("d-none");
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

    $("#productOrder").on("click", ".fa-plus, .fa-minus", function () {
        var table = $("#productOrder").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row; // Lấy chỉ số dòng của ô chứa nút được click
        var rowData = table.row(rowIdx).data(); // Lấy dữ liệu của dòng được click

        var quantity = rowData.quantity;
        var amount = rowData.amount;
        var unit_price = rowData.unit_price;
        if ($(this).hasClass("fa-plus") && quantity < 10) {
            quantity++;
            amount = quantity * unit_price;
        } else if ($(this).hasClass("fa-minus") && quantity > 1) {
            if (quantity > 1) {
                quantity--;
                amount = quantity * unit_price;
            } else if (quantity == 1) {
            }
        }

        rowData.quantity = quantity;
        rowData.amount = amount;
        table.row(rowIdx).data(rowData).draw(false);

        formatTotal(checkTotal(dataOrders));
        // Cập nhật lại tổng tiền
    });

    $("#productOrder").on("click", ".btn-delete-product", function () {
        var table = $("#productOrder").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        table.row(rowIdx).remove().draw();
        dataOrders.splice(rowIdx, 1);
        if (dataOrders.length == 0) {
            table.destroy();
        }
        console.log(dataOrders);
        // Cập nhật lại tổng tiền
        formatTotal(checkTotal(dataOrders));
    });

    function formatTotal(total) {
        totalFormat = formatCurrency(total);
        var totalElement = $("#total");
        totalElement.text(totalFormat);
    }

    function checkTotal(dOrder) {
        var total = 0;
        for (var i = 0; i < dOrder.length; i++) {
            total += parseInt(dOrder[i].amount);
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
                    return "The receipt must be bigger than the total amount to be paid";
                }
                if (value - Total > 500000) {
                    return "The amount received is too large, please try again";
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                const receivedAmount = result.value;
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
                                }).then(() => {
                                    var orderPendingTable =
                                        $("#orderPendingTable").DataTable();
                                    orderPendingTable.ajax.reload();
                                    removeDataProductTable();
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
        formattedTime = "";
    }

    var orderReady = $("#orderReadyTable")
        .DataTable({
            ajax: {
                url: "get-data-order-ready",
                dataSrc: "data",
            },
            ordering: false,
            paging: true,
            lengthChange: false,
            responsive: true,
            limit: 10,
            searching: true,
            autoWidth: true,
            responsive: true,
            columns: [
                { data: "order_id", title: "ID" },
                {
                    data: "order_type",
                    title: "Type",
                    render: function (data, type, row) {
                        return parseInt(data) === 1
                            ? '<div class="text-info py-1">Direct</div>'
                            : '<div class="text-success py-1">Online</div>';
                    },
                },
                { data: "order_date", title: "Order Date" },
                { data: "table_id", title: "Order Table" },
                {
                    data: "order_type",
                    title: "Actions",
                    render: function (data, type, row) {
                        if (data == 1) {
                            return (
                                '<button class="btn btn-outline-success btn-sm py-1" id="complete-btn" data-id="' +
                                row.order_id +
                                '">Complete Order</button>'
                            );
                        } else if (data == 0) {
                            return (
                                '<button class="btn btn-outline-warning btn-sm py-1" id="delivery-btn" data-id="' +
                                row.order_id +
                                '">Delivery</button>'
                            );
                        }
                    },
                },
            ],
        })
        .on("draw.dt", function () {
            updateReadyOrderCount();
        });

    function updateReadyOrderCount() {
        // Lấy bảng DataTable của tab "Ready"
        var readyTable = $("#orderReadyTable").DataTable();

        // Sử dụng Ajax của DataTable để lấy số lượng order
        $.ajax({
            url: readyTable.ajax.url(), // Lấy URL của yêu cầu Ajax của DataTable
            method: "GET",
            dataType: "json",
            success: function (data) {
                var orderCount = data.data.length; // Đếm số lượng order từ dữ liệu mới
                console.log(orderCount);
                $("#order-ready-tab-num").text(orderCount); // Cập nhật số lượng vào thẻ "num" của tab "Ready"
            },
        });
    }

    var orderDeli = $("#orderDelivery")
        .DataTable({
            ajax: {
                url: "get-data-order-delivering",
                dataSrc: "data",
            },
            ordering: false,
            paging: true,
            lengthChange: false,
            limit: 10,
            responsive: true,
            searching: true,
            autoWidth: true,
            responsive: true,
            columns: [
                { data: "order_id", title: "ID" },
                {
                    data: "order_type",
                    title: "Type",
                    render: function (data, type, row) {
                        return parseInt(data) === 1
                            ? '<div class="text-info py-1">Direct</div>'
                            : '<div class="text-success py-1">Online</div>';
                    },
                },
                { data: "delivery_code", title: "Delivery Code" },
                {
                    data: "null",
                    title: "Actions",
                    render: function (data, type, row) {
                        return (
                            '<button class="btn btn-outline-success btn-sm-btn py-1" id="complete-deli" data-id="' +
                            row.order_id +
                            '">Complete Order</button>'
                        );
                    },
                },
            ],
        })
        .on("draw.dt", function () {
            updateDeliOrderCount();
        });

    function updateDeliOrderCount() {
        var readyTable = $("#orderDelivery").DataTable();

        $.ajax({
            url: readyTable.ajax.url(),
            method: "GET",
            dataType: "json",
            success: function (data) {
                var orderCount = data.data.length;
                console.log(orderCount);
                $("#delivery-order-tab-num").text(orderCount);
            },
        });
    }

    $("#orderReadyTable").on("click", "#complete-btn", function () {
        var orderId = $(this).data("id");
        console.log("Order ID:", orderId);
        $.ajax({
            url: "complete-order",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                order_id: orderId,
            },
            success: function (response) {
                if (response.success) {
                    swal.fire({
                        icon: "success",
                        title: "SUCCESS",
                        text: "Order Completed",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    orderReady.ajax.reload();
                } else {
                }
            },
        });
    });

    $("#orderReadyTable").on("click", "#delivery-btn", function () {
        var orderId = $(this).data("id");
        Swal.fire({
            title: "Delivery Code",
            input: "text",
            inputLabel: "Enter Delivery Code.",
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return "This field is required !";
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                const deliverycode = result.value;
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    url: "delivery-order",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    data: {
                        order_id: orderId,
                        delivery_code: deliverycode,
                    },
                    success: function (response) {
                        if (response.success) {
                            swal.fire({
                                icon: "success",
                                title: "SUCCESS",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            orderReady.ajax.reload();
                        } else {
                        }
                    },
                });
            }
        });
    });

    $("#orderDelivery").on("click", "#complete-deli", function () {
        var orderId = $(this).data("id");
        $.ajax({
            url: "complete-order",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                order_id: orderId,
            },
            success: function (response) {
                if (response.success) {
                    swal.fire({
                        icon: "success",
                        title: "SUCCESS",
                        text: "Order Completed",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    orderDeli.ajax.reload();
                } else {
                }
            },
        });
    });

    var ordersErr = $("#orderError")
        .DataTable({
            ajax: {
                url: "get-data-order-error",
                dataSrc: "data",
            },
            ordering: false,
            paging: true,
            lengthChange: false,
            responsive: true,
            limit: 10,
            searching: true,
            autoWidth: true,
            responsive: true,
            columns: [
                { data: "order_id", title: "ID" },
                {
                    data: "order_type",
                    title: "Type",
                    render: function (data, type, row) {
                        return parseInt(data) === 1
                            ? '<div class="text-info py-1">Direct</div>'
                            : '<div class="text-success py-1">Online</div>';
                    },
                },
                { data: "order_date", title: "Order Date" },
                { data: "table_id", title: "Order Table" },
                {
                    data: null,
                    title: "Actions",
                    render: function (data, type, row) {
                        return (
                            '<button class="btn btn-outline-danger btn-sm py-1" id="handle-btn" data-id="' +
                            row.order_id +
                            '">Handle</button>'
                        );
                    },
                },
            ],
        })
        .on("draw.dt", function () {
            updateErrorOrderCount();
        });

    function updateErrorOrderCount() {
        // Lấy bảng DataTable của tab "Ready"
        var readyTable = $("#orderError").DataTable();

        // Sử dụng Ajax của DataTable để lấy số lượng order
        $.ajax({
            url: readyTable.ajax.url(), // Lấy URL của yêu cầu Ajax của DataTable
            method: "GET",
            dataType: "json",
            success: function (data) {
                var orderCount = data.data.length; // Đếm số lượng order từ dữ liệu mới
                console.log(orderCount);
                $("#error-order-tab-num").text(orderCount); // Cập nhật số lượng vào thẻ "num" của tab "Ready"
            },
        });
    }

    $("#orderError").on("click", "#handle-btn", function () {
        if ($.fn.DataTable.isDataTable("#productOrderError")) {
            $("#productOrderError").DataTable().destroy();
            $("#productOrderError").empty();
        }

        var table = $("#orderError").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        var orderId = rowData.order_id;
        $.ajax({
            url: `details-order-error`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                order_id: orderId,
            },
            success: function (response) {
                if (response.success) {
                    dataOrdersErr = response.products;
                    generateOrderErrInfo(
                        response.order.table_id,
                        response.order.order_date,
                        response.order.order_id,
                        response.order.order_type,
                        response.order.delivery_address
                    );
                    tableOrderErr = $("#productOrderError").DataTable({
                        data: dataOrdersErr,
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
                            {
                                data: "quantity",
                                title: "Quantity",
                                render: function (data, type, row) {
                                    var html = '<div class="d-flex">';
                                    html +=
                                        '<button id="btn-minus-err" class="fa-solid fa-minus px-2 border mx-2 btn btn-outline-primary mb-1"></button>';
                                    html +=
                                        '<div class="mt-1">' + data + "</div>";
                                    html +=
                                        '<button id="btn-plus-err" class="fa-solid fa-plus px-2 border mx-2 btn btn-outline-primary mb-1"></button>';
                                    html += "</div>";
                                    return html;
                                },
                            },
                            {
                                data: "amount",
                                title: "Amount",
                                render: function (data, type, row) {
                                    return formatCurrency(data);
                                },
                            },
                            {
                                data: null,
                                title: "",
                                render: function (data, type, row) {
                                    return '<button class="btn btn-danger btn-sm status-btn py-1 btn-delete-product">Delete</button>';
                                },
                            },
                            {
                                data: "out_of_stock",
                                title: "",
                                render: function (data, type, row) {
                                    if (data == true) {
                                        return '<p class="text-danger mb-0">OOS</p>';
                                    } else {
                                        return "";
                                    }
                                },
                            },
                        ],
                    });
                } else {
                }
            },
        });
    });

    $("#productOrderError").on(
        "click",
        "#btn-minus-err, #btn-plus-err",
        function () {
            var table = $("#productOrderError").DataTable();
            var rowIdx = table.cell($(this).closest("td, li")).index().row; // Lấy chỉ số dòng của ô chứa nút được click
            var rowData = table.row(rowIdx).data(); // Lấy dữ liệu của dòng được click

            var quantity = rowData.quantity;
            var amount = rowData.amount;
            var unit_price = rowData.unit_price;
            console.log(quantity);
            if ($(this).hasClass("fa-plus") && quantity < 10) {
                quantity++;
                amount = quantity * unit_price;
            } else if ($(this).hasClass("fa-minus") && quantity > 1) {
                if (quantity > 1) {
                    quantity--;
                    amount = quantity * unit_price;
                } else if (quantity == 1) {
                }
            }

            rowData.quantity = quantity;
            rowData.amount = amount;
            console.log(rowData);
            table.row(rowIdx).data(rowData).draw(false);

            formatTotalErr(checkTotal(dataOrdersErr));
            // Cập nhật lại tổng tiền
        }
    );

    function addDataToTableErr(rowData) {
        dataOrdersErr.push(rowData);

        var table = $("#productOrderError").DataTable();
        table.clear();
        table.rows.add(dataOrdersErr).draw();

        formatTotalErr(checkTotal(dataOrdersErr));
    }

    $("#result-product-edit").on("click", "#food-product", function () {
        var table = $("#result-product-edit").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        var productId = rowData.product_id;
        $.ajax({
            url: `get-data-product-size-{id}`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                product_id: productId,
            },
            success: function (response) {
                if (response.success) {
                    var rowDataProductEdit = {
                        product_name: rowData.product_name,
                        size: 4,
                        product_size_id:
                            response.productSize[0].product_size_id,
                        unit_price: response.productSize[0].unit_price,
                        quantity: 1,
                        amount: parseInt(response.productSize[0].unit_price),
                        out_of_stock: false,
                    };
                    console.log(rowDataProductEdit);
                    if (dataOrdersErr.length == 0) {
                        addDataToTableErr(rowDataProductEdit);
                    } else {
                        var duplicateProductErr = findDuplicateProduct(
                            dataOrdersErr,
                            rowDataProductEdit
                        );
                        console.log(duplicateProductErr);
                        if (duplicateProductErr) {
                            duplicateProductErr.quantity =
                                parseInt(duplicateProductErr.quantity) +
                                parseInt(rowDataProductEdit.quantity);

                            duplicateProductErr.amount =
                                duplicateProductErr.unit_price *
                                duplicateProductErr.quantity;
                            var table = $("#productOrderError").DataTable();
                            table.clear();
                            table.rows.add(dataOrdersErr).draw();
                            var totalErr = 0;
                            for (var i = 0; i < dataOrdersErr.length; i++) {
                                totalErr += dataOrders[i].amount;
                            }
                            formatTotalErr(checkTotal(dataOrdersErr));
                        } else {
                            addDataToTableErr(rowDataProductEdit);
                        }
                    }
                } else {
                }
            },
        });
    });

    $("#result-product-edit").on("click", ".dropdown-item", function () {
        var tableresult = $("#result-product-edit").DataTable();
        var rowIdx = tableresult.cell($(this).closest("td, li")).index().row;
        var rowData = tableresult.row(rowIdx).data();
        var productId = rowData.product_id;
        datasizeId = $(this).data("id");
        dataSize = $(this).text();
        $.ajax({
            url: `get-data-product-size-{id}`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                product_id: productId,
            },
            success: function (response) {
                if (response.success) {
                    var productSizes = response.productSize;
                    console.log(response);
                    for (var i = 0; i < productSizes.length; i++) {
                        if (
                            productSizes[i].size_id == datasizeId &&
                            productSizes[i].product_id == productId
                        ) {
                            var matches = productSizes[i];
                            var rowDataProduct = {
                                product_name:
                                    rowData.product_name + " " + dataSize,
                                size: matches.size_id,
                                product_size_id: matches.product_size_id,
                                unit_price: matches.unit_price,
                                quantity: 1,
                                amount: parseInt(matches.unit_price),
                                out_of_stock: false,
                            };
                            console.log(rowDataProduct);
                            if (dataOrdersErr.length == 0) {
                                addDataToTableErr(rowDataProduct);
                            } else {
                                var duplicateProduct = findDuplicateProduct(
                                    dataOrdersErr,
                                    rowDataProduct
                                );
                                if (duplicateProduct) {
                                    duplicateProduct.quantity =
                                        parseInt(duplicateProduct.quantity) +
                                        parseInt(rowDataProduct.quantity);

                                    duplicateProduct.amount =
                                        duplicateProduct.unit_price *
                                        duplicateProduct.quantity;
                                    var table =
                                        $("#productOrderError").DataTable();
                                    table.clear();
                                    table.rows.add(dataOrdersErr).draw();
                                    var total = 0;
                                    for (
                                        var i = 0;
                                        i < dataOrders.length;
                                        i++
                                    ) {
                                        total += dataOrdersErr[i].amount;
                                    }
                                    formatTotalErr(checkTotal(dataOrdersErr));
                                } else {
                                    addDataToTableErr(rowDataProduct);
                                }
                            }
                        }
                    }
                }
            },
        });
    });

    function formatTotalErr(total) {
        totalFormat = formatCurrency(total);
        var totalErrElement = $("#total-err");
        totalErrElement.text(totalFormat);
    }

    $("#productOrderError").on("click", ".btn-delete-product", function () {
        var table = $("#productOrderError").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        table.row(rowIdx).remove().draw();
        dataOrdersErr.splice(rowIdx, 1);
        // if (dataOrders.length == 0) {
        //     table.destroy();
        // }
        console.log(dataOrdersErr);
        // Cập nhật lại tổng tiền
        formatTotalErr(checkTotal(dataOrdersErr));
    });

    function removeDataProductTableErr() {
        dataOrdersErr = [];
        $("#productOrderError").DataTable().destroy();
        orderTableErrElement.text("");
        orderIdErrElement.text("");
        orderTableErrElement.text("");
        cusInf.text("");
        orderTypeElement.text("");
        $("#order-err-card").addClass("d-none");
    }

    $("#order-error").on("click", "#delete-order-err-btn", function () {
        Swal.fire({
            title: "Cancel ?",
            icon: "question",
            text: "Do you want to cancel this order ?",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                var order_id = $("#order-code-err").text();
                // console.log(order_id);
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    url: "delete-order",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    data: {
                        order_id: order_id,
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Success",
                                icon: "success",
                                text: "Cancel Order Successfully !",
                                showConfirmButton: false,
                                timer: 1000,
                            }).then(() => {
                                Swal.fire({
                                    title: "Return",
                                    icon: "info",
                                    text:
                                        "Return the customer " +
                                        response.orderTotal +
                                        " VND",
                                    showConfirmButton: true,
                                }).then(() => {
                                    var orderErrorTable =
                                        $("#orderError").DataTable();
                                    orderErrorTable.ajax.reload();
                                    removeDataProductTableErr();
                                });
                            });
                        }
                    },
                });
            }
        });
    });

    $("#order-error").on("click", "#exit-btn", function () {
        removeDataProductTableErr();
    });

    $("#order-error").on("click", "#update-order-btn", function () {
        if (dataOrdersErr.length == 0) {
            Swal.fire({
                title: "Error",
                icon: "error",
                text: "There must be at least one product in the order",
                showConfirmButton: false,
                timer: 2500,
            });
        } else {
            const hasOutOfStockProduct = dataOrdersErr.some(
                (product) => product.out_of_stock
            );
            if (hasOutOfStockProduct) {
                Swal.fire({
                    title: "Error",
                    icon: "error",
                    text: "Please remove out-of-stock products before updating",
                    showConfirmButton: false,
                    timer: 2500,
                });
            } else {
                Swal.fire({
                    title: "Update",
                    icon: "question",
                    text: "Do you want to update order ?",
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var csrfToken = $('meta[name="csrf-token"]').attr(
                            "content"
                        );
                        var order_id = $("#order-code-err").text();
                        $.ajax({
                            url: "update-order",
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                            },
                            data: {
                                order_id: order_id,
                                products: dataOrdersErr,
                            },
                            success: function (response) {
                                if (response.success) {
                                    curent_total = response.total_amount;
                                    last_total = response.last_total;
                                    Swal.fire({
                                        title: "Success",
                                        icon: "success",
                                        text: "Update Order Successfully !",
                                        showConfirmButton: false,
                                        timer: 1000,
                                    }).then(() => {
                                        if (curent_total >= last_total) {
                                            Swal.fire({
                                                title: "Additional charge",
                                                icon: "info",
                                                text:
                                                    "Additional charge from customer " +
                                                    (curent_total -
                                                        last_total) +
                                                    " VND",
                                                showConfirmButton: true,
                                            }).then(() => {
                                                var orderErrorTable =
                                                    $(
                                                        "#orderError"
                                                    ).DataTable();
                                                orderErrorTable.ajax.reload();
                                                removeDataProductTableErr();
                                            });
                                        } else {
                                            Swal.fire({
                                                title: "Return",
                                                icon: "info",
                                                text:
                                                    "Return the customer " +
                                                    (last_total -
                                                        curent_total) +
                                                    " VND",
                                                showConfirmButton: true,
                                            }).then(() => {
                                                var orderErrorTable =
                                                    $(
                                                        "#orderError"
                                                    ).DataTable();
                                                orderErrorTable.ajax.reload();
                                                removeDataProductTableErr();
                                            });
                                        }
                                    });
                                }
                            },
                        });
                    }
                });
            }
        }
    });

    setInterval(function () {
        orderReady.ajax.reload();
        resutlTable.ajax.reload();
        orderDeli.ajax.reload();
        resutlTableErr.ajax.reload();
        ordersErr.ajax.reload();
    }, 10000);
});
