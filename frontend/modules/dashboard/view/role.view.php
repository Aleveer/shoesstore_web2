<?php
$title = 'Roles';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}
include(__DIR__ .'/../inc/head.php');


use backend\bus\PermissionBUS;
use backend\bus\RoleBUS;

$permissionList = PermissionBUS::getInstance()->getAllModels();
$roleList = RoleBUS::getInstance()->getAllModels();
?>


<body>
    <!-- HEADER -->
    <?php include(__DIR__ .'/../inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR MENU -->
            <?php include(__DIR__ .'/../inc/sidebar.php'); ?>

            <!-- MAIN -->
            <main class="col-9 ms-sm-auto col-lg-10 px-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                    <div class="search-group input-group">
                        <input type="text" id="productSearch" class="searchInput form-control">
                        <button type="button" class="btn btn-sm btn-primary align-middle padx-0 pady-0">
                            <span data-feather="search"></span>
                        </button>
                    </div>
                    <div class="btn-toolbar mb-2 mb-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal" 
                                data-bs-target="#addRoleModal" id="addProduct" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <?php include(__DIR__ . '/../view/role.table.php') ?>
            </main>


            <!-- Add Role Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <?php include(__DIR__ . '/../view/role_permission.list.php') ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- edit quantity modal -->
            <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Role</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="">
                        <div class="modal-body">
                            <label for="inputName" class="form-label">Role Name</label>
                            <input type="text" id="inputName" name="inputName" class="form-control">
                            <label for="inputPermission" class="form-label">Permissions</label>
                            <div id="inputPermission" class="form-control">
                                <?php include(__DIR__ . '/../view/role_permission.list.php') ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php include(__DIR__ . '/../inc/app/app.php'); ?>
    <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
</body>
</html>
