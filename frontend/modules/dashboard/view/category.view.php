<?php
ob_start();
use backend\bus\ProductBUS;
use backend\models\CategoriesModel;

$title = 'Categories';

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
include (__DIR__ . '/../inc/app/app.php');
use backend\bus\CategoriesBUS;

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
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                            data-bs-target="#addModal" id="addCategory" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <!-- BODY DATABASE -->
                <table class="table align-middle table-borderless table-hover text-start">
                    <thead>
                        <tr class="align-middle">
                            <th>Id</th>
                            <th>Name</th>
                            <th>Product Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php foreach (CategoriesBUS::getInstance()->getAllModels() as $categories): ?>
                        <tbody>
                            <tr>
                                <td class='col-1'><?= $categories->getId() ?></td>
                                <td class='col-2'><?= $categories->getName() ?></td>
                                <td class='col-2'>
                                    <?= count(ProductBUS::getInstance()->searchModel($categories->getId(), ['category_id'])); ?>
                                </td>
                                <td class='col-2 categoryAction'>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $categories->getId() ?>">
                                        <span data-feather="tool"></span>
                                        Update
                                    </button>
                                    <?php if (count(ProductBUS::getInstance()->searchModel($categories->getId(), ['category_id'])) > 0) { ?>
                                        <button class="btn btn-sm btn-secondary" id='deleteCategoryBtnId'
                                            name='deleteCategoryBtnName' disabled style="display: none;">
                                            <span data-feather="trash-2"></span>
                                            Delete
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn btn-sm btn-danger" id='deleteCategoryBtnId'
                                            name='deleteCategoryBtnName'>
                                            <span data-feather="trash-2"></span>
                                            Delete
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <!-- Edit modal -->
                            <div class="modal fade" id="editModal<?= $categories->getId() ?>" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true" style="width: 100%">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Category</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="row g-3" id="editForm<?= $categories->getId() ?>">
                                                <div class="col-md-4">
                                                    <label for="inputName" class="form-label">Category name</label>
                                                    <input type="text" class="form-control"
                                                        id="inputCategoryName<?= $categories->getId() ?>"
                                                        value="<?php echo $categories->getName(); ?>" name="categoryName">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" id="editButtonId"
                                                        name="editButtonName">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    <?php endforeach; ?>
                </table>
            </main>

            <!-- Add modal -->
            <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true" style="width: 100%">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Category</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3">
                                <div class="col-md-4">
                                    <label for="inputName" class="form-label">Category name</label>
                                    <input type="text" class="form-control" id="inputCategoryName" name="categoryName">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="saveButton"
                                name="saveBtnName">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            if (isPost()) {
                if (isset($_POST['saveBtn'])) {
                    error_log('Save button clicked');
                    $categoryName = $_POST['categoryName'];
                    $categoryModel = new CategoriesModel(null, null);
                    $categoryModel->setName($categoryName);

                    CategoriesBUS::getInstance()->addModel($categoryModel);
                    CategoriesBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Category added successfully');
                }
            }
            ?>

            <?php
            if (isPost()) {
                if (isset($_POST['editButtonName'])) {
                    error_log('Edit button clicked');
                    $categoryName = $_POST['name'];
                    $categoryId = $_POST['id'];
                    $categoryModel = CategoriesBUS::getInstance()->getModelById($categoryId);
                    $categoryModel->setName($categoryName);

                    CategoriesBUS::getInstance()->updateModel($categoryModel);
                    CategoriesBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Category updated successfully');
                }
            }
            ?>

            <?php
            if (isPost()) {
                if (isset($_POST['deleteCategoryBtn'])) {
                    $categoryId = $_POST['categoryId'];
                    if (CategoriesBUS::getInstance()->deleteModel($categoryId)) {
                        CategoriesBUS::getInstance()->refreshData();
                        ob_end_clean();
                        return jsonResponse('success', 'Category deleted successfully');
                    } else {
                        ob_end_clean();
                        return jsonResponse('error', 'Category cannot be deleted');
                    }
                }
            }
            ?>
            <?php include (__DIR__ . '/../inc/app/app.php'); ?>
            <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_category.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_category.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/delete_category.js"></script>
</body>

</html>