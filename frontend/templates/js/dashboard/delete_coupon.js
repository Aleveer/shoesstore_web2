$(document).ready(function () {
    let deleteBtn = document.querySelectorAll('[name="deleteCouponBtnName"]');
    deleteBtn.forEach(function (button, index) {
        button.addEventListener('click', function () {
            console.log('delete button clicked');
            let couponId = $(this).closest('tr').find('td:first').text();
            $.ajax({
                url: 'http://localhost/frontend/index.php?module=dashboard&view=coupon.view',
                method: 'POST',
                dataType: 'json',
                data: {
                    couponId: couponId,
                    deleteCouponBtn: true,
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        window.location.reload();
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Delete request failed');
                    // Handle the error response here
                }
            });
        });
    });
});