<div class="table-responsive">
    <table class="table table-striped table-hover">
        <!-- HEADER TABLE -->
        <thead>
            <tr>
                <th scope="col" class="col-1">ID Order</th>
                <th scope="col" class="col-1">User ID</th>
                <th scope="col" class="col-2">Customer Name</th>
                <th scope="col" class="col-2">Order Date</th>
                <th scope="col" class="col-2">Address</th>
                <th scope="col" class="col-2">Final Price</th>
                <th scope="col" class="col-1 text-center">Status</th>
                <th scope="col" class="col-1 text-center">Info</th>
            </tr>
        </thead>

        <?php
        use backend\bus\OrdersBUS;
        use backend\enums\OrderStatusEnums;

        ?>
        <?php foreach ($orderList as $order):
            ?>
            <tbody>
                <!-- TESTING STATIC -->
                <tr class="align-middle">
                    <td class="order" id="orderId" data-order-id="<?= $order->getId() ?>"><?= $order->getId() ?></td>
                    <td><?= $order->getUserId() ?></td>
                    <td><?= $order->getCustomerName() ?></td>
                    <td><?= $order->getOrderDate() ?></td>
                    <td><?= $order->getCustomerAddress() ?></td>
                    <td><?= $order->getTotalAmount() ?></td>
                    <td class="text-center">
                        <select class="form-control" name="status" id="orderStatus"
                            onchange="updateOrderStatus(<?= $order->getId() ?>, this.value)">
                            <option value="PENDING" <?php if (strtoupper($order->getStatus()) == OrderStatusEnums::PENDING)
                                echo 'selected'; ?>>Pending</option>
                            <option value="SHIPPING" <?php if (strtoupper($order->getStatus()) == OrderStatusEnums::SHIPPING)
                                echo 'selected'; ?>>Shipping</option>
                            <option value="COMPLETED" <?php if (strtoupper($order->getStatus()) == OrderStatusEnums::COMPLETED)
                                echo 'selected'; ?>>Completed
                            </option>
                            <option value="CANCELED" <?php if (strtoupper($order->getStatus()) == OrderStatusEnums::CANCELED)
                                echo 'selected'; ?>>Canceled
                            </option>
                            <option value="ACCEPTED" <?php if (strtoupper($order->getStatus()) == OrderStatusEnums::ACCEPTED)
                                echo 'selected'; ?>>Accepted</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <a
                            href="http://localhost/frontend/index.php?module=dashboard&view=order.view.detail&customerOrderId=<?= $order->getId(); ?>">
                            <button class="btn btn-sm btn-primary view-button" data-order-id="<?= $order->getId() ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-eye">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                    </path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </a>
                        </button>
                    </td>
                </tr>
            </tbody>
        <?php endforeach; ?>
        <?php
        if (isPost()) {
            error_log(print_r($_POST, true)); // Add this line to log the $_POST variable
            if (isset($_POST['orderId']) && isset($_POST['status'])) {
                $orderId = $_POST['orderId'];
                error_log($orderId);
                $status = $_POST['status'];
                error_log($status);
                $order = OrdersBUS::getInstance()->getModelById($orderId);
                $order->setStatus($status);
                OrdersBUS::getInstance()->updateModel($order);
                OrdersBUS::getInstance()->refreshData();
            }
        }
        ?>
    </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function updateOrderStatus(orderId, status) {
            $.ajax({
                type: 'POST',
                url: 'http://localhost/frontend/index.php?module=dashboard&view=order.view',
                data: {
                    orderId: orderId,
                    status: status
                },
                success: function (data) {
                    location.reload();
                }
            });
        }
    </script>
</div>