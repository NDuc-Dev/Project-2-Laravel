$(document).ready(function () {
    $("#form-validate").validate({
        rules: {
            email: {
                required: true,
                regexEmail: true,
            },
        },
        messages: {
            email: {
                required: "Vui lòng nhập email",
                regexEmail: "Email không hợp lệ",
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
            var formData = $(form).serialize();
            console.log($(form).attr("action"));
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
                        }).then((confirm) => {
                            if (confirm) {
                                window.location.href = "/login";
                            }
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


    //email
    $.validator.addMethod("regexEmail", function (value, element) {
        return /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(value);
    });
});
