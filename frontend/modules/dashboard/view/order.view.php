<?php
$title = 'Orders';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}
include (__DIR__ . '/../inc/head.php');
include (__DIR__ . '/../inc/app/app.php');

// Namespace
use backend\bus\OrdersBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\UserBUS;

$orderList = OrdersBUS::getInstance()->getAllModels();
$orderListItem = OrderItemsBUS::getInstance();
// $userListDetail = UserBUS::getInstance()->getAllModels();

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
                <?php include (__DIR__ . '/order.search.php'); ?>

                <!-- BODY DATABASE -->
                <?php include (__DIR__ . '/order.table.php'); ?>
            </main>

            <?php include (__DIR__ . '/../inc/app/app.php'); ?>
</body>

</html>