$(document).ready(function () {
    let sizeName = document.getElementById("inputSizeName");
    let saveBtn = document.getElementById("saveButton");
    if (saveBtn) {
        saveBtn.addEventListener("click", function (event) {
            event.preventDefault();
            //Check if all fields are filled and valid:
            if (!sizeName.value) {
                alert("Please enter size!");
                return;
            }

            //Check if size is valid: it can only be between 1 to 100:
            let trimmedSizeName = sizeName.value.trim();
            if (trimmedSizeName < 1 || trimmedSizeName > 100) {
                alert("Please enter a valid size!");
                return;
            }

            $.ajax({
                url: 'http://localhost/frontend/index.php?module=dashboard&view=size.view',
                method: "POST",
                dataType: "json",
                data: {
                    sizeName: sizeName.value,
                    saveBtn: true
                },

                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        //Close the modal
                        $('#addSizeModal').modal('hide');
                        window.location.reload();
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function (error) {
                    // handle error
                    console.log('Error: ' + error);
                }
            });
        });
    }
});