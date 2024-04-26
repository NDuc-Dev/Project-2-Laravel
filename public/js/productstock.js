$(document).ready(function () {
    function initializeDataTableProduct(data) {
        $("#productList").DataTable({
            ajax: {
                url: "get-data-products",
                dataSrc: "data",
            },
            scrollY: "250px",
            ordering: false,
            paging: true,
            lengthChange: false,
            // autoWidth: true,
            limit: 10,
            responsive: true,
            searching: true,
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
                            return '<label class="badge badge-success">In Stock</label>';
                        } else if (data == 0) {
                            return '<label class="badge badge-danger">Out Of Stock</label>';
                        } else {
                            return "";
                        }
                    },
                    searchable: false,
                },
                {
                    data: null,
                    title: "Action",
                    render: function (data, type, row) {
                        return (
                            '<button class="btn btn-danger btn-sm status-btn py-1" data-id="' +
                            row.product_id +
                            '">Change Status</button>'
                        );
                    },
                    searchable: false,
                },
            ],
        });
    }

    initializeDataTableProduct();

    $("#productList").on("click", ".status-btn", function () {
        var productId = $(this).data("id");
        console.log(productId);
        Swal.fire({
            title: "Change Status?",
            text: "Are you sure you want to change the status?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                handleUpdateClickChange(productId);
            }
        });
    });

    function handleUpdateClickChange(productId) {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        // showSpinner();
        $.ajax({
            url: "change-status-instock-" + productId,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data:{
                product_id: productId,
            },
            success: function (response) {
                if (response.success) {
                    hideSpinner();
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.messages,
                        showConfirmButton: false,
                        timer: 1000,
                    }).then(() => {
                        var productTable = $("#productList").DataTable();
                        productTable.ajax.reload();
                    });
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = "An unexpected error occurred";
                if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else {
                    errorMessage = error;
                    hideSpinner();
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage,
                });
            },
        });
    }
});
