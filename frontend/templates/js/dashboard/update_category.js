$(document).ready(function () {
    // handle the form submit event

    $(document).on('submit', 'form[id^="editForm"]', function (e) {
        e.preventDefault();
        console.log('edit form submitted');
        var categoryId = $(this).closest('.modal').attr('id').replace('editModal', '');
        var categoryName = $('#inputCategoryName' + categoryId).val();

        //check if the category name is empty
        if (categoryName === '') {
            alert('Category name cannot be empty');
            return;
        }
        if (categoryName.length > 100) {
            alert('Category name cannot be more than 100 characters');
            return;
        }
        if (categoryName.length < 3) {
            alert('Category name cannot be less than 3 characters');
            return;
        }
        if (categoryName.trim() === '') {
            alert('Category name cannot be empty');
            return;
        }
        $.ajax({
            url: 'http://localhost/frontend/index.php?module=dashboard&view=category.view',
            method: 'POST',
            datatype: 'json',
            data: {
                id: categoryId,
                name: categoryName,
                editButtonName: true
            },
            success: function (data) {
                if (data.status == "success") {
                    alert(data.message);
                    //Close the modal
                    $('#editModal' + categoryId).modal('hide');
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