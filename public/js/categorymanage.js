$(document).ready(function () {
    // validate form
    $("#form-validate").validate({
        rules: {
            category_name: {
                required: true,
                minlength: 1,
            },
            categorySelect: {
                selectRequired: "",
            },
        },
        messages: {
            category_name: {
                required: "Vui lòng nhập tên category",
                minlength: "Tên phải ít nhất 1 ký tự",
            },
            descriptions: {
                regexEmail: "Email không hợp lệ",
            },
            categorySelect: {
                selectRequired: "Vui lòng chọn group cho category",
            },
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
            element.next().addClass("error text-danger py-2");
        },
        success: function (label, element) {
            $(element).next().remove();
        },
        submitHandler: function (form) {
            Swal.fire({
                title: "Continue ?",
                text: "Do you want to continue ?",
                icon: "question",
            }).then((willCreate) => {
                if (willCreate) {
                    var formData = $(form).serialize();
                    $.ajax({
                        type: "POST",
                        url: $(form).attr("action"),
                        data: formData,
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1500,
                                }).then(() => {
                                    var categoryTable =
                                        $("#categoryTable").DataTable();
                                    categoryTable.ajax.reload();
                                    var form =
                                        document.getElementById(
                                            "form-validate"
                                        );
                                    form.reset();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: response.message,
                                    icon: "error",
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: "Error",
                                text: "An error occurred while create staff information!",
                                icon: "error",
                            });
                        },
                    });
                }
            });
        },
    });

    $("#form-validate").submit(function (event) {
        if (!$(this).valid()) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Please fill in all required fields correctly.",
            });
            event.preventDefault();
        }
    });

    // //role
    $.validator.addMethod("selectRequired", function (value, element, arg) {
        return arg !== value;
    });

    $("#categoryTable").DataTable({
        ajax: {
            url: "get-data-category",
            dataSrc: "dataCategory",
        },
        autoWidth: true,
        responsive: true,
        columns: [
            { data: "category_id", title: "ID" },
            { data: "category_name", title: " Category" },
            {
                data: "category_status",
                title: "Status",
                render: function (data, type, row) {
                    if (data == 1) {
                        return '<label class="badge badge-success">Active</label>';
                    } else if (data == 0) {
                        return '<label class="badge badge-danger">Inactive</label>';
                    } else {
                        return "";
                    }
                },
            },
            {
                data: "group_id",
                title: "Group",
                render: function (data, tyep, row) {
                    return parseInt(data) === 1 ? "Drink" : "Food";
                },
            },
            {
                data: null,
                title: "Actions",
                render: function (data, type, row) {
                    return (
                        '<button class="btn btn-info btn-sm view-btn py-1" data-bs-toggle="modal" data-bs-target="#modal-product-list" data-name="' +
                        row.category_name +
                        '">View Product In Category</button>' +
                        '<span class ="p-1"></span>' +
                        '<button class="btn btn-danger btn-sm py-1" id="change-status-category" data-id="' +
                        row.category_id +
                        '">Change Status</button>'
                    );
                },
            },
        ],
    });
    
    var categoryname = "";
    $("#categoryTable").on("click", ".view-btn", function () {
        var table = $("#categoryTable").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        categoryname = rowData.category_name;
        handleUpdateClickView(categoryname);
    });
    
    function handleUpdateClickView(category) {
        createProductsTable(category);
    }

    $("#categoryTable").on("click", "#change-status-category", function () {
        var table = $("#categoryTable").DataTable();
        var rowIdx = table.cell($(this).closest("td, li")).index().row;
        var rowData = table.row(rowIdx).data();
        categoryId = rowData.category_id;
        handleUpdateClickChangeCategoryStatus(categoryId);
    });

    function handleUpdateClickChangeCategoryStatus(categoryId){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/manage/categories/change-status",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                category_id : categoryId,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.messages,
                        showConfirmButton: false,
                        timer: 1000,
                    }).then(() => {
                        var categoryTable = $("#categoryTable").DataTable();
                        categoryTable.ajax.reload();
                    });
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = "An unexpected error occurred";
                if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else {
                    errorMessage = error;
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage,
                });
            },
        });
    }

    function createProductsTable(category) {
        $("#productsTable").DataTable({
            ajax: {
                url: "get-data-products-" + category,
                dataSrc: "dataProducts",
            },
            autoWidth: true,
            responsive: true,
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
                            return `<img src="${firstImagePath}" alt="${row.product_name}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/50x50';">`;
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
                    data: "status",
                    title: "Status",
                    render: function (data, type, row) {
                        if (data == 1) {
                            return '<label class="badge badge-success">On Sale</label>';
                        } else if (data == 0) {
                            return '<label class="badge badge-danger">Inactive</label>';
                        } else {
                            return "";
                        }
                    },
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
                },
                {
                    data: null,
                    title: "Action",
                    render: function (data, type, row) {
                        return (
                            '<button class="btn btn-info btn-sm edit-btn py-1" data-id="' +
                            row.product_id +
                            '">Update</button>' +
                            '<span class ="p-1"></span>' +
                            '<button class="btn btn-danger btn-sm status-btn py-1" data-id="' +
                            row.product_id +
                            '">Change Status</button>'
                        );
                    },
                },
            ],
        });
    }

    $(".btn-close-product").on("click", function () {
        var productTable = $("#productsTable").DataTable();
        productTable.destroy();
    });

    $("#productsTable").on("click", ".edit-btn", function () {
        var productId = $(this).data("id");
        handleUpdateClickUpdate(productId);
    });

    function handleUpdateClickUpdate(productId) {
        $.ajax({
            url: "/manage/products/update-product-" + productId,
            method: "GET",
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.error,
                    });
                } else {
                    window.location.href =
                        "/manage/products/update-product-" + productId;
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = "An unexpected error occurred";
                if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else {
                    errorMessage = error;
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: errorMessage,
                    });
                }
            },
        });
    }

    $("#productsTable").on("click", ".status-btn", function () {
        var productId = $(this).data("id");
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
        $.ajax({
            url: "/manage/products/change-status-" + productId,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.messages,
                        showConfirmButton: false,
                        timer: 1000,
                    }).then(() => {
                        var productTable = $("#productsTable").DataTable();
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
