$(document).ready(function () {
    $("#form-validate").validate({
        rules: {
            password: {
                required: true,
                regexPassword: true,
            },
            confirmation_password: {
                required: true,
                equalToPassword: true,
            },
        },
        messages: {
            password: {
                required: "Vui lòng nhập mật khẩu",
                regexPassword: "Mật khẩu phải chứa ít nhất 6-18 kí tự",
            },
            confirmation_password: {
                equalToPassword: "mật khẩu không khớp",
                required: "Vui lòng nhập mật khẩu",
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
                } else {
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

    //password
    $.validator.addMethod("regexPassword", function (value, element) {
        return /^[a-zA-Z0-9!@#$%^&*()_+-=<>?]{6,18}$/.test(value);
    });

    $.validator.addMethod(
        "equalToPassword",
        function (value, element) {
            var password = $("#password").val();
            return value === password;
        },
        "Confirm password does not match."
    );
});
