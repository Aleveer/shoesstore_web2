$(document).ready(function () {
    $(document).on('click', 'button[id^="deleteSizeItemBtn"]', function (e) {
        e.preventDefault();

        var buttonId = $(this).attr('id');
        console.log('Button ID: ' + buttonId); // Log the button ID

        var ids = buttonId.split('_');
        console.log('Split ID: ', ids); // Log the result of the split operation

        var productId = ids[1];
        var sizeId = ids[2];

        console.log('Product ID: ' + productId);
        console.log('Size ID: ' + sizeId);

        var confirmDelete = confirm('Are you sure you want to delete this item?');
        if (confirmDelete) {
            $.ajax({
                url: 'http://localhost/frontend/index.php?module=dashboard&view=inventory.view',
                type: 'POST',
                datatype: 'json',
                data: {
                    productId: productId,
                    sizeId: sizeId,
                    delete: true
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        $(this).closest('tr').remove();
                        window.location.reload();
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // handle error
                    console.log('Delete failed: ' + textStatus);
                }
            });
        }
    });
});