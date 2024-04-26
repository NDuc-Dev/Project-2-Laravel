$(document).ready(function () {
    //xử lí validate và sự kiện submit form
    $("#form-validate").validate({
        rules: {
            product_name: {
                required: true,
                minlength: 1,
                regex: true,
                minimumLetters: true,
            },
            descriptions: {
                required: true,
            },
            image_input: {
                required: true,
                accept: "image/*",
            },
            priceS: {
                positiveNumber: true,
                wholeNumber: true,
                divisibleBy1000: true,
            },
            priceM: {
                positiveNumber: true,
                wholeNumber: true,
                divisibleBy1000: true,
            },
            priceL: {
                positiveNumber: true,
                wholeNumber: true,
                divisibleBy1000: true,
            },
            priceU: {
                positiveNumber: true,
                wholeNumber: true,
                divisibleBy1000: true,
            },
            group_category: {
                selectRequired: "",
            },
            categorySelect: {
                selectRequired: "",
            },
        },
        messages: {
            product_name: {
                required: "Please enter product name",
                minimumLetters:
                    "Product name must be at least 1 characters long",
                minlength: "Name must be at least 1 character",
                regex: "Tên không được chứa số và kí tự đặc biệt",
            },
            descriptions: {
                regex: "Field cannot contain numbers and special characters",
                minimumLetters: "Descriptions must be at least 1 character",
            },
            image_input: {
                required: "Please select an image",
                accept: "Please select a valid image file (JPEG, PNG, GIF)",
            },
            priceS: {
                positiveNumber:
                    "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",
            },
            priceM: {
                positiveNumber:
                    "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",
            },
            priceL: {
                positiveNumber:
                    "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",
            },
            priceU: {
                positiveNumber:
                    "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",
            },
            group_category: {
                selectRequired: "Please select group category",
            },
            categorySelect: {
                selectRequired: "Please select category",
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
            }).then((create) => {
                if (create) {
                    var form = document.getElementById("form-validate");
                    var formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500,
                            }).then(() => {
                                var productTable =
                                    $("#productTable").DataTable();
                                productTable.ajax.reload();

                                var form =
                                    document.getElementById("form-validate");
                                form.reset();
                                $(".food-size").addClass("d-none");
                                $(".drink-size").addClass("d-none");
                                removeImage();
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: "Error",
                                text: "An error occurred while processing your request. Please try again later.",
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        },
                    });
                }
            });
        },
    });

    $.validator.addMethod("regex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z ]+$/.test(value);
    });

    $.validator.addMethod("minimumLetters", function (value, element) {
        var lettersCount = value.replace(/\s/g, "").length;
        return lettersCount >= 1;
    });

    $.validator.addMethod("selectRequired", function (value, element, arg) {
        return arg !== value;
    });

    $.validator.addMethod("positiveNumber", function (value, element) {
        return value >= 1000;
    });
    $.validator.addMethod("wholeNumber", function (value, element) {
        return /^\d+$/.test(value);
    });
    $.validator.addMethod("divisibleBy1000", function (value, element) {
        return parseInt(value) % 1000 === 0;
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

    //xử lí form khi chọn và hiển thị category
    $("#group_category").change(function () {
        var selectedGroup = $(this).val();
        var categorySelect = $("#categorySelect");
        if (selectedGroup === "1") {
            $(".drink-size").removeClass("d-none");
            $(".food-size").addClass("d-none");
            categorySelect.empty();
            categorySelect.append(
                `<option selected disabled>--Choose a Category--</option>`
            );
            dataCategory.forEach(function (category) {
                if (selectedGroup === "1" && category.group_id === 1) {
                    categorySelect.append(
                        `<option value="${category.category_name}">${category.category_name}</option>`
                    );
                }
            });
        } else if (selectedGroup === "2") {
            $(".food-size").removeClass("d-none");
            $(".drink-size").addClass("d-none");
            categorySelect.empty();
            categorySelect.append(
                `<option selected disabled>--Choose a Category--</option>`
            );
            dataCategory.forEach(function (category) {
                if (selectedGroup === "2" && category.group_id === 2) {
                    categorySelect.append(
                        `<option value="${category.category_name}">${category.category_name}</option>`
                    );
                }
            });
        } else {
            $(".drink-size").hide();
            $(".food-size").hide();
        }
    });

    //lấy data của product và fill vào bảng
    $("#productTable").DataTable({
        ajax: {
            url: "get-data-products",
            dataSrc: "dataProducts",
        },
        autoWidth: true,
        responsive: true,
        scrollX: false,
        columns: [
            {
                data: null,
                title: "",
                render: function (data, type, row) {
                    return (
                        '<input type="checkbox" class="select-checkbox" data-id="' +
                        row.product_id +
                        '">'
                    );
                },
                orderable: false,
                searchable: false,
                width: "10px",
            },
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
            // {
            //     data: "status_in_stock",
            //     title: "Status In Stock",
            //     render: function (data, type, row) {
            //         if (data == 1) {
            //             return '<label class="badge badge-success">In Stock</label>';
            //         } else if (data == 0) {
            //             return '<label class="badge badge-danger">Out Of Stock</label>';
            //         } else {
            //             return "";
            //         }
            //     },
            // },
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

    $("#productTable").on("click", ".status-btn", function () {
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
        showSpinner();
        $.ajax({
            url: "/manage/products/change-status-" + productId,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
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
                        var productTable = $("#productTable").DataTable();
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

    $("#productTable").on("click", ".edit-btn", function () {
        var productId = $(this).data("id");
        handleUpdateClickUpdate(productId);
    });

    function handleUpdateClickUpdate(productId) {
        showSpinner();
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
                    hideSpinner();
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: errorMessage,
                    });
                }
            },
        });
    }

    //Data form create product

    $("#image-select").click(function () {
        $("#image-input").click();
    });

    $("#image-input").change(function () {
        var file = $(this)[0].files[0];
        if (file && file.type.match("image.*")) {
            var fileSize = file.size;
            var maxSize = 8 * 1024 * 1024; // 2MB

            if (fileSize > maxSize) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Image size should not exceed 2MB",
                });
                $(this).val("");
            } else {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $("#image-data").val(e.target.result);
                    $("#image-select").css(
                        "background-image",
                        "url(" + e.target.result + ")"
                    );
                    $(".content-before").addClass("d-none");
                    $("#remove-image-btn").removeClass("d-none");
                };

                reader.readAsDataURL(this.files[0]);
            }
        } else {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please select an image file (PNG, JPG, JPEG, GIF)",
            });
            $(this).val("");
        }
    });

    $("#remove-image-btn").click(function () {
        removeImage();
    });

    function removeImage() {
        $("#image-input").val("");

        $("#image-select").css("background-image", "none");

        $("#remove-image-btn").addClass("d-none");

        $(".content-before").removeClass("d-none");
    }

    function showSpinner() {
        $("#container-spinner").removeClass("d-none");
    }

    function hideSpinner() {
        $("#container-spinner").addClass("d-none");
    }

    var selectedProductIds = [];

    $(document).on("change", ".select-checkbox", function () {
        var productId = $(this).data("id");

        if ($(this).is(":checked")) {
            selectedProductIds.push(productId);
        } else {
            var index = selectedProductIds.indexOf(productId);
            if (index !== -1) {
                selectedProductIds.splice(index, 1);
            }
        }
    });

    $("#btn-change-status-products").on("click", function () {
        if (selectedProductIds.length == 0) {
            Swal.fire({
                title: "Error",
                text: "No products selected, please try again",
                icon: "error",
                showConfirmButton: false,
                timer: 1500,
            });
        } else {
            Swal.fire({
                title: "Change Status?",
                text: "Are you sure you want to change the status?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    handleStatusChange(selectedProductIds);
                }
            });
        }
    });

    function handleStatusChange(listIds) {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        showSpinner();
        $.ajax({
            url: "change-products-status",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                listIds: listIds,
            },
            success: function (response) {
                if (response.success) {
                    hideSpinner();
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1000,
                    }).then(() => {
                        var productTable = $("#productTable").DataTable();
                        productTable.ajax.reload();
                        selectedProductIds = [];
                    });
                }
                console.log(response);
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
