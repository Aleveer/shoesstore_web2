$(document).ready(function () {
    $(document).on('submit', 'form', function (e) {
        e.preventDefault();
        console.log('edit form submitted');
        var permissionId = $(this).closest('.modal').attr('id').replace('editPermissionModal_', '');
        var permissionName = $('#inputName_' + permissionId).val();
        //check if the permission name is empty
        if (permissionName === '') {
            alert('Permission cannot be empty');
            return;
        }
        if (permissionName.trim() === '') {
            alert('Permission name cannot be empty');
            return;
        }

        $.ajax({
            url: 'http://localhost/frontend/index.php?module=dashboard&view=permission.view',
            type: 'POST',
            dataType: 'json',
            data: {
                id: permissionId,
                name: permissionName,
                submit: true
            },
            success: function (data) {
                if (data.status == "success") {
                    alert(data.message);
                    $('#editPermissionModal_' + permissionId).modal('hide'); // Close the modal
                } else if (data.status == "error") {
                    alert(data.message);
                }
            },
            error: function (response) {
                console.error('Failed to update permission');
            }
        });
    });
});