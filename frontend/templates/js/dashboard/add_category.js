$(document).ready(function () {
    let categoryName = document.getElementById("inputCategoryName");
    let saveBtn = document.getElementById("saveButton");
    if (saveBtn) {
        saveBtn.addEventListener("click", function (event) {
            event.preventDefault();
            //Check if all fields are filled and valid:
            if (!categoryName.value) {
                alert("Please enter category name");
                return;
            }
            //Check if category name is valid:
            let trimmedCategoryName = categoryName.value.trim();
            if (trimmedCategoryName.length < 3) {
                alert("Please enter a valid category name");
                return;
            }
            $.ajax({
                url: 'http://localhost/frontend/index.php?module=dashboard&view=category.view',
                method: "POST",
                dataType: "json",
                data: {
                    categoryName: categoryName.value,
                    saveBtn: true
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        //Close the modal
                        $('#addCategoryModal').modal('hide');
                        window.location.reload();
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function (data) {
                    alert("Error adding category");
                }
            });
        });
    }
});