<?php
use backend\bus\PaymentMethodsBUS;
use backend\bus\PaymentsBUS;

$title = 'Orders';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission(3)) {
    die('Access denied');
}

include (__DIR__ . '/../inc/head.php');
include (__DIR__ . '/../inc/app/app.php');

// Namespace
use backend\bus\OrdersBUS;
use backend\bus\OrderItemsBUS;
use backend\enums\OrderStatusEnums;

$orderList = OrdersBUS::getInstance()->getAllModels();
$orderListItem = OrderItemsBUS::getInstance();
function showOrder($order)
{
    echo '<tr class="align-middle">';
    echo '<td class="order" id="orderId" data-order-id="' . $order->getId() . '">';
    echo $order->getId();
    echo '</td>';
    echo '<td>' . $order->getUserId() . '</td>';
    echo '<td>' . $order->getCustomerName() . '</td>';
    echo '<td>' . $order->getOrderDate() . '</td>';
    echo '<td>' . $order->getCustomerAddress() . '</td>';
    $paymentData = PaymentsBUS::getInstance()->getModelByOrderId($order->getId());
    $paymentMethod = PaymentMethodsBUS::getInstance()->getModelById($paymentData->getMethodId());
    echo '<td>' . $paymentMethod->getMethodName() . '</td>';
    echo '<td>' . $order->getTotalAmount() . '</td>';
    echo '<td class="text-center">';
    echo '<select class="form-control" name="status" id="orderStatus" onchange="updateOrderStatus(' . $order->getId() . ', this.value)">';
    echo '<option value="PENDING"' . (strtoupper($order->getStatus()) == OrderStatusEnums::PENDING ? ' selected' : '') . '>Pending</option>';
    echo '<option value="SHIPPING"' . (strtoupper($order->getStatus()) == OrderStatusEnums::SHIPPING ? ' selected' : '') . '>Shipping</option>';
    echo '<option value="COMPLETED"' . (strtoupper($order->getStatus()) == OrderStatusEnums::COMPLETED ? ' selected' : '') . '>Completed</option>';
    echo '<option value="CANCELED"' . (strtoupper($order->getStatus()) == OrderStatusEnums::CANCELED ? ' selected' : '') . '>Canceled</option>';
    echo '<option value="ACCEPTED"' . (strtoupper($order->getStatus()) == OrderStatusEnums::ACCEPTED ? ' selected' : '') . '>Accepted</option>';
    echo '</select>';
    echo '</td>';
    echo '<td class="text-center">';
    echo '<a href="http://localhost/frontend/index.php?module=dashboard&view=order.view.detail&customerOrderId=' . $order->getId() . '">';
    echo '<button class="btn btn-sm btn-primary view-button" data-order-id="' . $order->getId() . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">';
    echo '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>';
    echo '<circle cx="12" cy="12" r="3"></circle>';
    echo '</svg>';
    echo '</button>';
    echo '</a>';
    echo '</td>';
    echo '</tr>';
}
?>



