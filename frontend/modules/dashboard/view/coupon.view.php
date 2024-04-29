<?php
use backend\bus\CouponsBUS;
use backend\models\CouponsModel;

$title = 'Coupons';

if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}
include (__DIR__ . '/../inc/head.php');
include (__DIR__ . '/../inc/app/app.php');

function showCouponList($coupon)
{
    echo "<tr>";
    echo "<td class='col-1'>" . $coupon->getId() . "</td>";
    echo "<td class='col-2'>" . $coupon->getCode() . "</td>";
    echo "<td class='col-2'>" . $coupon->getQuantity() . "</td>";
    echo "<td class='col-1'>" . $coupon->getPercent() . "</td>";
    echo "<td class='col-2'>" . $coupon->getExpired() . "</td>";
    echo "<td class='col-1'>" . $coupon->getDescription() . "</td>";
    echo "<td class='col-2 couponAction'>";
    echo "<button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editModal" . $coupon->getId() . "'>";
    echo "<span data-feather='tool'></span>";
    echo "Update";
    echo "</button>";
    echo "<button class='btn btn-sm btn-danger' id='deleteCouponBtnId' name='deleteCouponBtnName'>";
    echo "<span data-feather='trash-2'></span>";
    echo "Delete";
    echo "</button>";
    echo "</td>";
    echo "</tr>";
    //Add modal:
    echo "<div class='modal fade' id='editModal" . $coupon->getId() . "' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true' style='width: 100%'>";
    echo "<div class='modal-dialog modal-lg'>";
    echo "<div class='modal-content'>";
    echo "<div class='modal-header'>";
    echo "<h1 class='modal-title fs-5' id='staticBackdropLabel'>Edit Coupon</h1>";
    echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
    echo "</div>";
    echo "<div class='modal-body'>";
    echo "<form class='row g-3' id='editForm" . $coupon->getId() . "'>";
    echo "<div class='col-md-4'>";
    echo "<label for='inputCode' class='form-label'>Code</label>";
    echo "<input type='text' class='form-control' id='inputEdtCouponCodeId" . $coupon->getId() . "' value='" . $coupon->getCode() . "'>";
    echo "</div>";
    echo "<div class='col-md-3'>";
    echo "<label for='inputPassword' class='form-label'>Quantity</label>";
    echo "<input type='number' name='inputCouponQuantity' id='inputEdtCouponQuantityId" . $coupon->getId() . "' class='form-control' value='" . $coupon->getQuantity() . "'>";
    echo "</div>";
    echo "<div class='col-5'>";
    echo "<label for='inputEmail' class='form-label'>Discount(%)</label>";
    echo "<input type='number' class='form-control' name='inputDiscountQuantity' id='inputEdtCouponDiscount" . $coupon->getId() . "' value='" . $coupon->getPercent() . "'>";
    echo "</div>";
    echo "<div class='col-md-6'>";
    echo "<label for='inputDescription' class='form-label'>Description</label>";
    echo "<textarea class='form-control' id='couponEdtDescriptionId" . $coupon->getId() . "' name='descriptionEdt' row='1' cols='40'>" . $coupon->getDescription() . "</textarea>";
    echo "</div>";
    echo "<div class='col-md-6'>";
    echo "<label for='inputExpired' class='form-label'>Expired Date</label>";
    echo "<input type='date' class='form-control' id='inputEdtCouponExpiredId" . $coupon->getId() . "' value='" . $coupon->getExpired() . "'>";
    echo "</div>";
    echo "<div class='modal-footer'>";
    echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>";
    echo "<button type='submit' class='btn btn-primary' id='editButtonId' name='editBtnName'>Update</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
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
                    <form method="POST" class="search-group input-group">
                        <input type="text" id="couponSearchBarId" name="couponSearchBar"
                            class="searchInput form-control" placeholder="Search...">
                        <input type="date" id="startDate" name="startDate" class="form-control" style="width: 120px;"
                            placeholder="Start Date">
                        <input type="date" id="endDate" name="endDate" class="form-control" style="width: 120px;"
                            placeholder="End Date">
                        <button type="submit" class="btn btn-sm btn-primary align-middle padx-0 pady-0" id="searchBtnId"
                            name="searchBtnName">
                            <span data-feather="search"></span>
                        </button>
                    </form>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                            data-bs-target="#addModal" id="addCouponId" name="addCouponName" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <table class="table align-middle table-borderless table-hover text-start">
                    <thead>
                        <tr class="align-middle">
                            <th>Id</th>
                            <th>Code</th>
                            <th>Quantity</th>
                            <th>Discount (%)</th>
                            <th>Expired Date</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <?php
                    if (!isPost() || (isPost() && !isset($_POST['searchBtnName']))) {
                        foreach (CouponsBUS::getInstance()->getAllModels() as $coupons) {
                            showCouponList($coupons);
                        }
                    }
                    ?>
                    <!--Handling search function -->
                    <?php
                    if (isPost()) {
                        $filterAll = filter();
                        if (isset($_POST['searchBtnName'])) {
                            $search = $_POST['couponSearchBar'];
                            $startDate = $_POST['startDate'];
                            $endDate = $_POST['endDate'];

                            // Initialize coupons list
                            $couponsList = array();

                            // If both search bar and date picker are empty, show all coupons
                            if (empty($search) && empty($startDate) && empty($endDate)) {
                                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                echo "Please input at least one of the search bar or date picker to search!";
                                echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                echo "</div>";
                                $couponsList = CouponsBUS::getInstance()->getAllModels();
                            } else {
                                // Search by search term if it's not empty
                                if (!empty($search)) {
                                    $couponsList = CouponsBUS::getInstance()->searchModel($search, ['code', 'quantity', 'percent', 'description']);
                                }

                                // Search by date range if both dates are not empty
                                if (!empty($startDate) && !empty($endDate)) {
                                    $couponsList = CouponsBUS::getInstance()->searchBetweenDate($startDate, $endDate);
                                }

                                // Search by start date if only start date is not empty
                                if (!empty($startDate) && empty($endDate)) {
                                    $couponsList = CouponsBUS::getInstance()->searchAfterDate($startDate);
                                }

                                // Search by end date if only end date is not empty
                                if (empty($startDate) && !empty($endDate)) {
                                    $couponsList = CouponsBUS::getInstance()->searchBeforeDate($endDate);
                                }

                                // Filter by search term if both search term and date are not empty
                                if (!empty($search) && (!empty($startDate) || !empty($endDate))) {
                                    $couponsList = array_filter($couponsList, function ($coupon) use ($search) {
                                        return $coupon->getCode() == $search;
                                    });
                                }
                            }

                            if (count($couponsList) == 0) {
                                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                echo "No result found!";
                                echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                echo "</div>";
                            }

                            // Display the coupons
                            foreach ($couponsList as $coupons) {
                                showCouponList($coupons);
                            }
                        }
                    }
                    ?>
                </table>
            </main>

            <!-- Add modal -->
            <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true" style="width: 100%">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Coupon</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3">
                                <div class="col-md-4">
                                    <label for="inputCode" class="form-label">Code</label>
                                    <input type="text" class="form-control" id="inputCouponCodeId">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputPassword" class="form-label">Quantity</label>
                                    <input type="number" name="inputCouponQuantity" id="inputCouponQuantityId"
                                        class="form-control">
                                </div>
                                <div class="col-5">
                                    <label for="inputEmail" class="form-label">Discount(%)</label>
                                    <input type="number" class="form-control" name="inputDiscountQuantityId"
                                        id="inputCouponDiscount">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="couponDescriptionId" name="description" row="1"
                                        cols="40"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputExpired" class="form-label">Expired Date</label>
                                    <input type="date" class="form-control" id="inputCouponExpiredId">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary" id="saveButton"
                                        name="saveBtnName">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                //TODO: Fix notification not showing
                if (isPost()) {
                    if (isset($_POST['saveBtn'])) {
                        $code = $_POST['couponCode'];
                        if (CouponsBUS::getInstance()->checkForDuplicateCode($code)) {
                            error_log('Duplicate code');
                            return;
                        }
                        $quantity = $_POST['couponQuantity'];
                        $discount = $_POST['couponDiscount'];
                        $description = $_POST['couponDescription'];
                        $expired = $_POST['couponExpiredDate'];
                        $currentDate = date('Y-m-d');
                        if ($expired < $currentDate) {
                            error_log('Expired date must be greater than current date');
                            return;
                        }

                        $newCoupon = new CouponsModel(null, $code, $quantity, $discount, $expired, $description);
                        CouponsBUS::getInstance()->addModel($newCoupon);
                        CouponsBUS::getInstance()->refreshData();
                    }
                }
                ?>
                <?php
                if (isPost()) {
                    if (isset($_POST['editButtonName'])) {
                        $id = $_POST['id'];
                        $coupon = CouponsBUS::getInstance()->getModelById($id);
                        $code = $_POST['code'];

                        if ($coupon->getCode() != $code) {
                            if (CouponsBUS::getInstance()->checkForDuplicateCode($code)) {
                                error_log('Duplicate code');
                                return;
                            }
                        }

                        $quantity = $_POST['quantity'];
                        $discount = $_POST['discount'];
                        $description = $_POST['description'];
                        $expired = $_POST['expiry'];

                        $coupon->setCode($code);
                        $coupon->setQuantity($quantity);
                        $coupon->setPercent($discount);
                        $coupon->setDescription($description);
                        $coupon->setExpired($expired);
                        CouponsBUS::getInstance()->updateModel($coupon);
                        CouponsBUS::getInstance()->refreshData();
                    }
                }
                ?>

                <?php
                if (isPost()) {
                    if (isset($_POST['deleteCouponBtn'])) {
                        $id = $_POST['couponId'];
                        $couponModel = CouponsBUS::getInstance()->getModelById($id);
                        CouponsBUS::getInstance()->deleteModel($couponModel->getId());
                        CouponsBUS::getInstance()->refreshData();
                    }
                }
                ?>
                <?php include (__DIR__ . '/../inc/app/app.php'); ?>
                <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_coupon.js"></script>
                <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_coupon.js"></script>
                <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/delete_coupon.js"></script>

</body>