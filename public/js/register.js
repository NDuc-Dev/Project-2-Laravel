$(document).ready(function () {
    $("#form-validate").validate({
        rules: {
            name: {
                required: true,
                minlength: 1,
                regex: true,
                minimumLetters: true,
            },
            email: {
                required: true,
                regexEmail: true,
            },
            phone: {
                required: true,
                regexPhone: true,
            },
            user_name: {
                required: true,
                regexUserName: true,
            },
            password: {
                required: true,
                regexPassword: true,
            },
            password_confirmation: {
                required: true,
                equalToPassword: true,
            },
        },
        messages: {
            name: {
                required: "Vui lòng nhập tên",
                minlength: "Tên phải ít nhất 1 ký tự",
                regex: "Tên không được chứa số và kí tự đặc biệt",
                minimumLetters: "Tên không hợp lệ",
            },
            email: {
                required: "Vui lòng nhập email",
                regexEmail: "Email không hợp lệ",
            },
            phone: {
                required: "Vui lòng nhập số điện thoại",
                regexPhone: "Số điện thoại không hợp lệ",
            },
            user_name: {
                required: "Vui lòng nhập tên người dùng",
                regexUserName:
                    "Tên người dùng không hợp lệ, chỉ chứa các kí tự a-Z 0-9 - _ + và độ dài 3-16 kí tự",
            },
            password: {
                required: "Vui lòng nhập mật khẩu",
                regexPassword: "Mật khẩu phải chứa ít nhất 6-18 kí tự",
            },
            password_confirmation: {
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
                    var username = $("#user_name").val();
                    var phone = $("#phone").val();
                    var email = $("#email").val();
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "check-dupplicate-info-guest",
                        method: "POST",
                        data: {
                            _token: token,
                            username: username,
                            phone: phone,
                            email: email,
                        },
                        success: function (response) {
                            if (response.exists) {
                                Swal.fire({
                                    title: "Error !",
                                    text: response.message,
                                    icon: "error",
                                });
                            } else {
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
                                            }).then((confirm)=>{
                                                if(confirm){
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
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                            Swal.fire({
                                title: "Error !",
                                text: "An unexpected error occurred",
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

    //custom rule
    //name
    $.validator.addMethod("regex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z ]+$/.test(value);
    });
    $.validator.addMethod("minimumLetters", function (value, element) {
        var lettersCount = value.replace(/\s/g, "").length;
        return lettersCount >= 1;
    });

    //email
    $.validator.addMethod("regexEmail", function (value, element) {
        return /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(value);
    });

    //phone
    $.validator.addMethod("regexPhone", function (value, element) {
        return /(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b/.test(value);
    });

    //user name
    $.validator.addMethod("regexUserName", function (value, element) {
        return /^[a-z0-9_-]{3,16}$/.test(value);
    });

    //password
    $.validator.addMethod("regexPassword", function (value, element) {
        return /^[a-zA-Z0-9!@#$%^&*()_+-=<>?]{6,18}$/.test(value);
    });

    //role
    $.validator.addMethod("selectRequired", function (value, element, arg) {
        return arg !== value;
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
