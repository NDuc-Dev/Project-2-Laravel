$(document).ready(function () {
    // validate form
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
            role: {
                selectRequired: "",
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
            role: {
                selectRequired: "Vui lòng chọn vai trò cho người dùng",
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
                    var token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "check-dupplicate-user-name",
                        method: "POST",
                        data: {
                            _token: token,
                            username: username,
                        },
                        success: function (response) {
                            if (response.exists) {
                                Swal.fire({
                                    title: "Error !",
                                    text: "User name is already exits.",
                                    icon: "error",
                                });
                            } else {
                                var formData = $(form).serialize();
                                showSpinner();
                                $.ajax({
                                    type: "POST",
                                    url: $(form).attr("action"),
                                    data: formData,
                                    dataType: "json",
                                    success: function (response) {
                                        if (response.success) {
                                            hideSpinner();
                                            Swal.fire({
                                                title: "Success",
                                                text: response.message,
                                                icon: "success",
                                                showConfirmButton: false,
                                                timer: 1500,
                                            }).then(() => {
                                                var staffTable =
                                                    $(
                                                        "#staffTable"
                                                    ).DataTable();
                                                staffTable.ajax.reload();
                                                var form =
                                                    document.getElementById(
                                                        "form-validate"
                                                    );
                                                form.reset();
                                            });
                                        } else {
                                            hideSpinner();
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

    $("#staffTable").DataTable({
        ajax: {
            url: "get-data-staff",
            dataSrc: "data",
        },
        autoWidth: true,
        responsive: true,
        columns: [
            { data: "user_id", title: "ID" },
            { data: "name", title: "Name" },
            { data: "role", title: "Role" },
            { data: "email", title: "Email" },
            { data: "phone", title: "Phone" },
            {
                data: "status",
                title: "Status",
                render: function (data, type, row) {
                    return parseInt(data) === 1
                        ? '<label class="badge badge-success py-1">Active</label>'
                        : '<label class="badge badge-warning py-1">Inactive</label>';
                },
            },
            {
                data: null,
                title: "Actions",
                render: function (data, type, row) {
                    return (
                        '<button class="btn btn-info btn-sm edit-btn py-1" data-id="' +
                        row.user_id +
                        '">Update</button>' +
                        '<span class ="p-1"></span>' +
                        '<button class="btn btn-danger btn-sm status-btn py-1" data-id="' +
                        row.user_id +
                        '">Change Status</button>'
                    );
                },
            },
        ],
    });

    $("#staffTable").on("click", ".status-btn", function () {
        var userId = $(this).data("id");
        Swal.fire({
            title: "Change Status?",
            text: "Are you sure you want to change the status?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                handleUpdateClickChange(userId);
            }
        });
    });

    function handleUpdateClickChange(userId) {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        showSpinner();
        $.ajax({
            url: "/manage/staffs/change-status-" + userId,
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
                        var staffTable = $("#staffTable").DataTable();
                        staffTable.ajax.reload();
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
    $("#staffTable").on("click", ".edit-btn", function () {
        var userId = $(this).data("id");
        handleUpdateClickUpdate(userId);
    });

    function handleUpdateClickUpdate(userId) {
        showSpinner();
        $.ajax({
            url: "/manage/staffs/update-staff-" + userId,
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
                        "/manage/staffs/update-staff-" + userId;
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

    function showSpinner() {
        $("#container-spinner").removeClass("d-none");
    }

    function hideSpinner() {
        $("#container-spinner").addClass("d-none");
    }
});
