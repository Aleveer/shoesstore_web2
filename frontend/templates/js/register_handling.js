
$(document).ready(function () {
    var registerBtn = $("#registerBtn");
    if (registerBtn.length) {
        registerBtn.on("click", function (e) {
            var fullname = $('input[name="fullname"]').val();
            var username = $('input[name="username"]').val();
            var email = $('input[name="email"]').val();
            var phone = $('input[name="phone"]').val();
            var address = $('input[name="address"]').val();
            var password = $('input[name="password"]').val();
            var password_confirm = $('input[name="password_confirm"]').val();
            var gender = $('input[name="gender"]:checked').val() ? $('input[name="gender"]:checked').val() : '';
            if ($('.top_message')) {
                $('.top_message').remove();
            }
            $.ajax({
                url: "http://localhost/frontend/?module=auth&action=register",
                type: "POST",
                dataType: "json",
                data: {
                    fullname: fullname,
                    username: username,
                    email: email,
                    phone: phone,
                    address: address,
                    password: password,
                    password_confirm: password_confirm,
                    gender: gender,
                    registerBtn: true
                },
                success: function (data) {
                    if (data.status == 'success') {
                        var msg = "<div class='top_message cw text-center alert alert-success'>" + data.message + "</div>";
                        $('.header_register').after(msg);
                        $('input[type="text"]').val('');
                        $('input[type="password"]').val('');
                        $('input[type="number"]').val('');
                        // Uncheck các radio button
                        $('input[type="radio"]').prop('checked', false);
                    } else {
                        $('.error-message').remove();
                        var errorsArray = Object.entries(data.errors);
                        for (var i = 0; i < errorsArray.length; i++) {
                            $('input[name="' + errorsArray[0][0] + '"]').focus();
                            if (Array.isArray(errorsArray[i])) {
                                for (var j = 0; j < errorsArray[i].length; j++) {
                                    errorsDetail = Object.entries(errorsArray[i][1])
                                    console.log(errorsDetail);
                                    for (var k = 0; k < errorsDetail.length; k++) {
                                        showErrorMessage(errorsArray[i][0], errorsDetail[k][1]);
                                        break;
                                    }
                                    break;
                                }
                            }
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                    console.error("Status:", status);
                    console.error("XHR:", xhr);
                    alert("Error occurred. Please try again.");
                },
            });
        });
    }

    function showErrorMessage(inputName, message) {
        var inputElement = $('input[name="' + inputName + '"]');
        var errorMessageElement = '<div class="error-message text-danger">' + message + '</div>';
        inputElement.closest('.form-group').append(errorMessageElement);
    }
});
