$(document).ready(function () {
    $(".login-form").submit(function (event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: $(this).attr("action"), // Lấy action của form
            method: "POST",
            data: formData, // Dữ liệu từ form đã được serialized
            success: function (response) {
                if (response.message === "Your Account is not actived") {
                    Swal.fire({
                        title: "Error",
                        icon: "error",
                        html: `
                        Your Account is not actived, click
                          <a href="/getActive">here</a>,
                          to active your account
                        `,
                    });
                } else if (!response.success) {
                    Swal.fire({
                        icon: "error",
                        text: response.message,
                        title: "Error",
                    });
                } else {
                    Swal.fire({
                        icon: "success",
                        text: response.message,
                        title: "Success",
                        showConfirmButton: false,
                    });
                    window.location.href = "/home";
                }
            },
            error: function (xhr, status, error) {
                // Xử lý lỗi khi gửi yêu cầu AJAX
                console.error(error);
            },
        });
    });
});
