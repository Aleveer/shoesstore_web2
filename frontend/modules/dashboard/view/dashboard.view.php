<?php
ob_start();

use backend\bus\OrdersBUS;

$title = 'Dashboard';

if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission(5)) {
    die('Access denied');
}

include (__DIR__ . '/../inc/head.php');

$thongKeList = OrdersBUS::getInstance()->filterByDateRange();

function displayThongKe($thongKe, $index)
{
    echo '
        <tr class="align-middle">
            <th scope="col">' . $index . '</th>
            <th scope="col">' . $thongKe->getUserId() . '</th>
            <th scope="col">' . $thongKe->getCustomerName() . '</th>
            <th scope="col">' . $thongKe->getTotalPurchaseAmount() . '</th>
            <th scope="col"><button class="seeDetailBtn" id="' . $thongKe->getUserId() . '"><i class="fas fa-eye"></i></button></th>
        </tr>
        ';
}

?>
<html>

<body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- HEADER -->
    <?php include (__DIR__ . '/../inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR MENU -->
            <?php include (__DIR__ . '/../inc/sidebar.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">

                        <!-- Function Button -->
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>

                        <!-- Drop Down Menu -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <span data-feather="calendar"></span>
                                This week
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Last Month</a></li>
                                <li><a class="dropdown-item" href="#">Last Year</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-evenly">
                    <div class="col-6">
                        <h3>Revenue</h3>
                        <hr>
                        <canvas id="Revenue_Income"></canvas>
                    </div>

                    <div class="col-6">
                        <h3>Total Login</h3>
                        <hr>
                        <canvas id="Total_Login"></canvas>
                    </div>
                </div>

                <h2>Top Customer</h2>
                <div class="container-lg d-flex justify-content-start m-0">
                    <td colspan="2">
                        <div class="purchased-sort-by-date-wrapper row">
                            <div class="purchased-sort-input col">
                                <input type="date" id="purchased-date-from" name="dateFrom" class="form-control"
                                    style="width: 150px;" placeholder="Start Date">
                            </div>
                            <div class="purchased-sort-input col">
                                <input type="date" id="purchased-date-to" name="dateTo" class="form-control"
                                    style="width: 150px;" placeholder="End Date">
                            </div>
                            <div class="search col">
                                <button type="submit"
                                    class="filter_by_date_btn btn btn-sm btn-primary align-middle padx-0 pady-0">
                                    <span data-feather="search"></span>
                                </button>
                            </div>
                        </div>
                    </td>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <!-- HEADER TABLE -->
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Customer Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Order List</th>
                            </tr>
                        </thead>

                        <!-- BODY DATABASE -->
                        <tbody class="showAllThongKe">
                            <?php
                            foreach ($thongKeList as $index => $thongKe) {
                                displayThongKe($thongKe, $index + 1);
                            }
                            if (isPost()) {
                                $filterAll = filter();
                                if (isset($filterAll['dateFrom']) && isset($filterAll['dateTo'])) {
                                    $thongKeList = OrdersBUS::getInstance()->filterByDateRange($filterAll['dateFrom'], $filterAll['dateTo']);
                                    $thongKeListArray = array_map(function ($thongKe) {
                                        return $thongKe->toArray();
                                    }, $thongKeList);
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['thongKeList' => $thongKeListArray]);
                                    exit;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- pop up detail thong ke -->
    <div class="model_detail">
        <div class="content-wrapper">
            <div style="width: 100%;" class="table-responsive">
                <table class="table table-striped table-hover ">
                    <!-- HEADER TABLE -->
                    <thead>
                        <tr>
                            <th scope="col" class="col-2">Order Id</th>
                            <th scope="col" class="col-2">Order Date</th>
                            <th scope="col" class="col-2">Customer Name</th>
                            <th scope="col" class="col-1">Final Price</th>
                            <th scope="col" class="col-1 text-center">Status</th>
                            <th scope="col" class="col-1 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="showOrderOfUser">
                        <?php
                        if (isPost()) {
                            $filterAll = filter();
                            if (isset($filterAll['userId'])) {
                                $orderList = OrdersBUS::getInstance()->getOrdersByUserId($filterAll['userId']);
                                $orderListArray = array_map(function ($order) {
                                    return $order->toArray();
                                }, $orderList);
                                ob_end_clean();
                                header('Content-Type: application/json');
                                echo json_encode(['orderList' => $orderListArray]);
                                exit;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    include (__DIR__ . '/../inc/app/app.php');
    include (__DIR__ . '/../inc/chart.php')
        ?>
</body>

</html>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        let filterByDateBtn = document.querySelector('.filter_by_date_btn');
        let modelDetail = document.querySelector('.model_detail');
        let contentWrapper = document.querySelector('.content-wrapper');

        // Function to add click event to see detail buttons
        function addSeeDetailButtonClickEvent() {
            let seeDetailButtons = document.querySelectorAll('.seeDetailBtn');
            seeDetailButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    // Get userId from button's id
                    let userId = button.getAttribute('id');
                    // Call function to get order list by user id
                    getOrderListByUserId(userId);
                    // Show detail modal
                    modelDetail.style.display = 'flex';
                });
            });
        }

        // Initial adding of click event to see detail buttons
        addSeeDetailButtonClickEvent();

        // Click event for filter by date button
        filterByDateBtn.addEventListener('click', function () {
            var dateFrom = document.getElementById('purchased-date-from').value;
            var dateTo = document.getElementById('purchased-date-to').value;

            fetch('http://localhost/frontend/?module=dashboard&view=dashboard.view', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'dateFrom=' + dateFrom + '&dateTo=' + dateTo,
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var tableBody = document.querySelector('.showAllThongKe');
                    tableBody.innerHTML = htmlThongKeList(data.thongKeList);
                    // Add click event to see detail buttons after re-rendering
                    addSeeDetailButtonClickEvent();
                });
        });

        // Function to generate HTML for thong ke list
        function htmlThongKeList(thongKeList) {
            let htmlInTable = "";
            thongKeList.forEach(function (thongKe, index) {
                htmlInTable += `
            <tr class="align-middle">
                <th scope="col">${index + 1}</th>
                <th scope="col">${thongKe.userId}</th>
                <th scope="col">${thongKe.customerName}</th>
                <th scope="col">${thongKe.totalPurchaseAmount}</th>
                <th scope="col"><button class="seeDetailBtn" id="${thongKe.userId}">Xem chi tiết</button></th>
            </tr>`;
            });
            return htmlInTable;
        }

        // Function to get order list by user id
        function getOrderListByUserId(userId) {
            fetch("http://localhost/frontend/?module=dashboard&view=dashboard.view", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'userId=' + userId,
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    console.log(data);
                    var orderTableBody = document.querySelector('.showOrderOfUser');
                    orderTableBody.innerHTML = htmlOrderList(data.orderList);
                    addSeeDetailOrderButtonClickEvent();
                });
        }

        function addSeeDetailOrderButtonClickEvent() {
            let seeOrderDetailButtons = document.querySelectorAll('.orderDetailBtn');
            seeOrderDetailButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    // Get userId from button's id
                    let orderId = button.getAttribute('data-order-id');
                    window.location.href = 'http://localhost/frontend/index.php?module=dashboard&view=order.view.detail&customerOrderId=' + orderId;
                });
            });
        }

        // Function to generate HTML for order list
        function htmlOrderList(orderList) {
            let htmlInTable = "";
            if (orderList) {
                orderList.forEach(function (order) {
                    htmlInTable += `
                        <tr class="align-middle">
                            <td>${order.id}</td>
                            <td>${order.orderDate}</td>
                            <td>${order.customerName}</td>
                            <td>${order.totalAmount}</td>
                            <td class="text-center">${order.status}</td>
                            <td class="text-center">
                                <button class="orderDetailBtn" data-order-id="${order.id}">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>`;
                });
            }
            return htmlInTable;
        }

        // Click event to hide detail modal when clicked outside
        modelDetail.addEventListener('click', function (e) {
            modelDetail.style.display = 'none';
        });

        // Click event to stop propagation when clicked inside content wrapper
        contentWrapper.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
</script>