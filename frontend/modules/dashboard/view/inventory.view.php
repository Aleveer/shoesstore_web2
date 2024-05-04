<?php
ob_start();
use backend\bus\CategoriesBUS;
use backend\bus\SizeBUS;
use backend\bus\SizeItemsBUS;
use backend\models\SizeItemsModel;

$title = 'Inventory';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission(2)) {
    die('Access denied');
}

include (__DIR__ . '/../inc/head.php');

use backend\bus\ProductBUS;

$productList = ProductBUS::getInstance()->getAllModels();
?>


<body>
    <!-- HEADER -->
    <?php include (__DIR__ . '/../inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR MENU -->
            <?php include (__DIR__ . '/../inc/sidebar.php'); ?>

            <!-- MAIN -->
            <main class="col-9 ms-sm-auto col-lg-10 px-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>

                    <div class="btn-toolbar mb-2 mb-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                            data-bs-target="#addModal" id="addSizeItem" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <form method="POST" style="width: 70%;">
                    <div class="search-group input-group">
                        <input type="text" id="productSearch" class="searchInput form-control" name="searchValue"
                            placeholder="Search product name here...">
                        <button type="submit" class="btn btn-sm btn-primary align-middle padx-0 pady-0"
                            name="searchBtnName" id="searchBtnId">
                            <span data-feather="search"></span>
                        </button>
                    </div>
                </form>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th></th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    if (!isPost() || (!isPost() && !isset($_POST['searchBtnName']))) {
                        $products = SizeItemsBUS::getInstance()->getAllModels();
                        $sizeItemList = SizeItemsBUS::getInstance()->getAllModels();

                        $page = isset($_GET['page']) ? $_GET['page'] : 1;

                        $sizeItemChunks = array_chunk($sizeItemList, 12);
                        $sizeItemsForCurrentPage = $sizeItemChunks[$page - 1];
                        foreach ($sizeItemsForCurrentPage as $sizeItem) {
                            $product = ProductBUS::getInstance()->getModelById($sizeItem->getProductId());
                            $size = SizeBUS::getInstance()->getModelById($sizeItem->getSizeId());
                            ?>
                            <tbody>
                                <tr>
                                    <td><img src='<?php echo $product->getImage(); ?>' alt='' class='rounded float-start'>
                                    </td>
                                    <td><?php echo $product->getName(); ?></td>
                                    <td>
                                        <?php $categoryName = CategoriesBUS::getInstance()->getModelById($product->getCategoryId())->getName(); ?>
                                        <?php echo $categoryName; ?>
                                    </td>
                                    <td>
                                        <?php echo preg_replace('/[^0-9]/', '', $size->getName()); ?><br>
                                    </td>
                                    <td>
                                        <?php echo $sizeItem->getQuantity(); ?><br>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editQuantityModal_<?= $product->getId() ?>_<?= $size->getId() ?>">
                                            <span data-feather="tool"></span>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            id='deleteSizeItemBtn_<?= $product->getId() ?>_<?= $size->getId() ?>'
                                            name='deleteSizeItemBtn'>
                                            <span data-feather="trash-2"></span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- edit quantity modal -->
                                <div class="modal fade" id="editQuantityModal_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form id="form_<?= $product->getId() ?>_<?= $size->getId() ?>">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Size Quantity</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="inputSize" class="form-label">Size</label>
                                                    <input type="text" name="inputSize" id="inputSize" class="form-control"
                                                        value="<?= preg_replace('/[^0-9]/', '', $size->getName()) ?>" readonly>

                                                    <label for="inputQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                        class="form-label">Current
                                                        Quantity</label>
                                                    <input type="number" name="inputQuantity"
                                                        id="inputQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                        class="form-control" value="<?= $sizeItem->getQuantity() ?>" readonly>

                                                    <label for="inputNewQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                        class="form-label">New Quantity</label>
                                                    <input type="number" name="inputNewQuantity"
                                                        id="inputNewQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                        class="form-control">
                                                    <p class="text-danger"> Tip: Negative number for decreasing quantity, else
                                                        increasing quantity from
                                                        current quantity</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tbody>
                            <?php
                        }

                        $totalPages = count($sizeItemChunks);

                        echo "<nav aria-label='Page navigation example'>";
                        echo "<ul class='pagination justify-content-center'>";

                        // Add previous button
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=inventory.view&page=" . ($page - 1) . "'>Previous</a></li>";
                        }

                        $range = 2; // Change this to increase or decrease the range of pages displayed around the current page
                        for ($i = 1; $i <= $totalPages; $i++) {
                            // If the page number is within the range of the current page or is the first or last page, display it
                            if ($i == $page || $i == 1 || $i == $totalPages || ($i > $page - $range && $i < $page + $range)) {
                                // Highlight the current page
                                if ($i == $page) {
                                    echo "<li class='page-item active'><a class='page-link' href='?module=dashboard&view=inventory.view&page=$i'>$i</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=inventory.view&page=$i'>$i</a></li>";
                                }
                            }
                            // If the page number is just outside the range of the current page, display an ellipsis
                            else if ($i == $page - $range - 1 || $i == $page + $range + 1) {
                                echo "<li class='page-item disabled'><a class='page-link'>...</a></li>";
                            }
                        }

                        // Add next button
                        if ($page < $totalPages) {
                            echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=inventory.view&page=" . ($page + 1) . "'>Next</a></li>";
                        }

                        echo "</ul>";
                        echo "</nav>";
                        ?>

                        </tbody>
                        <?php
                    }
                    if (isPost() && isset($_POST['searchBtnName'])) {
                        $searchValue = $_POST['searchValue'];
                        if (empty($searchValue) || trim($searchValue) == '') {
                            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                            echo "Please input the search bar to search!";
                            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' onclick='window.history.back(); aria-label='Close'></button>";
                            echo "</div>";
                        }

                        $products = ProductBUS::getInstance()->searchModel($searchValue, ['name']);
                        if (count($products) == 0 && !empty($searchValue)) {
                            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                            echo "No product found!";
                            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' onclick='window.history.back(); aria-label='Close'></button>";
                            echo "</div>";
                        }

                        //Show search result:
                        foreach ($products as $product) {
                            $sizeItems = SizeItemsBUS::getInstance()->getModelByProductId($product->getId());
                            foreach ($sizeItems as $sizeItem) {
                                $size = SizeBUS::getInstance()->getModelById($sizeItem->getSizeId());
                                ?>
                                <tbody>
                                    <tr>
                                        <td><img src='<?php echo $product->getImage(); ?>' alt='' class='rounded float-start'>
                                        </td>
                                        <td><?php echo $product->getName(); ?></td>
                                        <td>
                                            <?php $categoryName = CategoriesBUS::getInstance()->getModelById($product->getCategoryId())->getName(); ?>
                                            <?php echo $categoryName; ?>
                                        </td>
                                        <td>
                                            <?php echo preg_replace('/[^0-9]/', '', $size->getName()); ?><br>
                                        </td>
                                        <td>
                                            <?php echo $sizeItem->getQuantity(); ?><br>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editQuantityModal_<?= $product->getId() ?>_<?= $size->getId() ?>">
                                                <span data-feather="tool"></span>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                id='deleteSizeItemBtn_<?= $product->getId() ?>_<?= $size->getId() ?>'
                                                name='deleteSizeItemBtn'>
                                                <span data-feather="trash-2"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- edit quantity modal -->
                                    <div class="modal fade" id="editQuantityModal_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form id="form_<?= $product->getId() ?>_<?= $size->getId() ?>">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Size Quantity</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="inputSize" class="form-label">Size</label>
                                                        <input type="text" name="inputSize" id="inputSize" class="form-control"
                                                            value="<?= preg_replace('/[^0-9]/', '', $size->getName()) ?>" readonly>

                                                        <label for="inputQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                            class="form-label">Current
                                                            Quantity</label>
                                                        <input type="number" name="inputQuantity"
                                                            id="inputQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                            class="form-control" value="<?= $sizeItem->getQuantity() ?>" readonly>

                                                        <label for="inputNewQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                            class="form-label">New Quantity</label>
                                                        <input type="number" name="inputNewQuantity"
                                                            id="inputNewQuantity_<?= $product->getId() ?>_<?= $size->getId() ?>"
                                                            class="form-control">
                                                        <p class="text-danger"> Tip: Negative number for decreasing quantity, else
                                                            increasing quantity from
                                                            current quantity</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tbody>
                                <?php
                            }
                        }
                    }
                    ?>
                </table>

                <!-- Add modal -->
                <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Item</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3">
                                    <div class="col-7">
                                        <label for="inputProductName" class="form-label">Product Name</label>
                                        <select id="inputProductName" class="form-select" name="productName">
                                            <?php
                                            $productsListChecking = ProductBUS::getInstance()->getAllModels();
                                            $sizeListChecking = SizeBUS::getInstance()->getAllModels();
                                            $sizeItemsListChecking = SizeItemsBUS::getInstance()->getAllModels();
                                            $allProductsFullyAssigned = true;
                                            $assignedProducts = [];
                                            foreach ($sizeItemsListChecking as $sizeItem) {
                                                $assignedProducts[$sizeItem->getProductId()][] = $sizeItem->getSizeId();
                                            }

                                            foreach ($productsListChecking as $product) {
                                                $isFullyAssigned = true;
                                                foreach ($sizeListChecking as $size) {
                                                    if (!isset($assignedProducts[$product->getId()]) || !in_array($size->getId(), $assignedProducts[$product->getId()])) {
                                                        $isFullyAssigned = false;
                                                        break;
                                                    }
                                                }
                                                if (!$isFullyAssigned) {
                                                    echo "<option value='" . $product->getId() . "'>" . $product->getName() . "</option>";
                                                    $allProductsFullyAssigned = false;
                                                }
                                            }

                                            if ($allProductsFullyAssigned) {
                                                echo "<option value=''>None</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label for="inputSize" class="form-label">Sizes</label>
                                        <select id="inputSizeId" class="form-select" name="size">
                                            <?php
                                            $sizeList1 = SizeBUS::getInstance()->getAllModels();
                                            foreach ($sizeList1 as $size1) {
                                                echo "<option value='" . preg_replace(' /[^0-9]/', '', $size1->getId()) .
                                                    "'>" . $size1->getName() . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="inputPrice" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="inputQuantity" name="quantity">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="saveButton"
                                    name="saveBtnName">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php
            if (isPost()) {
                //Handle add size item:
                if (isset($_POST['saveBtnName'])) {
                    $productId = $_POST['productName'];
                    $sizeId = $_POST['size'];
                    $quantity = $_POST['quantity'];
                    $checkingAssignedSizeItem = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($sizeId, $productId);

                    if ($checkingAssignedSizeItem != null) {
                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                        echo "This size is already assigned to this product!";
                        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' onclick='window.history.back(); aria-label='Close'></button>";
                        echo "</div>";
                        $checkingAssignedSizeItem->setQuantity($checkingAssignedSizeItem->getQuantity() + $quantity);
                        SizeItemsBUS::getInstance()->updateModel($checkingAssignedSizeItem);
                        SizeItemsBUS::getInstance()->refreshData();
                    }

                    $newSizeItemModel = new SizeItemsModel(null, $productId, $sizeId, $quantity);
                    SizeItemsBUS::getInstance()->addModel($newSizeItemModel);
                    SizeItemsBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Add size item successfully!');
                }

                //Handle update quantity:
                if (isset($_POST['button'])) {
                    $productId = $_POST['productId'];
                    $sizeId = $_POST['sizeId'];
                    $newQuantity = $_POST['newQuantity'];
                    $currentQuantity = $_POST['currentQuantity'];
                    $sizeItem = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($sizeId, $productId);
                    $sizeItem->setQuantity($currentQuantity + $newQuantity);
                    SizeItemsBUS::getInstance()->updateModel($sizeItem);
                    SizeItemsBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Update quantity successfully!');
                }

                //Handle delete size item:
                if (isset($_POST['delete'])) {
                    $productId = $_POST['productId'];
                    $sizeId = $_POST['sizeId'];
                    $sizeItem = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($sizeId, $productId);
                    SizeItemsBUS::getInstance()->deleteModel($sizeItem);
                    SizeItemsBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Delete size item successfully!');
                }
            }
            ?>


            <?php include (__DIR__ . '/../inc/app/app.php'); ?>
            <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_sizeitem.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_sizeitem.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/delete_sizeitem.js"></script>

</body>