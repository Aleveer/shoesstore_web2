$(document).ready(function () {
    let deleteBtn = document.querySelectorAll('[name="deleteSizeBtn"]');
    deleteBtn.forEach(function (button, index) {
        button.addEventListener('click', function () {
            console.log('delete button clicked');
            let sizeId = $(this).closest('tr').find('td:first').text();
            $.ajax({
                url: 'http://localhost/frontend/index.php?module=dashboard&view=size.view',
                method: 'POST',
                dataType: 'json',
                data: {
                    sizeId: sizeId,
                    deleteSizeBtn: true,
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
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
});