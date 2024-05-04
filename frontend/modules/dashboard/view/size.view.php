<?php
ob_start();
use backend\bus\SizeItemsBUS;
use backend\models\SizeModel;

$title = 'Sizes';

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
use backend\bus\SizeBUS;

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
                            data-bs-target="#addModal" id="addSize" class="addBtn">
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
                    <?php foreach (SizeBUS::getInstance()->getAllModels() as $sizes): ?>
                        <tbody>
                            <tr>
                                <td class='col-1'><?= $sizes->getId() ?></td>
                                <td class='col-2'><?= $sizes->getName() ?></td>
                                <td class='col-2'>
                                    <?= count(SizeItemsBUS::getInstance()->searchModel($sizes->getId(), ['size_id'])); ?>
                                </td>
                                <td class='col-2 sizeAction'>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $sizes->getId() ?>">
                                        <span data-feather="tool"></span>
                                        Update
                                    </button>
                                    <?php if (count(SizeItemsBUS::getInstance()->searchModel($sizes->getId(), ['size_id'])) > 0) { ?>
                                        <button class="btn btn-sm btn-secondary" id='deleteSizeBtnId' name='deleteSizeBtn'
                                            disabled style="display: none;">
                                            <span data-feather="trash-2"></span>
                                            Delete
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn btn-sm btn-danger" id='deleteSizeBtnId' name='deleteSizeBtn'>
                                            <span data-feather="trash-2"></span>
                                            Delete
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <!-- Edit modal -->
                            <div class="modal fade" id="editModal<?= $sizes->getId() ?>" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true" style="width: 100%">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Size</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="row g-3" id="editForm<?= $sizes->getId() ?>">
                                                <div class="col-md-4">
                                                    <label for="inputName" class="form-label">Size:</label>
                                                    <input type="number" class="form-control"
                                                        id="inputSizeName<?= $sizes->getId() ?>"
                                                        value="<?php echo preg_replace('/[^0-9]/', '', $sizes->getName()); ?>"
                                                        name="sizeName">
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
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Size</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3">
                                <div class="col-md-4">
                                    <label for="inputName" class="form-label">Size:</label>
                                    <input type="number" class="form-control" id="inputSizeName" name="sizeName">
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
                    $sizeName = $_POST['sizeName'];
                    $sizeName = 'Size ' . $sizeName;
                    foreach (SizeBUS::getInstance()->getAllModels() as $size) {
                        if ($size->getName() == $sizeName) {
                            error_log('Size already exists');
                            ob_end_clean();
                            return jsonResponse('error', 'Size already exists');
                        }
                    }
                    $sizeModel = new SizeModel(null, null);
                    $sizeModel->setName($sizeName);
                    SizeBUS::getInstance()->addModel($sizeModel);
                    SizeBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Size added successfully');
                }
            }
            ?>

            <?php
            if (isPost()) {
                if (isset($_POST['editButtonName'])) {
                    error_log('Edit button clicked');
                    $sizeName = $_POST['name'];
                    $id = $_POST['id'];
                    $sizeModel = SizeBUS::getInstance()->getModelById($id);
                    $sizeName = 'Size ' . $sizeName;
                    if ($sizeName != $sizeModel->getName()) {
                        foreach (SizeBUS::getInstance()->getAllModels() as $size) {
                            if ($size->getName() == $sizeName) {
                                error_log('Size already exists');
                                ob_end_clean();
                                return jsonResponse('error', 'Size already exists');
                            }
                        }
                    }

                    $sizeId = $_POST['id'];
                    $sizeModel = SizeBUS::getInstance()->getModelById($sizeId);
                    $sizeModel->setName($sizeName);

                    SizeBUS::getInstance()->updateModel($sizeModel);
                    SizeBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Size updated successfully');
                }
            }
            ?>

            <?php
            if (isPost()) {
                if (isset($_POST['deleteSizeBtn'])) {
                    $sizeId = $_POST['sizeId'];
                    if (SizeBUS::getInstance()->deleteModel($sizeId)) {
                        SizeBUS::getInstance()->refreshData();
                        ob_end_clean();
                        return jsonResponse('success', 'Size deleted successfully');
                    } else {
                        ob_end_clean();
                        return jsonResponse('error', 'Size cannot be deleted because it is being used in a product.');
                    }
                }
            }
            ?>
            <?php include (__DIR__ . '/../inc/app/app.php'); ?>
            <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_size.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_size.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/delete_size.js"></script>
</body>

</html>