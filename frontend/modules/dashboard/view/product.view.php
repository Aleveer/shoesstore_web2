<?php
use backend\bus\CategoriesBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\SizeItemsBUS;
use backend\models\ProductModel;

$title = 'Product';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}
include (__DIR__ . '/../inc/head.php');

use backend\bus\ProductBUS;

$productList = ProductBUS::getInstance()->getAllModels();

function showProductList($product)
{
    echo "
        <tr>
            <td><img src='{$product->getImage()}' alt='{$product->getName()}' class='rounded float-start'></td>
            <td class='text-center'>{$product->getId()}</td>
            <td>{$product->getName()}</td>
            <td>" . CategoriesBUS::getInstance()->getModelById($product->getCategoryId())->getName() . "</td>
            <td>{$product->getDescription()}</td>
            <td class='text-center'>{$product->getPrice()}</td>
            <td class='text-center'>
                <div>
                    <a href='http://localhost/frontend/index.php?module=dashboard&view=product.update&id={$product->getId()}' class='btn btn-sm btn-warning'>
                        <span data-feather='tool'></span>
                    </a>
                    <button class='btn btn-sm btn-danger' id='completelyDeleteProduct' name='completelyDeleteProduct'>
                        <span data-feather='trash-2'></span>
                    </button>
                    <button class='btn btn-sm btn-danger' id='deleteProductButton' name='deleteProductButton'>
                        <span data-feather='eye-off'></span>
                    </button>
                </div>
            </td>
            <td class='text-center'>{$product->getStatus()}</td>
        </tr>
    ";
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
            <main class="col-9 ms-sm-auto col-lg-10 px-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>

                    <div class="btn-toolbar mb-2 mb-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                            data-bs-target="#addModal" id="addProduct" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <form action="" method="POST">
                    <div class="search-group input-group py-2">
                        <input type="text" name="productSearch" id="productSearchBar" class="searchInput form-control"
                            placeholder="Search anything here...">
                        <button type="submit" id="productSearchButton" name="productSearchButtonName"
                            class="btn btn-sm btn-primary align-middle px-3">
                            <span data-feather="search"></span>
                        </button>
                    </div>
                </form>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th></th>
                            <th class='text-center'>ID</th>
                            <th class='col-3'>Product Name</th>
                            <th class='col-1'>Categories</th>
                            <th class='col-5'>Description</th>
                            <th class='col-1 text-center'>Price</th>
                            <th class='col-2 text-center'>Action</th>
                            <th class='col-1 text-center'>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        //By default, the product list shows all: 
                        if (!isPost() || (isPost() && !isset($_POST['productSearchButtonName']))) {
                            foreach ($productList as $product): ?>
                                <?= showProductList($product); ?>
                            <?php endforeach;
                        } ?>

                        <!-- Function -->
                        <?php
                        if (isPost()) {
                            $filterAll = filter();
                            if (isset($_POST['productSearchButtonName'])) {
                                $searchQuery = $_POST['productSearch'];
                                $searchResult = array();
                                if (empty($searchQuery)) {
                                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                    echo "Please input the search bar to search!";
                                    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                    echo "</div>";
                                    $searchResult = ProductBUS::getInstance()->getAllModels();
                                } else {
                                    $searchResult = ProductBUS::getInstance()->searchModel($searchQuery, ['id', 'name', 'price', 'description']);
                                    // Check if searchModel returned any results
                                    if (empty($searchResult) || count($searchResult) == 0) {
                                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                        echo "No result found!";
                                        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                        echo "</div>";
                                    }
                                }
                                foreach ($searchResult as $product) {
                                    showProductList($product);
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Add modal -->
                <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Product</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3">
                                    <div class="col-7">
                                        <label for="inputProductName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="inputProductName"
                                            name="productName">
                                    </div>
                                    <div class="col-5">
                                        <label for="inputProductCate" class="form-label">Categories</label>
                                        <select id="inputProductCate" class="form-select" name="category">
                                            <?php $categoriesList = CategoriesBUS::getInstance()->getAllModels();
                                            foreach ($categoriesList as $categories) {
                                                echo "<option value='" . $categories->getId() . "'>" . $categories->getName() . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="inputPrice" class="form-label">Price</label>
                                        <input type="text" class="form-control" id="inputPrice" name="price">
                                    </div>
                                    <div class="col-4">
                                        <label for="inputGender" class="form-label">Gender</label>
                                        <select id="inputGender" class="form-select" name="gender">
                                            <option value="0" selected>Male</option>
                                            <option value="1">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-7">
                                        <label for="inputDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="w3review" name="description" row="1"
                                            cols="40"></textarea>
                                    </div>
                                    <div class="col-7">
                                        <label for="inputImg">Image (.JPG, .JPEG, .PNG)</label>
                                        <input type="file" class="form-control" name="image" id="inputImg"
                                            accept=".jpg, .jpeg, .png">
                                    </div>
                                    <div class="col-5 productImg">
                                        <img id="imgPreview" src="..\..\..\..\templates\images\680098.jpg"
                                            alt="Preview Image">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="saveButton"
                                    name="saveBtnName">Save</button>
                            </div>
                            <?php
                            if (isPost()) {
                                if (isset($_POST['saveBtn'])) {
                                    $productName = $_POST['productName'];
                                    $productCategory = $_POST['category'];
                                    $productPrice = $_POST['price'];
                                    $productGender = $_POST['gender'];
                                    $productDescription = $_POST['description'];

                                    $productModel = new ProductModel(null, $productName, $productCategory, $productPrice, $productDescription, null, $productGender, 'active');
                                    $data = $_POST['image'];
                                    $productModel->setImage($data);

                                    ProductBUS::getInstance()->addModel($productModel);
                                    ProductBUS::getInstance()->refreshData();
                                    //Once created, refresh the page:
                                    echo '<script>window.location.href = "?module=dashboard&view=product.view";</script>';
                                }
                            }
                            ?>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                //Handle delete product:
                if (isPost()) {
                    if (isset($_POST['deleteButton'])) {
                        error_log('Delete button clicked');
                        $productId = $_POST['productId'];
                        $updateProductStatus = ProductBUS::getInstance()->getModelById($productId);
                        $updateProductStatus->setStatus('inactive');
                        ProductBUS::getInstance()->updateModel($updateProductStatus);
                        ProductBUS::getInstance()->refreshData();
                    }
                }

                //Handle completely delete product:
                //TODO: Fix not popping up confirmation dialog
                if (isPost()) {
                    if (isset($_POST['completelyDeleteProduct'])) {
                        $productId = $_POST['productId'];
                        $productPreparedToDel = ProductBUS::getInstance()->getModelById($productId);

                        //Check for orders that contain the product:
                        $orders = OrderItemsBUS::getInstance()->getOrderItemsListByProductId($productId);
                        if (count($orders) > 0) {
                            echo '<script>alert("Cannot delete product. Product is in orders.");</script>';
                            error_log('Cannot delete product. Product is in orders.');
                        } else {
                            //Confirm to delete product:
                            echo '<script>
            if(confirm("Are you sure you want to delete this product? Once you delete, you cannot recover it.")) {
                ';
                            foreach (SizeItemsBUS::getInstance()->getModelByProductId($productId) as $sizeItem) {
                                if ($sizeItem->getProductId() == $productId) {
                                    SizeItemsBUS::getInstance()->deleteModel($sizeItem);
                                }
                            }
                            ProductBUS::getInstance()->deleteModel($productPreparedToDel->getId());
                            ProductBUS::getInstance()->refreshData();
                            echo '
            window.location.href = "?module=dashboard&view=product.view";
            }
            </script>';
                        }
                    }
                }
                ?>
                <?php include (__DIR__ . '/../inc/app/app.php'); ?>
                <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_product.js"></script>
                <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/delete_product.js"></script>
</body>