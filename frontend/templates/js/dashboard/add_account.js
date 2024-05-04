$(document).ready(function () {
    let username = document.getElementById("inputUsername");
    let password = document.getElementById("inputPassword");
    let email = document.getElementById("inputEmail");
    let name = document.getElementById("inputName");
    let phone = document.getElementById("inputPhone");
    let gender = document.getElementById("inputGender");
    let role = document.getElementById("inputRole");
    let address = document.getElementById("inputAddress");
    let imageUpload = document.getElementById("inputImg");
    let imagePreview = document.getElementById("imgPreview");

    imageUpload.addEventListener('change', (event) => {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let base64Image = e.target.result
                imagePreview.src = base64Image;
            }
            reader.readAsDataURL(file);
        }
    })

    let saveBtn = document.getElementById("saveBtn");
    if (saveBtn) {
        saveBtn.addEventListener("click", function (event) {
            event.preventDefault();

            // Check if all fields are filled and valid:
            if (!username.value || !password.value || !email.value || !name.value || !phone.value || !address.value) {
                alert("Please fill all fields");
                return;
            }

            // Check if an image has been selected
            if (imageUpload.files.length === 0) {
                alert("Please select an image");
                return;
            }

            $.ajax({
                url: window.location.href,
                method: "POST",
                dataType: "json",
                data: {
                    username: username.value,
                    password: password.value,
                    email: email.value,
                    name: name.value,
                    phone: phone.value,
                    gender: gender.value,
                    role: role.value,
                    address: address.value,
                    image: imagePreview.src,
                    saveBtn: true,
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        //Close the modal
                        $('#addAccountModal').modal('hide');
                        // Redirect to account view page
                        window.location.href = "http://localhost/frontend/index.php?module=dashboard&view=account.view";
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error creating account: " + errorThrown);
                }
            });
        });
    }
});