$(document).ready(function () {
    $(document).on('submit', 'form[id^="editForm"]', function (e) {
        e.preventDefault();
        console.log('edit form submitted');
        var couponId = $(this).closest('.modal').attr('id').replace('editModal', '');
        var couponCode = $('#inputEdtCouponCodeId' + couponId);
        var couponQuantity = $('#inputEdtCouponQuantityId' + couponId);
        var couponDiscount = $('#inputEdtCouponDiscount' + couponId);
        var couponDescription = $('#couponEdtDescriptionId' + couponId);
        var couponExpiry = $('#inputEdtCouponExpiredId' + couponId);

        function isValidInput() {
            return couponCode.val() && couponQuantity.val() && couponDiscount.val() && couponExpiry.val();
        }

        function isValidCouponCode() {
            let trimmedCouponCode = couponCode.val().trim();
            return trimmedCouponCode.length >= 3;
        }

        function isValidCouponQuantity() {
            let trimmedCouponQuantity = parseInt(couponQuantity.val().trim());
            return !isNaN(trimmedCouponQuantity) && trimmedCouponQuantity > 0;
        }

        function isValidCouponDiscount() {
            let trimmedCouponDiscount = parseFloat(couponDiscount.val().trim());
            return !isNaN(trimmedCouponDiscount) && trimmedCouponDiscount >= 0 && trimmedCouponDiscount <= 100;
        }

        function isValidCouponExpiry() {
            return couponExpiry.val() !== '';
        }

        if (!isValidInput()) {
            alert('All fields are required');
            return;
        }

        if (!isValidCouponCode()) {
            alert('Coupon code must be at least 3 characters');
            return;
        }

        if (!isValidCouponQuantity()) {
            alert('Coupon quantity must be a positive number');
            return;
        }

        if (!isValidCouponDiscount()) {
            alert('Coupon discount must be a number between 0 and 100');
            return;
        }

        if (!isValidCouponExpiry()) {
            alert('Coupon expiry date is required');
            return;
        }

        $.ajax({
            url: 'http://localhost/frontend/index.php?module=dashboard&view=coupon.view',
            method: 'POST',
            datatype: 'json',
            data: {
                id: couponId,
                code: couponCode.val(),
                quantity: couponQuantity.val(),
                discount: couponDiscount.val(),
                description: couponDescription.val(),
                expiry: couponExpiry.val(),
                editButtonName: true,
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
                // handle any errors
                alert('An error occurred: ' + error);
            }
        });
    });
});