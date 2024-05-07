<?php
ob_start();

use backend\bus\CategoriesBUS;
use backend\bus\OrdersBUS;

$title = 'Dashboard';

if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}


include (__DIR__ . '/../inc/head.php');

$thongKeListKH = OrdersBUS::getInstance()->filterByDateRangeKH();
$thongKeListKHArray = array_map(function ($thongKe) {
    return $thongKe->toArray();
}, $thongKeListKH);
$thongKeListKHJSON = json_encode($thongKeListKHArray);


function displayThongKeKH($thongKe, $index)
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<body>
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
                    <!-- <div class="btn-toolbar mb-2 mb-md-0">

                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>

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
                    </div> -->
                </div>

                <div class="row justify-content-evenly">
                    <div class="col-6">
                        <h1 style="text-align:center;">Bar Chart</h1>
                        <canvas id="thongKeCotChart"></canvas>
                        <hr>
                        <!-- <h1 style="text-align:center;">Biểu đồ tròn</h1>
                        <canvas id="thongKeTronChart"></canvas> -->
                    </div>

                    <div class="col-6">
                        <h1 style="text-align:center;">Line Chart</h1>
                        <canvas id="thongKeDuongChart"></canvas>
                        <hr>
                    </div>
                </div>

                <h2>Statistic</h2>
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
                <br>
                <select id="filterOption">
                    <option value="khachHang">Customer</option>
                    <option value="sanPham">Product</option>
                </select>
                <div><br></div>
                <select style="display:none;" id="filterCategoryOption">
                    <?php
                    echo '<option value="-1">Default</option>';
                    $cateList = CategoriesBUS::getInstance()->getAllModels();
                    foreach ($cateList as $category) {
                        $categoryName = $category->getName();
                        $categoryId = $category->getId();
                        echo "<option value='$categoryId'>$categoryName</option>";
                    }
                    ?>
                </select>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <!-- HEADER TABLE -->
                        <thead class="header_thong_ke">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">User Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>

                        <!-- BODY DATABASE -->
                        <tbody class="showAllThongKe">
                            <?php
                            foreach ($thongKeListKH as $index => $thongKe) {
                                displayThongKeKH($thongKe, $index + 1);
                            }
                            if (isPost()) {
                                $filterAll = filter();
                                echo '<pre>';
                                print_r($filterAll);
                                echo '</pre>';
                                if (isset($filterAll['dateFrom']) && isset($filterAll['dateTo'])) {
                                    if (isset($filterAll['khachhang'])) {
                                        //Add +1 day to dateTo:
                                        $dateTo = date('Y-m-d', strtotime($filterAll['dateTo'] . ' +1 day'));
                                        $thongKeListKH = OrdersBUS::getInstance()->filterByDateRangeKH($filterAll['dateFrom'], $dateTo);
                                        $thongKeListKHArray = array_map(function ($thongKe) {
                                            return $thongKe->toArray();
                                        }, $thongKeListKH);
                                        ob_end_clean();
                                        header('Content-Type: application/json');
                                        echo json_encode(['thongKeListKH' => $thongKeListKHArray]);
                                        exit;
                                    }
                                    if (isset($filterAll['sanpham'])) {
                                        $category = $filterAll['category'];
                                        if ($category == -1) {
                                            //Add +1 day to dateTo:
                                            $dateTo = date('Y-m-d', strtotime($filterAll['dateTo'] . ' +1 day'));
                                            $thongKeListSP = OrdersBUS::getInstance()->filterByDateRangeSP($filterAll['dateFrom'], $dateTo);
                                            $thongKeListSPArray = array_map(function ($thongKe) {
                                                return $thongKe->toArray();
                                            }, $thongKeListSP);
                                            ob_end_clean();
                                            header('Content-Type: application/json');
                                            echo json_encode(['thongKeListSP' => $thongKeListSPArray]);
                                            exit;
                                        } else {
                                            //Add +1 day to dateTo:
                                            $dateTo = date('Y-m-d', strtotime($filterAll['dateTo'] . ' +1 day'));
                                            $thongKeListSP = OrdersBUS::getInstance()->filterByDateRangeSPTheoLoai($filterAll['dateFrom'], $dateTo, $category);
                                            $thongKeListSPArray = array_map(function ($thongKe) {
                                                return $thongKe->toArray();
                                            }, $thongKeListSP);
                                            ob_end_clean();
                                            header('Content-Type: application/json');
                                            echo json_encode(['thongKeListSP' => $thongKeListSPArray]);
                                            exit;
                                        }
                                    }
                                } elseif (isset($filterAll['dateFrom'])) {
                                    if (isset($filterAll['khachhang'])) {
                                        $thongKeListKH = OrdersBUS::getInstance()->filterByDateRangeKH($filterAll['dateFrom']);
                                        $thongKeListKHArray = array_map(function ($thongKe) {
                                            return $thongKe->toArray();
                                        }, $thongKeListKH);
                                        ob_end_clean();
                                        header('Content-Type: application/json');
                                        echo json_encode(['thongKeListKH' => $thongKeListKHArray]);
                                        exit;
                                    }
                                    if (isset($filterAll['sanpham'])) {
                                        $category = $filterAll['category'];
                                        if ($category == -1) {
                                            $thongKeListSP = OrdersBUS::getInstance()->filterByDateRangeSP($filterAll['dateFrom']);
                                            $thongKeListSPArray = array_map(function ($thongKe) {
                                                return $thongKe->toArray();
                                            }, $thongKeListSP);
                                            ob_end_clean();
                                            header('Content-Type: application/json');
                                            echo json_encode(['thongKeListSP' => $thongKeListSPArray]);
                                            exit;
                                        } else {
                                            $thongKeListSP = OrdersBUS::getInstance()->filterByDateRangeSPTheoLoai($filterAll['dateFrom'], $category);
                                            $thongKeListSPArray = array_map(function ($thongKe) {
                                                return $thongKe->toArray();
                                            }, $thongKeListSP);
                                            ob_end_clean();
                                            header('Content-Type: application/json');
                                            echo json_encode(['thongKeListSP' => $thongKeListSPArray]);
                                            exit;
                                        }
                                    }
                                } elseif (isset($filterAll['dateTo'])) {
                                    if (isset($filterAll['khachhang'])) {
                                        $dateTo = date('Y-m-d', strtotime($filterAll['dateTo'] . ' +1 day'));
                                        $thongKeListKH = OrdersBUS::getInstance()->filterByDateRangeKH($dateTo);
                                        $thongKeListKHArray = array_map(function ($thongKe) {
                                            return $thongKe->toArray();
                                        }, $thongKeListKH);
                                        ob_end_clean();
                                        header('Content-Type: application/json');
                                        echo json_encode(['thongKeListKH' => $thongKeListKHArray]);
                                        exit;
                                    }
                                    if (isset($filterAll['sanpham'])) {
                                        $category = $filterAll['category'];
                                        if ($category == -1) {
                                            //Add +1 day to dateTo:
                                            $dateTo = date('Y-m-d', strtotime($filterAll['dateTo'] . ' +1 day'));
                                            $thongKeListSP = OrdersBUS::getInstance()->filterByDateRangeSP($dateTo);
                                            $thongKeListSPArray = array_map(function ($thongKe) {
                                                return $thongKe->toArray();
                                            }, $thongKeListSP);
                                            ob_end_clean();
                                            header('Content-Type: application/json');
                                            echo json_encode(['thongKeListSP' => $thongKeListSPArray]);
                                            exit;
                                        } else {
                                            //Add +1 day to dateTo:
                                            $dateTo = date('Y-m-d', strtotime($filterAll['dateTo'] . ' +1 day'));
                                            $thongKeListSP = OrdersBUS::getInstance()->filterByDateRangeSPTheoLoai($dateTo, $category);
                                            $thongKeListSPArray = array_map(function ($thongKe) {
                                                return $thongKe->toArray();
                                            }, $thongKeListSP);
                                            ob_end_clean();
                                            header('Content-Type: application/json');
                                            echo json_encode(['thongKeListSP' => $thongKeListSPArray]);
                                            exit;
                                        }
                                    }
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
                            <th scope="col" class="col-1">ID Order</th>
                            <th scope="col" class="col-1">User ID</th>
                            <th scope="col" class="col-2">Customer Name</th>
                            <th scope="col" class="col-2">Order Date</th>
                            <th scope="col" class="col-1">Final Price</th>
                            <th scope="col" class="col-1 text-center">Status</th>
                            <th scope="col" class="col-1 text-center">Info</th>
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
        let headerThongKe = document.querySelector('.header_thong_ke');
        let thongKeKHList = <?= $thongKeListKHJSON ?>;
        let thongKeSPList = [];
        let filterOption = document.querySelector('#filterOption');
        let tableBody = document.querySelector('.showAllThongKe');
        var dateFrom = document.getElementById('purchased-date-from').value;
        var dateTo = document.getElementById('purchased-date-to').value;
        var selectFilterByCategory = document.querySelector('#filterCategoryOption');

        // Function to add click event to see detail buttons
        function addSeeDetailButtonClickEvent() {
            let seeDetailButtons = document.querySelectorAll('.seeDetailBtn');
            seeDetailButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    let userId = button.getAttribute('id');
                    getOrderListByUserId(userId);
                    modelDetail.style.display = 'flex';
                });
            });
        }

        // Initial adding of click event to see detail buttons
        addSeeDetailButtonClickEvent();

        // Click event for filter by date button
        filterByDateBtn.addEventListener('click', function () {
            if (filterOption.value == 'khachHang') {
                filterKhachHang();
            }
            if (filterOption.value == 'sanPham') {
                filterSanPham();
            }
        });

        filterOption.addEventListener('change', function () {
            var selectedValue = filterOption.value;
            if (selectedValue === 'khachHang') {
                selectFilterByCategory.style.display = 'none';
                filterKhachHang();
            } else if (selectedValue === 'sanPham') {
                selectFilterByCategory.style.display = 'block';
                filterSanPham();
            }
        });

        selectFilterByCategory.addEventListener('change', function () {
            filterSanPham();
        })

        function filterKhachHang() {
            headerThongKe.innerHTML = `<tr>
            <th scope="col"></th>
            <th scope="col">User Id</th>
            <th scope="col">Name</th>
            <th scope="col">Total amount</th>
            <th scope="col">Action</th>
            </tr>`;
            dateFrom = document.getElementById('purchased-date-from').value;
            dateTo = document.getElementById('purchased-date-to').value;
            fetch('http://localhost/frontend/?module=dashboard&view=dashboard.view', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'dateFrom=' + dateFrom + '&dateTo=' + dateTo + '&khachhang=' + true
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    thongKeKHList = data.thongKeListKH;
                    tableBody.innerHTML = htmlThongKeKHList(thongKeKHList);
                    addSeeDetailButtonClickEvent();
                    var ctx = document.getElementById('thongKeCotChart').getContext('2d');
                    Chart.getChart('thongKeCotChart').destroy();
                    drawThongKeKHCotChart();
                    var ctx = document.getElementById('thongKeDuongChart').getContext('2d');
                    Chart.getChart('thongKeDuongChart').destroy();
                    drawThongKeKHDuongChart();
                    var ctx = document.getElementById('thongKeTronChart').getContext('2d');
                    Chart.getChart('thongKeTronChart').destroy();
                    drawThongKeKHTronChart();
                });
        }

        function filterSanPham() {
            var category = selectFilterByCategory.value;
            headerThongKe.innerHTML = `<tr>
            <th scope="col"></th>
            <th scope="col">Product Id</th>
            <th scope="col">Name</th>
            <th scope="col">Total quantity</th>
            <th scope="col">Total price</th>
            </tr>`
            dateFrom = document.getElementById('purchased-date-from').value;
            dateTo = document.getElementById('purchased-date-to').value;
            fetch('http://localhost/frontend/?module=dashboard&view=dashboard.view', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'dateFrom=' + dateFrom + '&dateTo=' + dateTo + '&sanpham=' + true + '&category=' + category
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    thongKeSPList = data.thongKeListSP;
                    tableBody.innerHTML = htmlThongKeSPList(thongKeSPList);
                    var ctx = document.getElementById('thongKeCotChart').getContext('2d');
                    Chart.getChart('thongKeCotChart').destroy();
                    drawThongKeSPCotChart();
                    var ctx = document.getElementById('thongKeDuongChart').getContext('2d');
                    Chart.getChart('thongKeDuongChart').destroy();
                    drawThongKeSPDuongChart();
                    var ctx = document.getElementById('thongKeTronChart').getContext('2d');
                    Chart.getChart('thongKeTronChart').destroy();
                    drawThongKeSPTronChart();
                });
        }

        // Function to generate HTML for thong ke list
        function htmlThongKeKHList(thongKeList) {
            let htmlInTable = "";
            thongKeList.forEach(function (thongKe, index) {
                htmlInTable += `
            <tr class="align-middle">
                <th scope="col">${index + 1}</th>
                <th scope="col">${thongKe.userId}</th>
                <th scope="col">${thongKe.customerName}</th>
                <th scope="col">${thongKe.totalPurchaseAmount}</th>
                <th scope="col"><button class="seeDetailBtn" id="${thongKe.userId}"><i class="fas fa-eye"></i></button></th>
            </tr>`;
            });
            return htmlInTable;
        }

        function htmlThongKeSPList(thongKeList) {
            let htmlInTable = "";
            thongKeList.forEach(function (thongKe, index) {
                htmlInTable += `
            <tr class="align-middle">
                <th scope="col">${index + 1}</th>
                <th scope="col">${thongKe.productId}</th>
                <th scope="col">${thongKe.productName}</th>
                <th scope="col">${thongKe.totalQuantitySold}</th>
                <th scope="col">${thongKe.totalAmount}</th>
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
                            <td>${order.userId}</td>
                            <td>${order.customerName}</td>
                            <td>${order.orderDate}</td>
                            <td>${order.totalAmount}</td>
                            <td class="text-center">${order.status}</td>
                            <td class="text-center"><button class="orderDetailBtn" data-order-id="${order.id}"><i class="fas fa-eye"></i></button></td>
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



        function drawThongKeKHCotChart() {
            var ctx = document.getElementById('thongKeCotChart').getContext('2d');
            var thongKeKHChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: thongKeKHList.map(item => item.customerName),
                    datasets: [{
                        label: 'Total Purchase Amount',
                        data: thongKeKHList.map(item => item.totalPurchaseAmount),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function drawThongKeSPCotChart() {
            var ctx = document.getElementById('thongKeCotChart').getContext('2d');
            var thongKeSPChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: thongKeSPList.map(item => item.productName),
                    datasets: [{
                        label: 'Total Quantity Sold',
                        data: thongKeSPList.map(item => item.totalQuantitySold),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'decimal'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Hàm vẽ biểu đồ đường cho thống kê khách hàng
        function drawThongKeKHDuongChart() {
            var ctx = document.getElementById('thongKeDuongChart').getContext('2d');
            var thongKeKHChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: thongKeKHList.map(item => item.customerName),
                    datasets: [{
                        label: 'Total Purchase Amount',
                        data: thongKeKHList.map(item => item.totalPurchaseAmount),
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Hàm vẽ biểu đồ đường cho thống kê sản phẩm
        function drawThongKeSPDuongChart() {
            var ctx = document.getElementById('thongKeDuongChart').getContext('2d');
            var thongKeSPChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: thongKeSPList.map(item => item.productName),
                    datasets: [{
                        label: 'Total quantity sold',
                        data: thongKeSPList.map(item => item.totalQuantitySold),
                        fill: false,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'decimal'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function drawThongKeKHTronChart() {
            var ctx = document.getElementById('thongKeTronChart').getContext('2d');
            var thongKeKHChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: thongKeKHList.map(item => item.customerName),
                    datasets: [{
                        label: 'Total Purchase Amount',
                        data: thongKeKHList.map(item => item.totalPurchaseAmount),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                }
            });
        }

        function drawThongKeSPTronChart() {
            var ctx = document.getElementById('thongKeTronChart').getContext('2d');
            var thongKeSPChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: thongKeSPList.map(item => item.productName),
                    datasets: [{
                        label: 'Total Quantity Sold',
                        data: thongKeSPList.map(item => item.totalQuantitySold),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', {
                                            style: 'decimal'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                }
            });
        }
        // Gọi hàm vẽ biểu đồ khách hàng
        drawThongKeKHCotChart();
        drawThongKeKHDuongChart();
        drawThongKeKHTronChart();
    });
</script>