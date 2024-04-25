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
                required: "Please enter your password",
                regexPassword: "Password must contain at least 6-18 characters",
            },
            confirmation_password: {
                equalToPassword: "Password incorect",
                required: "Please enter your password",
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
                                    text: "Change Password success",
                                    icon: "success",
                                }).then((confirm) => {
                                    if (confirm) {
                                        window.location.reload();
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
                                text: "An error occurred, please try again!",
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


    $("#form-validate-update").validate({
        rules: {
            name: {
                required: true,
                minlength: 1,
                regex: true,
                minimumLetters: true
            },
            email: {
                required: true,
                regexEmail: true,
            },
            phone: {
                required: true,
                regexPhone: true,
            },
        },
        messages: {
            name: {
                required: "Please enter your name",
                minlength: "Name must be at least 1 character",
                regex: "Name cannot contain numbers and special characters",
                minimumLetters: "Invalid name"
            },
            email: {
                required: "Please enter your email",
                regexEmail: "Invalid Email"
            },
            phone: {
                required: "Please enter your phone number",
                regexPhone: "Invalid Phone number",
            },
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
            element.next().addClass(
                "error text-danger py-2");
        },
        success: function (label, element) {
            $(element).next().remove();
        },
        submitHandler: function (form) {
            Swal.fire({
                title: "Continue ?",
                text: "Do you want to continue ?",
                icon: "question",
            }).then((Update) => {
                if (Update) {
                    var formData = $(form).serialize();
                    $.ajax({
                        type: 'POST',
                        url: $(form).attr('action'), 
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if(response.success)
                            {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                            else{
                                Swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Xử lý lỗi
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred, please try again!',
                                icon: 'error'
                            });
                        }
                    });
                } else {

                }
            });
        }
    });


    $('#form-validate').submit(function (event) {
        if (!$(this).valid()) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Please fill in all required fields correctly.",
            });
            event.preventDefault();
        }
    });

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
});
