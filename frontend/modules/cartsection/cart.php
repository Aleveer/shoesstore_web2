<?php
use backend\bus\ProductBUS;
use backend\bus\CartsBUS;
use backend\bus\UserBUS;
use backend\bus\TokenLoginBUS;
use backend\services\session;
use backend\bus\SizeItemsBUS;
use backend\bus\SizeBUS;

requireLogin();
CartsBUS::getInstance()->refreshData();
$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
$cartList = CartsBUS::getInstance()->getModelByUserId($userModel->getId());

if ($userModel->getRoleId() == 1 || $userModel->getRoleId() == 2 || $userModel->getRoleId() == 3) {
    echo '<script>';
    echo 'alert("You don\'t have access to this page!")';
    echo '</script>';
    echo '<script>';
    echo 'window.location.href = "?module=indexphp&action=product"';
    echo '</script>';
    die();
}
?>

<?php
if (isPost()) {
    if (isset($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
        $cartId = $_POST['cartId'];
        $cart = CartsBUS::getInstance()->getModelById($cartId);
        $cart->setQuantity($quantity);
        CartsBUS::getInstance()->updateModel($cart);
        CartsBUS::getInstance()->refreshData();
    } else if (isset($_POST['delete'])) {
        $cartId = $_POST['cartId'];
        CartsBUS::getInstance()->deleteModel($cartId);
        CartsBUS::getInstance()->refreshData();
        //Refresh the page:
        echo '<script>';
        echo 'window.location.href = "?module=cartsection&action=cart"';
        echo '</script>';
    }
}
?>
<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php layouts("header") ?>
</div>

<body>
    <div id="cart">
        <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/cart_handler.js"></script>
        <div class="cart-header">
            <div class="cart-header-wrapper">
                <h1>Cart</h1>
                <div class="cart-info">
                    <div class="text-group">
                        <label class="cart-header-label" for="cart-total">Total:</label>
                        <div id="cart-total">
                            <?php
                            $total = 0;
                            foreach ($cartList as $cart) {
                                $total += $cart->getQuantity() * ProductBUS::getInstance()->getModelById($cart->getProductId())->getPrice();
                            }
                            //Split the $total by 3 digits like: 3.000.000:
                            $total = number_format($total, 0, '', '.');
                            echo $total;
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if (count($cartList) > 0) {
                    echo '<form action="?module=cartsection&action=order" method="POST">';
                    echo '<button id="order-continue" type="submit" class="checkout">Continue</button>';
                    echo '</form>';
                } else {
                    echo '<button id="order-continue" type="button" class="checkout" style="display: none;">';
                    echo '</button>';
                }
                ?>
            </div>
        </div>

        <div class="cart-content">
            <div class="cart-content-header">
                <div class="header-wrapper">
                    <p class="cart-product">Product</p>
                    <p class="cart-size">Size</p>
                    <p class="cart-price">Price</p>
                    <p class="cart-quantity">Quantity</p>
                    <p class="cart-total">Total</p>
                    <p class="cart-action">Action</p>
                </div>
            </div>
            <div class="cart-content-body">
                <?php
                foreach ($cartList as $cart) {
                    $product = ProductBUS::getInstance()->getModelById($cart->getProductId());
                    $sizeItems = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($cart->getSizeId(), $cart->getProductId());
                    if ($product != null && $sizeItems != null) {
                        if ($sizeItems->getQuantity() == 0 || $product->getStatus() == 'inactive') {
                            //Possible case: The product is deleted by the admin / out of stock / unavailable
                            //If the product is not found, delete that product from the cart and refresh the data:
                            //Possibly tell user that the product is out of stock / unavailable:
                            echo '<script>';
                            echo 'alert("A product you have in cart: ' . $product->getName() . ' is not available or out of stock!")';
                            echo '</script>';
                            CartsBUS::getInstance()->deleteModel($cart->getId());
                            CartsBUS::getInstance()->refreshData();

                            //Refresh a page?
                            echo '<script>';
                            echo 'window.location.href = "?module=cartsection&action=cart"';
                            echo '</script>';
                            break;
                        }
                    } else {
                        //If the product is not found, delete that product from the cart and refresh the data:
                        echo '<script>';
                        echo 'alert("A product you have in cart is not available or out of stock!")';
                        echo '</script>';
                        CartsBUS::getInstance()->deleteModel($cart->getId());
                        CartsBUS::getInstance()->refreshData();

                        //Refresh a page?
                        echo '<script>';
                        echo 'window.location.href = "?module=cartsection&action=cart"';
                        echo '</script>';
                        break;
                    }
                    $sizeItems = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($cart->getSizeId(), $product->getId());
                    $size = SizeBUS::getInstance()->getModelById($cart->getSizeId());
                    echo '<form method="POST" action="?module=cartsection&action=cart">';
                    echo '<div class="item-container" name="itemContainer">';
                    echo '<div class="cart-item cart-product" name="cartProduct">';
                    echo '<img src="' . $product->getImage() . '" alt="" name="productImage">';
                    echo '<p name="productName">' . $product->getName() . '</p>';
                    echo '</div>';
                    echo '<div class="cart-item cart-size" name="cartSize">';
                    $sizeName = $size->getName();
                    $sizeNumber = preg_replace('/[^0-9]/', '', $sizeName);
                    echo '<p name="sizeNumber">' . $sizeNumber . '</p>';
                    echo '</div>';
                    echo '<div class="cart-item cart-price" name="cartPrice">' . $product->getPrice() . '</div>';
                    echo '<div class="cart-item cart-quantity" name="cartQuantity">';
                    echo '<button class="minus-btn" name="minusBtn">-</button>';
                    echo '<input type="text" class="productQuantity" value="' . $cart->getQuantity() . '" disabled name="productQuantity" data-max-quantity="' . $sizeItems->getQuantity() . '">';
                    echo '<button class="plus-btn" name="plusBtn">+</button>';
                    echo '</div>';
                    echo '<div class="cart-item cart-total" name="cartTotal">' . $product->getPrice() * $cart->getQuantity() . '</div>';
                    echo '<div class="cart-item cart-action" name="cartAction"><i class="fa-solid fa-trash" style="color: #ff0700a6;" name="trashIcon"></i></div>';
                    echo '</div>';
                    echo '<input type="hidden" name="cartId" value="' . $cart->getId() . '">';
                    echo '</form>';
                }
                ?>
                <?php
                if (count($cartList) == 0) {
                    echo '<div class="no-product">';
                    echo '<p>This cart is empty. Please add something :3</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
<!--Footer-->
<div id="footer" style="margin-top: 15rem">
    <?php layouts('footer') ?>
</div>