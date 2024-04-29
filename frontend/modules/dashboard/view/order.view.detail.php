<?php
use backend\bus\PaymentMethodsBUS;
use backend\bus\PaymentsBUS;
use backend\bus\ProductBUS;
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;
use backend\enums\RolesEnums;
use backend\services\session;
use backend\bus\OrderItemsBUS;
use backend\bus\OrdersBUS;

global $id;
$orderId = $_GET['customerOrderId'];
requireLogin();

$order = OrdersBUS::getInstance()->getModelById($orderId);
$userModel = UserBUS::getInstance()->getModelById($order->getUserId());

if ($order->getUserId() != $userModel->getId() && $userModel->getRoleId() == RolesEnums::CUSTOMER) {
    // The order doesn't belong to the user, return a 403 Forbidden status code
    http_response_code(403);
    echo 'You do not have permission to view this order.';
    exit;
} else {
    // Admin, manager, employee can view all orders:
    if (
        $userModel->getRoleId() != RolesEnums::CUSTOMER &&
        ($userModel->getRoleId() == RolesEnums::ADMIN ||
            $userModel->getRoleId() == RolesEnums::MANAGER ||
            $userModel->getRoleId() == RolesEnums::EMPLOYEE)
    ) {
        $orderId = $_GET['orderId'];
        $order = OrdersBUS::getInstance()->getModelById($orderId);
    }
}

$paymentMethod = PaymentsBUS::getInstance()->getModelByOrderId($order->getId());
$methodId = PaymentMethodsBUS::getInstance()->getModelById($paymentMethod->getMethodId())->getMethodName();
$orderItemsListBasedOnOrderFromUser = OrderItemsBUS::getInstance()->getOrderItemsListByOrderId($orderId);
include (__DIR__ . '/../inc/head.php');
?>

<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/home.js"></script>
</div>

<body>
    <section class="h-100 gradient-custom width:100%" style="height:150vh;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-10 col-xl-8">
                    <div class="card" style="border-radius: 10px;">
                        <div class="card-header px-2 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-muted mb-0">Order from: <span
                                    style="color: #a8729a;"><?php echo $userModel->getName() ?></span>!</h6>
                            <h6 class="text-muted mb-0">Back to <a href="?module=dashboard&view=order.view"
                                    style="color: #a8729a;">Customer Order List</a></h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <p class="lead fw-normal mb-0" style="color: #a8729a;">Receipt</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <php class="text-muted mb-0">Customer name: <?php echo $order->getCustomerName() ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <php class="text-muted mb-0">Address: <?php echo $order->getCustomerAddress() ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <php class="text-muted mb-0">Phone number: <?php echo $order->getCustomerPhone() ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <php class="text-muted mb-0">Payment method: <?php echo $methodId ?></p>
                            </div>
                            <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;">
                            <div class="row d-flex align-items-center">
                                <div class="col-md-2">
                                    <p class="text-muted mb-0 small">Track Order</p>
                                </div>
                                <?php
                                $orderStatus = $order->getStatus();

                                $statusColor = '';
                                $statusText = '';
                                $progressPercentage = '';

                                switch ($orderStatus) {
                                    case 'pending':
                                        $statusColor = '#a8729a';
                                        $statusText = 'Pending';
                                        $progressPercentage = '20%';
                                        break;
                                    case 'accepted':
                                        $statusColor = '#a8729a';
                                        $statusText = 'Accepted';
                                        $progressPercentage = '40%';
                                        break;

                                    case 'shipping':
                                        $statusColor = '#a8729a';
                                        $statusText = 'Out for delivery';
                                        $progressPercentage = '65%';
                                        break;

                                    case 'completed':
                                        $statusColor = '#a8729a';
                                        $statusText = 'Completed';
                                        $progressPercentage = '100%';
                                        break;

                                    case 'cancelled':
                                        $statusColor = '#a8729a';
                                        $statusText = 'Cancelled';
                                        $progressPercentage = '100%';
                                        break;
                                }

                                echo '<div class="col-md-10">
                                <div class="progress" style="height: 6px; border-radius: 16px;">
                                    <div class="progress-bar" role="progressbar" style="width: ' . $progressPercentage . '; border-radius: 16px; background-color: ' . $statusColor . ';" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-around mb-1">
                                    <p class="text-muted mt-1 mb-0 small ms-xl-5">' . $statusText . '</p>
                                </div>
                            </div>';
                                ?>
                            </div>
                            <?php
                            echo '<div style="height: 300px; overflow-y: auto;">'; // Adjust the height as needed
                            foreach ($orderItemsListBasedOnOrderFromUser as $orderItem) {
                                echo '<div class="card shadow-0 border mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <img src="' . ProductBUS::getInstance()->getModelById($orderItem->getProductId())->getImage() . '" class="img-fluid" alt="Shoes">
                </div>
                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                    <p class="text-muted mb-0">' . ProductBUS::getInstance()->getModelById($orderItem->getProductId())->getName() . '</p>
                </div>
                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                    <p class="text-muted mb-0 small">Size: ' . $orderItem->getSizeId() . '</p>
                </div>
                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                    <p class="text-muted mb-0 small">Qty: ' . $orderItem->getQuantity() . '</p>
                </div>
                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                    <p class="text-muted mb-0 small">$ ' . ProductBUS::getInstance()->getModelById($orderItem->getProductId())->getPrice() . '</p>
                </div>
            </div>
        </div>
    </div>';
                            }
                            echo '</div>';
                            ?>

                            <div class="d-flex justify-content-between pt-2">
                                <p class="fw-bold mb-0">Order Details</p>
                                <p class="text-muted mb-0"><span class="fw-bold me-4">Total</span> $<?php
                                $totalPrice = 0;
                                foreach ($orderItemsListBasedOnOrderFromUser as $orderItem) {
                                    $totalPrice += $orderItem->getPrice();
                                }
                                echo $totalPrice;
                                ?></p>
                            </div>

                            <div class="d-flex justify-content-between pt-2">
                                <p class="text-muted mb-0">Invoice Number : <?php echo $order->getId() ?> </p>
                                <p class="text-muted mb-0"><span class="fw-bold me-4">Discount </span>
                                    <?php
                                    $discountedPrice = $totalPrice - $order->getTotalAmount();
                                    echo '$' . $discountedPrice;
                                    ?>
                                </p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <php class="text-muted mb-0">Invoice Date : <?php echo $order->getOrderDate() ?></p>
                            </div>

                        </div>
                        <div class="card-footer border-0 px-2 py-3"
                            style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                            <h5 class="d-flex align-items-center justify-content-end text-white text-uppercase mb-0">
                                Total paid: <span class="h4 mb-0 ms-2">$<?php echo $order->getTotalAmount() ?></span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>