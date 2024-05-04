<?php
ob_start();
$title = 'Permissions';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission(6)) {
    die('Access denied');
}

include (__DIR__ . '/../inc/head.php');

use backend\bus\PermissionBUS;

$permissionList = PermissionBUS::getInstance()->getAllModels();
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
                </div>


                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th>Permissions ID</th>
                            <th>Permissions Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($permissionList as $permission): ?>
                            <tr>
                                <td class='col-3'><?= $permission->getId(); ?></td>
                                <td class='col-8'><?= $permission->getName(); ?></td>
                                <td class='col-1'>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editPermissionModal_<?= $permission->getId() ?>">
                                        <span data-feather="tool"></span>
                                        Update
                                    </button>
                                </td>
                            </tr>
                            <!-- Edit permission modal -->
                            <div class="modal fade" id="editPermissionModal_<?= $permission->getId() ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form method="POST" action="your_update_endpoint_here">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Permission</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="inputName" class="form-label">Permission Name</label>
                                                <input type="text" name="inputName"
                                                    id="inputName_<?= $permission->getId() ?>" class="form-control"
                                                    value="<?= $permission->getName() ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>

        <?php
        if (isPost()) {
            //Handle update permission
            if (isset($_POST['submit'])) {
                error_log('Update permission');
                $permissionId = $_POST['id'];
                $permissionName = $_POST['name'];

                if (empty($permissionName)) {
                    ob_end_clean();
                    return jsonResponse('error', 'Permission name cannot be empty');
                }

                $permissionModel = PermissionBUS::getInstance()->getModelById($permissionId);
                $permissionModel->setName($permissionName);
                PermissionBUS::getInstance()->updateModel($permissionModel);
                PermissionBUS::getInstance()->refreshData();
                ob_end_clean();
                return jsonResponse('success', 'Update permission successfully');
            }
        }
        ?>
        <?php include (__DIR__ . '/../inc/app/app.php'); ?>
        <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_permission.js"></script>
</body>