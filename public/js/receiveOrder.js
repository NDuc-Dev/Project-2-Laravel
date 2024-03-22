$(document).ready(function(){
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
                data: "order_status",
                title: "Status",
                render: function (data, type, row) {
                    if (data == 0) {
                        return '<label class="badge badge-danger py-1">Pending</label>';
                    } else if (data == 1) {
                        return '<label class="badge badge-warning py-1">Inprogress</label>';
                    } else if (data == 2) {
                        return '<label class="badge badge-primary py-1">Ready</label>';
                    } else if (data == 3) {
                        return '<label class="badge badge-success py-1">Delivering</label>';
                    } else {
                        return "";
                    }
                },
            },
        ],
    });

    setInterval(function() {
        tablePendingOrder.ajax.reload();
    }, 30000); // 30 giây

    $("#orderPendingTable").on("click", "tr", function () {
        var rowData = $("#orderPendingTable").DataTable().row(this).data();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log(rowData);
        $.ajax({
            url: "get-data-order-details-"+ rowData.order_id,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                console.log(response);
            },
        });
    });
});