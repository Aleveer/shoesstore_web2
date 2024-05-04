$(document).ready(function () {
    // handle the form submit event

    $(document).on('submit', 'form[id^="editForm"]', function (e) {
        e.preventDefault();
        console.log('edit form submitted');
        var sizeId = $(this).closest('.modal').attr('id').replace('editModal', '');
        var sizeName = $('#inputSizeName' + sizeId).val();
        //check if the size name is empty
        if (sizeName === '') {
            alert('Size cannot be empty');
            return;
        }
        if (sizeName.trim() === '') {
            alert('Size name cannot be empty');
            return;
        }

        //Check if size is valid: it can only be between 1 to 100:
        if (sizeName.trim() < 1 || sizeName.trim() > 100) {
            alert("Please enter a valid size!");
            return;
        }

        $.ajax({
            url: 'http://localhost/frontend/index.php?module=dashboard&view=size.view',
            method: 'POST',
            dataType: 'json',
            data: {
                id: sizeId,
                name: sizeName,
                editButtonName: true
            },
            success: function (data) {
                if (data.status == "success") {
                    alert(data.message);
                    //Close the modal
                    $('#editModal' + sizeId).modal('hide');
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
});