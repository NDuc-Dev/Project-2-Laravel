$(document).ready(function () {
    // validate form
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
            role: {
                selectRequired: "--Choose a Role--",
            }
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
            role: {
                selectRequired: "Please select a role for the user"
            }
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
                        type: 'PUT',
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
                                    window.location.href = '/manage/staff/staff-management';
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
                                text: 'An error occurred while updating staff information!',
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

    //role
    $.validator.addMethod("selectRequired", function (value, element, arg) {
        return arg !== value;
    });


    $('form').on('click', '.reset-password', function () {
        var userId = document.getElementById("user_id").value;
        Swal.fire({
            title: 'Reset password ?',
            text: 'Are you sure you want to reset password?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                handleUpdateClickResetPassword(userId);
            }
        });
    });

    function handleUpdateClickResetPassword(userId) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        showSpinner();
        $.ajax({
            url: '/manage/staff/reset-password-' + userId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function (response) {
                if (response.success) {
                    hideSpinner();
                    Swal.fire({
                        icon: 'success',
                        title: 'Change Password Success',
                        text: 'Your new password is ' + response.newPassword
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
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });

    }

    function showSpinner() {
        $('#container-spinner').removeClass('d-none');
    }

    // Ẩn spinner
    function hideSpinner() {
        $('#container-spinner').addClass('d-none');
    }
});