<body>
    <!-- HEADER -->
    <?php include (__DIR__ . '/../inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR MENU -->
            <?php include (__DIR__ . '/../inc/sidebar.php'); ?>

            <!-- MAIN -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                </div>

                <!-- SEARCH BAR -->
                <div class="container-lg d-flex justify-content-start m-0">
                    <form action="" method="POST" class="m-0 col-lg-6">
                        <div class="input-group">
                            <input type="text" id="orderSearchBarId" name="orderSearchBarName"
                                class="searchInput form-control my-2 rounded-0" placeholder="Search anything here..."
                                style="width: 100%;">
                            <input type="date" id="startDate" name="startDate" class="form-control rounded-0"
                                style="width: 120px;" placeholder="Start Date">
                            <input type="date" id="endDate" name="endDate" class="form-control rounded-0"
                                style="width: 120px;" placeholder="End Date">
                            <button type="submit" class="btn btn-sm btn-primary align-middle padx-0 pady-0"
                                id="searchBtnId" name="searchBtnName">
                                <span data-feather="search"></span>
                            </button>
                        </div>
                        <select class="form-control rounded-0 my-2" name="statusSearchName" id="orderStatusSearchId">
                            <option value="NONE">---</option>
                            <option value="pending">Pending</option>
                            <option value="shipping">Shipping</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                            <option value="accepted">Accepted</option>
                        </select>
                    </form>
                </div>

                <!-- BODY DATABASE -->
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
                                <th scope="col" class="col-2">Payment method</th>
                                <th scope="col" class="col-1">Final Price</th>
                                <th scope="col" class="col-1 text-center">Status</th>
                                <th scope="col" class="col-1 text-center">Info</th>
                            </tr>
                        </thead>
                        <?php
                        if (!isPost() || (isPost() && !isset($_POST['searchBtnName']))) {
                            if (count($orderList) > 0) {
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                                // Split the order list into chunks of 12
                                $orderChunks = array_chunk($orderList, 12);

                                // Get the orders for the current page
                                $ordersForCurrentPage = $orderChunks[$page - 1];

                                foreach ($ordersForCurrentPage as $order): ?>
                                    <?= showOrder($order); ?>
                                <?php endforeach;


                                // Calculate the total number of pages
                                $totalPages = count($orderChunks);

                                echo "<nav aria-label='Page navigation example'>";
                                echo "<ul class='pagination justify-content-center'>";

                                // Add previous button
                                if ($page > 1) {
                                    echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=order.view&page=" . ($page - 1) . "'>Previous</a></li>";
                                }

                                for ($i = 1; $i <= $totalPages; $i++) {
                                    // Highlight the current page
                                    if ($i == $page) {
                                        echo "<li class='page-item active'><a class='page-link' href='?module=dashboard&view=order.view&page=$i'>$i</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=order.view&page=$i'>$i</a></li>";
                                    }
                                }

                                // Add next button
                                if ($page < $totalPages) {
                                    echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=order.view&page=" . ($page + 1) . "'>Next</a></li>";
                                }

                                echo "</ul>";
                                echo "</nav>";
                            }
                        }

                        ?>

                        <!--Handling search bar-->
                        <?php
                        if (isPost()) {
                            $filterAll = filter();
                            if (isset($_POST['searchBtnName'])) {
                                $searchValue = $_POST['orderSearchBarName'];
                                $startDate = $_POST['startDate'];
                                $endDate = $_POST['endDate'];
                                $statusSearch = $_POST['statusSearchName'];

                                //Initiate the list:
                                $searchOrderList = array();
                                //If both search bar and date picker and status are empty, show all orders:
                                if (empty($searchValue) && trim($searchValue) == '' && empty($startDate) && empty($endDate) && $statusSearch == 'NONE') {
                                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                    echo "Please input at least one of the search bar or date picker or shipping status to search!";
                                    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                    echo "</div>";
                                    $searchOrderList = $orderList;
                                } else {
                                    // Search by search term only if it's not empty and others are empty:
                                    if (!empty($searchValue) && trim($searchValue) != '' && empty($startDate) && empty($endDate) && $statusSearch == 'NONE') {
                                        $searchOrderList = OrdersBUS::getInstance()->searchModel($searchValue, ['id', 'customer_name', 'customer_phone', 'customer_address', 'total_amount']);
                                    }

                                    // Search by both start date and end date only if it's not empty
                                    if (!empty($startDate) && !empty($endDate) && empty($searchValue) && trim($searchValue) == '') {
                                        $searchOrderList = OrdersBUS::getInstance()->searchBetweenDate($startDate, $endDate);
                                    }

                                    // Search by start date only:
                                    if (!empty($startDate) && empty($endDate) && empty($searchValue) && trim($searchValue) == '') {
                                        $searchOrderList = OrdersBUS::getInstance()->searchAfterDate($startDate);
                                    }

                                    //Search by end date only:
                                    if (empty($startDate) && !empty($endDate) && empty($searchValue) && trim($searchValue) == '') {
                                        $searchOrderList = OrdersBUS::getInstance()->searchBeforeDate($endDate);
                                    }

                                    //Search by status only if it's not empty
                                    if ($statusSearch != 'NONE' && empty($searchValue) && trim($searchValue) == '' && empty($startDate) && empty($endDate)) {
                                        $searchOrderList = OrdersBUS::getInstance()->getOrdersByStatus($statusSearch);
                                    }

                                    //Search by search term and date if both are not empty, date could be start date / end date:
                                    if (!empty($searchValue) && trim($searchValue) != '' && (!empty($startDate) || !empty($endDate))) {
                                        if (!empty($searchValue) && trim($searchValue) != '' && (!empty($startDate) || !empty($endDate))) {
                                            $searchOrderList = OrdersBUS::getInstance()->searchModel($searchValue, ['id', 'customer_name', 'customer_phone', 'customer_address', 'total_amount']);
                                            if (!empty($startDate) && !empty($endDate)) {
                                                $searchOrderList = array_filter($searchOrderList, function ($order) use ($startDate, $endDate) {
                                                    $orderDate = $order->getOrderDate();
                                                    return $orderDate >= $startDate && $orderDate <= $endDate;
                                                });
                                            }
                                            if (!empty($startDate) && empty($endDate)) {
                                                $searchOrderList = array_filter($searchOrderList, function ($order) use ($startDate) {
                                                    $orderDate = $order->getOrderDate();
                                                    return $orderDate >= $startDate;
                                                });
                                            }
                                            if (empty($startDate) && !empty($endDate)) {
                                                $searchOrderList = array_filter($searchOrderList, function ($order) use ($endDate) {
                                                    $orderDate = $order->getOrderDate();
                                                    return $orderDate <= $endDate;
                                                });
                                            }
                                        }
                                    }

                                    //Search by search term and status only if both are not empty:
                                    if (!empty($searchValue) && trim($searchValue) != '' && $statusSearch != 'NONE' && empty($startDate) && empty($endDate)) {
                                        $searchOrderList = OrdersBUS::getInstance()->searchModel($searchValue, ['id', 'customer_name', 'customer_phone', 'customer_address', 'total_amount']);
                                        $searchOrderList = array_filter($searchOrderList, function ($order) use ($statusSearch) {
                                            return $order->getStatus() == $statusSearch;
                                        });
                                    }

                                    //Search by date and status only if both are not empty, date could be start date / end date:
                                    if (empty($searchValue) && trim($searchValue) == '' && $statusSearch != 'NONE' && (!empty($startDate) || !empty($endDate))) {
                                        $searchOrderList = OrdersBUS::getInstance()->getOrdersByStatus($statusSearch);
                                        if (!empty($startDate) && !empty($endDate)) {
                                            $searchOrderList = array_filter($searchOrderList, function ($order) use ($startDate, $endDate) {
                                                $orderDate = $order->getOrderDate();
                                                return $orderDate >= $startDate && $orderDate <= $endDate;
                                            });
                                        }
                                        if (!empty($startDate) && empty($endDate)) {
                                            $searchOrderList = array_filter($searchOrderList, function ($order) use ($startDate) {
                                                $orderDate = $order->getOrderDate();
                                                return $orderDate >= $startDate;
                                            });
                                        }
                                        if (empty($startDate) && !empty($endDate)) {
                                            $searchOrderList = array_filter($searchOrderList, function ($order) use ($endDate) {
                                                $orderDate = $order->getOrderDate();
                                                return $orderDate <= $endDate;
                                            });
                                        }
                                    }

                                    //Search both by search term, date and status if all are not empty, date could be start date / end date:
                                    if (!empty($searchValue) && trim($searchValue) != '' && $statusSearch != 'NONE' && (!empty($startDate) || !empty($endDate))) {
                                        $searchOrderList = OrdersBUS::getInstance()->searchModel($searchValue, ['id', 'customer_name', 'customer_phone', 'customer_address', 'total_amount']);
                                        $searchOrderList = array_filter($searchOrderList, function ($order) use ($statusSearch) {
                                            return $order->getStatus() == $statusSearch;
                                        });
                                        if (!empty($startDate) && !empty($endDate)) {
                                            $searchOrderList = array_filter($searchOrderList, function ($order) use ($startDate, $endDate) {
                                                $orderDate = $order->getOrderDate();
                                                return $orderDate >= $startDate && $orderDate <= $endDate;
                                            });
                                        }
                                        if (!empty($startDate) && empty($endDate)) {
                                            $searchOrderList = array_filter($searchOrderList, function ($order) use ($startDate) {
                                                $orderDate = $order->getOrderDate();
                                                return $orderDate >= $startDate;
                                            });
                                        }
                                        if (empty($startDate) && !empty($endDate)) {
                                            $searchOrderList = array_filter($searchOrderList, function ($order) use ($endDate) {
                                                $orderDate = $order->getOrderDate();
                                                return $orderDate <= $endDate;
                                            });
                                        }
                                    }
                                }
                                if (count($searchOrderList) == 0) {
                                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                    echo "No result found!";
                                    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                    echo "</div>";
                                }

                                //Display the search result:
                                foreach ($searchOrderList as $order) {
                                    showOrder($order);
                                }
                            }
                        }

                        ?>

                        <?php
                        if (isPost()) {
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
            </main>

            <?php include (__DIR__ . '/../inc/app/app.php'); ?>
</body>

</html>