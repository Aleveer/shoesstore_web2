$(document).ready(function () {
    $(document).on('submit', 'form[id^="form_"]', function (e) {
        e.preventDefault();
        console.log('Button clicked');
        var formId = $(this).attr('id');
        var productId = formId.split('_')[1];
        console.log(productId);
        var sizeId = formId.split('_')[2];
        console.log(sizeId);
        var newQuantity = parseInt($('#inputNewQuantity_' + productId + '_' + sizeId).val(), 10);
        console.log(newQuantity);
        var currentQuantity = parseInt($('#inputQuantity_' + productId + '_' + sizeId).val(), 10);
        console.log(currentQuantity);
        //Check quantity:
        if (isNaN(newQuantity)) {
            alert('Please enter a valid quantity');
            return;
        }

        if (newQuantity < 0) {
            var checkingQuantity = currentQuantity + newQuantity;
            if (checkingQuantity < 1) {
                alert('Quantity cannot be less than 1');
                return;
            }
        }

        $.ajax({
            url: 'http://localhost/frontend/index.php?module=dashboard&view=inventory.view',
            type: 'POST',
            dataType: 'json',
            data: {
                productId: productId,
                sizeId: sizeId,
                newQuantity: newQuantity,
                currentQuantity: currentQuantity,
                button: true
            },
            success: function (data) {
                if (data.status == "success") {
                    alert(data.message);
                    //close the modal
                    $('#updateModal_' + productId + '_' + sizeId).modal('hide');
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