$(document).ready(function () {
    //check và xử lí sự kiện thay đổi ảnh
    $('#change-image-btn').click(function () {
        $('#image-input').val('');
        $('#image-input').click();
    });

    $('#image-input').change(function () {
        var input = this;
        var image = $('#product-image')[0];

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(image).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }

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

    //Rule valid data
    $("#form-validate-update").validate({
        rules: {
            product_name: {
                required: true,
                minlength: 1,
                regex: true,
                minimumLetters: true
            },
            descriptions: {
                regex: true,
            },
            image_input: {
                required: true,
                accept: "image/*"
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
        },
        messages: {
            product_name: {
                required: "Please enter product name",
                minimumLetters: "Product name must be at least 1 characters long",
                minlength: "Name must be at least 1 character",
                regex: "Tên không được chứa số và kí tự đặc biệt",
            },
            descriptions: {
                regex: "Field cannot contain numbers and special characters",
                minimumLetters: "Descriptions must be at least 1 character"
            },
            image_input: {
                required: "Please select an image",
                accept: "Please select a valid image file (JPEG, PNG, GIF)"
            },
            priceS: {
                positiveNumber: "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",
            },
            priceM: {
                positiveNumber: "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",

            },
            priceL: {
                positiveNumber: "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",
            },
            priceU: {
                positiveNumber: "The amount is a positive integer and greater than 1000.",
                wholeNumber: "Invalid value",
                divisibleBy1000: "The amount must be a multiple of 1000",
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
            }).then((create) => {
                if (create) {
                    var form = document.getElementById('form-validate-update');
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var formData = new  FormData(form);
                    console.log(formData);
                    
                    $.ajax({
                        type: form.method,
                        url: form.action,
                        data: formData,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (response) {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
            });
        }
    });
});
