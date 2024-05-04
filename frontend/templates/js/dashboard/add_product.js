$(document).ready(function () {
    let productName = document.getElementById("inputProductName");
    let chosenCategory = document.getElementById("inputProductCate");
    let productPrice = document.getElementById("inputPrice");
    let chosenGender = document.getElementById("inputGender");
    let productDescription = document.getElementById("w3review");
    let productImageUpload = document.getElementById("inputImg");
    let imageProductReview = document.getElementById("imgPreview");
    productImageUpload.addEventListener('change', (event) => {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let base64Image = e.target.result
                imageProductReview.src = base64Image;
            }
            reader.readAsDataURL(file);
        }
    })

    let saveBtn = document.getElementById("saveButton");
    if (saveBtn) {
        saveBtn.addEventListener("click", function (event) {
            event.preventDefault();

            if (!productName.value) {
                alert("Please enter product name");
                //clear the input field
                productName.value = "";
                return;
            }

            if (!chosenCategory.value) {
                alert("Please select a category");
                return;
            }

            if (!productPrice.value) {
                alert("Please enter product price");
                //clear the input field
                productPrice.value = "";
                return;
            }

            if (isNaN(productPrice.value)) {
                alert("Please enter a valid price");
                //clear the input field
                productPrice.value = "";
                return;
            }

            if (productPrice.value < 1) {
                alert("Price can't be less than 1");
                //clear the input field
                productPrice.value = "";
                return;
            }

            // if (chosenGender.value == "0") {
            //     chosenGender.value = 0;
            // } else {
            //     chosenGender.value = 1;
            // }
            // console.log(chosenGender.value);
            //Check description:
            if (!productDescription.value) {
                alert("Please enter product description");
                //clear the input field
                productDescription.value = "";
                return;
            }

            //Check valid description:
            let trimmedDescription = productDescription.value.trim();
            if (trimmedDescription.length < 10) {
                alert("Description must be at least 10 characters long");
                return;
            }

            // Check if an image has been selected
            if (productImageUpload.files.length === 0) {
                alert("Please select an image");
                return;
            }

            $.ajax({
                url: window.location.href,
                method: "POST",
                dataType: "json",
                data: {
                    productName: productName.value,
                    category: chosenCategory.value,
                    price: productPrice.value,
                    gender: chosenGender.value,
                    description: productDescription.value,
                    image: imageProductReview.src,
                    saveBtn: true,
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        window.location.reload();
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error creating product: " + errorThrown);
                }
            });
        });
    }
});