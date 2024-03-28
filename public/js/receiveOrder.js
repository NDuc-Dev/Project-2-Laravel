$(document).ready(function () {
    var orderInprogress = false;
    var orderInprogressData = null;
    CheckOrderInprogress();
    function CheckOrderInprogress() {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "check-order-inprogress",
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if (response.success) {
                    renderOrderProcessngTable(response.data, response.orderId);
                    orderInprogress = true;
                    orderInprogressData = response.order;
                } else {
                }
            },
        });
    }

    var tablePendingOrder = $("#orderPendingTable").DataTable({
        ajax: {
            url: "get-data-order-pending",
            dataSrc: "data",
        },
        ordering: false,
        paging: true,
        lengthChange: false,
        limit: 10,
        responsive: true,
        searching: false,
        autoWidth: true,
        responsive: true,
        columns: [
            { data: "order_id", title: "Order ID" },
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
        ],
    });

    setInterval(function () {
        tablePendingOrder.ajax.reload(function (json) {
            if (tablePendingOrder.data().any()) {
            } else {
                tablePendingOrder.clear().draw();
            }
        });
    }, 10000);

    $("#orderPendingTable").on("click", "tr", function () {
        if (!orderInprogress) {
            var rowData = $("#orderPendingTable").DataTable().row(this).data();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                url: "get-data-order-details-" + rowData.order_id,
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    tablePendingOrder.ajax.reload(function (json) {
                        if (tablePendingOrder.data().any()) {
                            if (response.success) {
                                renderOrderProcessngTable(
                                    response.data,
                                    rowData.order_id
                                );
                                orderInprogress = true;
                                orderInprogressData = rowData;
                            } else {
                                swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            }
                        } else {
                            tablePendingOrder.clear().draw();
                            if (response.success) {
                                renderOrderProcessngTable(
                                    response.data,
                                    rowData.order_id
                                );
                                orderInprogress = true;
                                orderInprogressData = rowData;
                            } else {
                                swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            }
                        }
                    });
                },
            });
        } else {
            swal.fire({
                title: "Error",
                icon: "error",
                text: "You need to complete your current order before receiving a new order",
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });

    function renderOrderProcessngTable(orderData, orderId) {
        $("#orderProcessingAfter").removeClass("d-none");
        $("#orderProcessingBefore").addClass("d-none");
        $("#order-id").text(orderId);
        $("#orderProcessingTable").DataTable({
            data: orderData,
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
            ],
        });
    }

    $("#complete-order-btn").on("click", function () {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "change-status-order-to-ready-" + orderInprogressData.order_id,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if (response.success) {
                    swal.fire({
                        title: "Success",
                        icon: "success",
                        text: "Successful, the order has moved to ready status",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    orderInprogress = false;
                    orderInprogressData = null;
                    $("#orderProcessingAfter").addClass("d-none");
                    $("#orderProcessingBefore").removeClass("d-none");
                    $("#order-id").text("");
                    $("#orderProcessingTable").DataTable().destroy();
                } else {
                    swal.fire({
                        title: "Error",
                        icon: "error",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
        });
    });
});
