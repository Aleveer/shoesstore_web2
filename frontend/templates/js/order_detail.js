document.addEventListener('DOMContentLoaded', function () {
    var viewButtons = document.querySelectorAll('.view-button');
    viewButtons.forEach(function (button, index) {
        button.addEventListener('click', function () {
            var orderId = this.getAttribute('data-order-id');
            // Fetch and display order details based on orderId
            console.log('Displaying details for order id: ' + orderId);
            // Use fetch to get order details:
            fetch('http://localhost/frontend/index.php?module=account&action=order-detail&orderId=' + orderId)
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log('There has been a problem with your fetch operation: ' + error.message);
                });
        });
    });
});