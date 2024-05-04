$(document).ready(function () {
    let productName = document.getElementById("inputProductName");
    let sizeSelect = document.getElementById("inputSizeId");
    let quantity = document.getElementById("inputQuantity");
    let saveBtn = document.getElementById("saveButton");

    function isValidInput() {
        let size = sizeSelect.value;
        return productName.value && size && quantity.value;
    }

    function isValidQuantity() {
        let trimmedQuantity = parseInt(quantity.value.trim());
        return !isNaN(trimmedQuantity) && trimmedQuantity > 0;
    }

    if (saveBtn) {
        saveBtn.addEventListener("click", function (event) {
            event.preventDefault();
            let size = sizeSelect.value;
            console.log(size);
            if (!isValidInput()) {
                alert("Please fill all fields");
                return;
            }
            if (!isValidQuantity()) {
                alert("Please enter a valid quantity");
                return;
            }

            $.ajax({
                url: 'http://localhost/frontend/index.php?module=dashboard&view=inventory.view',
                method: "POST",
                dataType: "json",
                data: {
                    productName: productName.value,
                    size: size,
                    quantity: quantity.value,
                    saveBtnName: true
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        //Close the modal
                        $('#addSizeItemModal').modal('hide');
                        window.location.reload();
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert("Error adding item");
                }
            });
        });
    }
});