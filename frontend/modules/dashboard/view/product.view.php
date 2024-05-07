<?php
use backend\enums\StatusEnums;

ob_start();
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

if (!checkPermission(1)) {
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

                <form action="" method="POST" class="m-0 col-lg-6">
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
                            <th class='col-1'>Category</th>
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
                            if (count($productList) > 0) {
                                // Get the current page number from the URL, if it's not set default to 1
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                                // By default, the product list shows all: 
                                if (!isPost() || (isPost() && !isset($_POST['productSearchButtonName']))) {
                                    // Split the product list into chunks of 12
                                    $productChunks = array_chunk($productList, 12);

                                    // Get the products for the current page
                                    $productsForCurrentPage = $productChunks[$page - 1];

                                    foreach ($productsForCurrentPage as $product): ?>
                                        <?= showProductList($product); ?>
                                    <?php endforeach;
                                }

                                // Calculate the total number of pages
                                $totalPages = count($productChunks);

                                echo "<nav aria-label='Page navigation example'>";
                                echo "<ul class='pagination justify-content-start'>";

                                // Add previous button
                                if ($page > 1) {
                                    echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=product.view&page=" . ($page - 1) . "'>Previous</a></li>";
                                }

                                for ($i = 1; $i <= $totalPages; $i++) {
                                    // Highlight the current page
                                    if ($i == $page) {
                                        echo "<li class='page-item active'><a class='page-link' href='?module=dashboard&view=product.view&page=$i'>$i</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=product.view&page=$i'>$i</a></li>";
                                    }
                                }

                                // Add next button
                                if ($page < $totalPages) {
                                    echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=product.view&page=" . ($page + 1) . "'>Next</a></li>";
                                }

                                echo "</ul>";
                                echo "</nav>";
                            }
                        }
                        ?>

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
                                    if (empty($searchResult) || count($searchResult) == 0) {
                                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                        echo "No result found!";
                                        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                        echo "</div>";
                                    } else {
                                        if (isset($_GET['page'])) {
                                            header('Location: http://localhost/frontend/index.php?module=dashboard&view=product.view');
                                            exit;
                                        }
                                        foreach ($searchResult as $product) {
                                            echo showProductList($product);
                                        }
                                    }
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
                                        <img id="imgPreview" src="" alt="Preview Image">
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
                                    error_log('Save button clicked');
                                    $productName = $_POST['productName'];
                                    $productCategory = $_POST['category'];
                                    $productPrice = $_POST['price'];
                                    $productGender = $_POST['gender'];
                                    $productDescription = $_POST['description'];
                                    $data = $_POST['image'];
                                    $productModel = new ProductModel(null, $productName, $productCategory, $productPrice, $productDescription, $data, $productGender, strtolower(StatusEnums::INACTIVE));
                                    ProductBUS::getInstance()->addModel($productModel);
                                    ProductBUS::getInstance()->refreshData();
                                    ob_end_clean();
                                    return jsonResponse('success', 'Product added successfully!');
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
                        $updateProductStatus->setStatus(strtolower(StatusEnums::INACTIVE));
                        ProductBUS::getInstance()->updateModel($updateProductStatus);
                        ProductBUS::getInstance()->refreshData();
                        ob_end_clean();
                        return jsonResponse('success', 'Product hidden successfully!');
                    }
                }

                //Handle completely delete product:
                if (isPost()) {
                    if (isset($_POST['completelyDeleteProduct'])) {
                        $productId = $_POST['productId'];
                        $productPreparedToDel = ProductBUS::getInstance()->getModelById($productId);

                        //Check for orders that contain the product:
                        $orders = OrderItemsBUS::getInstance()->getOrderItemsListByProductId($productId);
                        if (count($orders) > 0) {
                            error_log('Cannot delete product. Product is in orders.');
                            ob_end_clean();
                            return jsonResponse('error', 'Cannot delete product. Product is in orders.');
                        } else {
                            foreach (SizeItemsBUS::getInstance()->getModelByProductId($productId) as $sizeItem) {
                                if ($sizeItem->getProductId() == $productId) {
                                    SizeItemsBUS::getInstance()->deleteModel($sizeItem);
                                }
                            }
                            ProductBUS::getInstance()->deleteModel($productPreparedToDel->getId());
                            ProductBUS::getInstance()->refreshData();
                            error_log('Product deleted successfully!');
                            ob_end_clean();
                            return jsonResponse('success', 'Product deleted successfully!');
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