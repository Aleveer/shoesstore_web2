document.addEventListener('DOMContentLoaded', function () {
    // Select the elements
    var minusBtn = document.querySelectorAll('[name="minusBtn"]');
    var plusBtn = document.querySelectorAll('[name="plusBtn"]');
    var productQuantity = document.querySelectorAll('[name="productQuantity"]');
    var cartPrice = document.querySelectorAll('[name="cartPrice"]');
    var cartTotal = document.querySelectorAll('[name="cartTotal"]');
    var deleteCartItem = document.querySelectorAll('[name="cartAction"]');
    var cartItem = document.querySelectorAll('[name="itemContainer"]');
    var cartId = document.querySelectorAll('[name="cartId"]');
    var cartSelect = document.querySelectorAll('[name="cartSelect"]');
    var cartSelectAll = document.querySelector('[name="cartSelectAll"]');
    var continueBtn = document.getElementById('order-continue');
    //var nameProduct = document.querySelectorAll('[name="prodyctName"]');
    // Add event listeners to the minus and plus buttons
    minusBtn.forEach(function (button, index) {
        button.addEventListener('click', function () {
            console.log('minus button clicked');
            // Decrease the quantity by 1
            var quantity = parseInt(productQuantity[index].value, 10);
            quantity = isNaN(quantity) ? 0 : quantity;
            if (quantity > 1) {
                quantity--;
                productQuantity[index].value = quantity;
                fetch('http://localhost/frontend/index.php?module=cartsection&action=cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'quantity=' + quantity + '&cartId=' + cartId[index].value,
                })
                    .then(function (response) {
                        return response.text();
                    })
                    .then(function (data) {
                        console.log(data);
                    });
                // Update the total price
                var price = parseFloat(cartPrice[index].textContent);
                cartTotal[index].textContent = price * quantity;
            } else {
                alert('Quantity cannot be less than 1');
                console.log('Quantity cannot be less than 1');
            }
        });
    });

    // Add event listeners to the minus and plus buttons
    plusBtn.forEach(function (button, index) {
        button.addEventListener('click', function () {
            console.log('plus button clicked');
            // Increase the quantity by 1
            var quantity = parseInt(productQuantity[index].value, 10);
            quantity = isNaN(quantity) ? 0 : quantity;
            var maxQuantity = parseInt(productQuantity[index].dataset.maxQuantity, 10);
            if (quantity < maxQuantity) {
                quantity++;
                productQuantity[index].value = quantity;
                fetch('http://localhost/frontend/index.php?module=cartsection&action=cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'quantity=' + quantity + '&cartId=' + cartId[index].value,
                })
                    .then(function (response) {
                        return response.text();
                    })
                    .then(function (data) {
                        console.log(data);
                    });

                // Update the total price
                var price = parseFloat(cartPrice[index].textContent);
                cartTotal[index].textContent = price * quantity;
            } else {
                alert('Cannot exceed available product quantity');
                console.log('Cannot exceed available product quantity');
            }
        });
    });

    // Add event listener to the trash icon
    deleteCartItem.forEach(function (button, index) {
        button.addEventListener('click', function () {
            console.log('trash icon clicked');
            // Remove the item from the cart
            cartItem[index].remove();
            //Send post:
            fetch('http://localhost/frontend/index.php?module=cartsection&action=cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'delete=true&cartId=' + cartId[index].value,
            })
        });
    });

    // // Add event listener to the select all checkbox
    // if (cartSelectAll) {
    //     cartSelectAll.addEventListener('click', function () {
    //         console.log('select all checkbox clicked');
    //         var cartIdArray = [];
    //         cartId.forEach(function (cart) {
    //             cartIdArray.push(cart.value);
    //         });
    //         cartSelect.forEach(function (checkbox) {
    //             checkbox.checked = cartSelectAll.checked;
    //         });
    //         //only send selected items when continue button is clicked:
    //         continueBtn.addEventListener('click', function () {
    //             sendSelectedItems(null, cartIdArray);
    //         });
    //     });

    //     // Add event listener to the individual checkboxes
    //     cartSelect.forEach(function (checkbox) {
    //         checkbox.addEventListener('change', function () {
    //             //Get id of a cart:

    //             // Uncheck the select all checkbox if any of the individual checkboxes are unchecked
    //             if (!checkbox.checked) {
    //                 cartSelectAll.checked = false;
    //             }
    //             // Collect and send the IDs of the selected items
    //             var cartIdArray = [];
    //             cartSelect.forEach(function (checkbox) {
    //                 if (checkbox.checked) {
    //                     //Pass id of a cart: i have var cartId:
    //                     cartIdArray.push(checkbox.value);
    //                     console.log(checkbox.value);
    //                 }
    //             });
    //             continueBtn.addEventListener('click', function () {
    //                 sendSelectedItems(null, cartIdArray);
    //             });

    //             // Check if multiple checkboxes are checked
    //             var checkedCount = 0;
    //             cartSelect.forEach(function (checkbox) {
    //                 if (checkbox.checked) {
    //                     checkedCount++;
    //                 }
    //             });
    //             if (checkedCount > 1) {
    //                 console.log('Multiple checkboxes are checked');
    //             }
    //         });
    //     });

    //     // Function to collect the IDs of the selected items and send them to order.php
    //     function sendSelectedItems(cartId, cartIdArray) {
    //         // If cartId is an array, use it directly. Otherwise, create an array with cartId as the only item
    //         var selectedItems = Array.isArray(cartId) ? cartId : [cartId];
    //         // If cartIdArray is provided, use it to override selectedItems
    //         if (cartIdArray) {
    //             selectedItems = cartIdArray;
    //         }
    //         // Send the IDs to order.php
    //         // This could be an AJAX request or a form submission, depending on your needs
    //         // For example, using jQuery's AJAX method:
    //         $.ajax({
    //             url: 'http://localhost/frontend/index.php?module=cartsection&action=order',
    //             method: 'POST',
    //             data: { selectedItems: selectedItems },
    //             success: function (response) {
    //                 // Handle the response from order.php
    //                 console.log("sent successfully with " + selectedItems);
    //             }
    //         });
    //     }
    // }
});