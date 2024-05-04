<?php
use backend\bus\CartsBUS;
use backend\bus\UserBUS;
use backend\bus\TokenLoginBUS;
use backend\services\session;
use backend\bus\OrdersBUS;

requireLogin();
$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
//Only customer can access this page:
if ($userModel->getRoleId() != 4) {
    //Echo a message then redirect to the user's homepage
    echo '<script>alert("You are not authorized to access this page!")</script>';
    redirect('?module=indexphp&action=userhomepage');
}

$cartListFromUser = CartsBUS::getInstance()->getModelByUserId($userModel->getId());

$orderList = OrdersBUS::getInstance()->getOrdersByUserId($userModel->getId());
?>

<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/home.js"></script>
    <?php layouts('header') ?>
</div>

<body>
    <h4 class="pt-3">Your recent orders:</h4>
    <div class="tab-pane fade" id="account-order-list">
        <!-- <div class="input-group mb-3" style="background-color: white; color: black;">
            <input type="text" class="form-control" placeholder="Search for an order" aria-label="Search for an order"
                aria-describedby="button-addon2" id="searchOrder">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="button-addon2">Search</button>
            </div>
        </div> -->
    </div>
    <div id="order-list-product">
        <table class="table table-striped col-12 align-middle table-borderless text-center table-secondary">
            <thead>
                <tr>
                    <th scope="col">Order Id</th>
                    <th scope="col">Purchase Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Price</th>
                    <th scope="col">View detail</details>
                    </th>
                </tr>
            </thead>
            <tbody id="productList">
                <?php
                usort($orderList, function ($a, $b) {
                    return strtotime($b->getOrderDate()) - strtotime($a->getOrderDate());
                });

                foreach ($orderList as $order) {
                    echo '<tr>';
                    echo '<td>' . $order->getId() . '</td>';
                    echo '<td>' . $order->getOrderDate() . '</td>';
                    echo '<td>' . $order->getStatus() . '</td>';
                    echo '<td>' . $order->getTotalAmount() . '</td>';
                    echo '<td> 
                        <a href="http://localhost/frontend/index.php?module=account&action=order-detail&orderId=' . $order->getId() . '">
                            <button class="btn btn-sm btn-primary view-button" data-order-id="' . $order->getId() . '">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                View
                            </button>
                        </a>
                    </td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/order_detail.js"></script>

    </div>
</body>