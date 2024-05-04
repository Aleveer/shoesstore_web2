$(document).ready(function () {
    var loginBtn = $("#loginBtn");

    if (loginBtn.length) {
        loginBtn.on("click", function (e) {
            var email = $('input[name="email"]').val();
            var password = $('input[name="password"]').val();
            if ($('.messageErrorDetail').length) {
                $('.messageErrorDetail').text();
            }
            $.ajax({
                url: "http://localhost/frontend/?module=auth&action=login",
                type: "POST",
                dataType: "json",
                data: {
                    email: email,
                    password: password
                },

                success: function (data) {
                    // Xử lý phản hồi từ server ở đây
                    console.log(data); // Hiển thị dữ liệu phản hồi từ server
                    if (data.status == 'error') {
                        if ($('.messageErrorDetail').length) {
                            $('.messageErrorDetail').text(data.message);
                        } else {
                            $('.message').append("<div class='messageErrorDetail cw text-center alert alert-danger'>" + data.message + "</div>");
                        }
                    } else {
                        window.location.href = "?module=indexphp&action=userhomepage";
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                    alert("Error occurred. Please try again.");
                },
            });
        });
    }
});