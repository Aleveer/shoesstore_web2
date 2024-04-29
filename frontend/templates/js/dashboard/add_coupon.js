$(document).ready(function () {
    let couponCode = document.getElementById("inputCouponCodeId");
    let couponQuantity = document.getElementById("inputCouponQuantityId");
    let couponDiscount = document.getElementById("inputCouponDiscount");
    let couponDescription = document.getElementById("couponDescriptionId");
    let couponExpiredDate = document.getElementById("inputCouponExpiredId");
    let saveBtn = document.getElementById("saveButton");

    function isValidInput() {
        return couponCode.value && couponQuantity.value && couponDiscount.value && couponDescription.value && couponExpiredDate.value;
    }

    function isValidCouponCode() {
        let trimmedCouponCode = couponCode.value.trim();
        return trimmedCouponCode.length >= 3;
    }

    function isValidCouponQuantity() {
        let trimmedCouponQuantity = parseInt(couponQuantity.value.trim());
        return !isNaN(trimmedCouponQuantity) && trimmedCouponQuantity > 0;
    }

    function isValidCouponDiscount() {
        let trimmedCouponDiscount = couponDiscount.value.trim();
        return trimmedCouponDiscount.length >= 1 && !isNaN(trimmedCouponDiscount) && trimmedCouponDiscount >= 0 && trimmedCouponDiscount <= 100;
    }

    function isValidCouponDescription() {
        let trimmedCouponDescription = couponDescription.value.trim();
        return trimmedCouponDescription.length >= 3;
    }

    function isValidCouponExpiredDate() {
        let trimmedCouponExpiredDate = couponExpiredDate.value.trim();
        if (trimmedCouponExpiredDate.length < 1) {
            return false;
        }
        let currentDate = new Date();
        let expiredDate = new Date(trimmedCouponExpiredDate);
        return expiredDate >= currentDate;
    }

    if (saveBtn) {
        saveBtn.addEventListener("click", function (event) {
            event.preventDefault();
            if (!isValidInput()) {
                alert("Please fill all fields");
                return;
            }
            if (!isValidCouponCode()) {
                alert("Coupon code must be at least 3 characters long");
                return;
            }
            if (!isValidCouponQuantity()) {
                alert("Please enter a valid coupon quantity");
                return;
            }
            if (!isValidCouponDiscount()) {
                alert("Please enter a valid coupon discount");
                return;
            }
            if (!isValidCouponDescription()) {
                alert("Please enter a valid coupon description");
                return;
            }
            if (!isValidCouponExpiredDate()) {
                alert("Please enter a valid expired date");
                return;
            }

            $.ajax({
                url: 'http://localhost/frontend/index.php?module=dashboard&view=coupon.view',
                method: "POST",
                dataType: "html",
                data: {
                    couponCode: couponCode.value,
                    couponQuantity: couponQuantity.value,
                    couponDiscount: couponDiscount.value,
                    couponDescription: couponDescription.value,
                    couponExpiredDate: couponExpiredDate.value,
                    saveBtn: true
                },
                success: function () {
                    alert("Coupon added successfully");
                    window.location.reload();
                },
                error: function () {
                    alert("Error adding coupon");
                }
            });
        });
    }
});