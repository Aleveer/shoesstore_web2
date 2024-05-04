<?php
ob_start();
use backend\bus\RolePermissionBUS;
use backend\models\RolePermissionsModel;

$title = 'Roles Setup';
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
use backend\bus\RoleBUS;

$permissionList = PermissionBUS::getInstance()->getAllModels();
$roleList = RoleBUS::getInstance()->getAllModels();
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
                        <!-- <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                            data-bs-target="#addRoleModal" id="addProduct" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button> -->
                    </div>
                </div>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th>Role ID</th>
                            <th>Role Name</th>
                            <th>Permission</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roleList as $role) { ?>
                            <tr>
                                <td class='col-2'><?= $role->getId() ?></td>
                                <td class='col-3'><?= $role->getName() ?></td>
                                <td class='col-3'>
                                    <?php
                                    $permissions = RolePermissionBUS::getInstance()->searchModel($role->getId(), ['role_id']);
                                    if ($permissions != null) {
                                        foreach ($permissions as $permission) {
                                            echo PermissionBUS::getInstance()->getModelById($permission->getPermissionId())->getName() . "<br>";
                                        }
                                    } else {
                                        echo "No permission";
                                    }
                                    ?>
                                </td>
                                <td class='col-1'>
                                    <?php if ($role->getName() !== 'customer' && $role->getName() !== 'admin') { ?>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editRoleModal_<?= $role->getId() ?>">
                                            <span data-feather="tool"></span>
                                            Update
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <!-- Edit Permission Modal -->
                            <div class="modal fade" id="editRoleModal_<?= $role->getId() ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Role Permission</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="">
                                            <div class="modal-body">
                                                <label for="inputName" class="form-label">Role Name</label>
                                                <input type="text" id="inputName_<?= $role->getId() ?>" name="inputName"
                                                    class="form-control" value="<?php echo $role->getName() ?>" readonly>
                                                <label for="inputPermission" class="form-label">Permissions</label>
                                                <div class="form-control">
                                                    <?php foreach ($permissionList as $permission): ?>
                                                        <input class="form-check-input mx-1 col-1" type="checkbox"
                                                            value="<?= $permission->getId() ?>"
                                                            id="<?= $permission->getId() ?>">
                                                        <label class="form-check-label col-5" for="<?= $permission->getId() ?>">
                                                            <?= $permission->getName() ?>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" id="updateBtnId"
                                                    name="updateBtnName">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Edit Role Modal -->
                        <?php } ?>
                    </tbody>
                </table>
            </main>

            <!-- Add Role Modal -->
            <!-- <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Role</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="">
                            <div class="modal-body">
                                <label for="inputName" class="form-label">Role Name</label>
                                <input type="text" id="inputName" name="inputName" class="form-control">
                                <label for="inputPermission" class="form-label">Permissions</label>
                                <div id="inputPermission" class="form-control">
                                    <?php foreach ($permissionList as $permission): ?>
                                        <input class="form-check-input mx-1 col-1" type="checkbox"
                                            value="<?= $permission->getId() ?>" id="<?= $permission->getId() ?>">
                                        <label class="form-check-label col-5" for="<?= $permission->getId() ?>">
                                            <?= $permission->getName() ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
        </div>
        <?php
        //Handle update role permission:
        if (isset($_POST['updateBtnName'])) {
            error_log("Update role permission");

            $roleId = $_POST['id'];
            $roleName = $_POST['name'];
            $permissions = isset($_POST['permissions']) ? array_unique($_POST['permissions']) : null;

            // Delete all existing permissions for the role
            RolePermissionBUS::getInstance()->deleteModelByRoleId($roleId);

            if (!is_null($permissions)) {
                foreach ($permissions as $permission) {
                    // Add each permission
                    $rolePermission = new RolePermissionsModel(null, null, null);
                    $rolePermission->setRoleId($roleId);
                    $rolePermission->setPermissionId($permission);
                    RolePermissionBUS::getInstance()->addModel($rolePermission);
                }
            }
            RolePermissionBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', 'Update role permission successfully');
        }
        ?>
        <?php include (__DIR__ . '/../inc/app/app.php'); ?>
        <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_roles_permissions.js"></script>
</body>

</html>