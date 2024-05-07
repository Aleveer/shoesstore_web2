<?php
ob_start();
use backend\bus\RoleBUS;
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;
use backend\enums\StatusEnums;
use backend\models\UserModel;
use backend\services\PasswordUtilities;
use backend\services\session;
use backend\services\validation;

$title = 'Accounts';

if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission(4)) {
    die('Access denied');
}

include (__DIR__ . '/../inc/head.php');
include (__DIR__ . '/../inc/app/app.php');
$userList = UserBUS::getInstance()->getAllModels();

//Get current logged in user
$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());

function showUserList($user, $currentLoggedInUser)
{

    $currentUserRole = $currentLoggedInUser->getRoleId();
    $displayUserRole = $user->getRoleId();
    $isCurrentUser = $currentLoggedInUser->getId() == $user->getId();

    // Admins can edit anyone but not for other admins, admin can edit themselves
    // Managers can edit them self employees and customers, but not admins or other managers
    // Employees can edit customers, but not other employees, managers, or admins:
    $canEdit = (($currentUserRole == '1' && $displayUserRole != '1') || ($currentUserRole == '1' && $isCurrentUser)) ||
        (($currentUserRole == '2' && $displayUserRole > '2') || ($currentUserRole == '2' && $isCurrentUser)) ||
        ($currentUserRole == '3' && $displayUserRole == '4');

    echo "<tr>";
    echo "<td class='col-1'><img src='" . $user->getImage() . "' style='width: 50px; height: 50px;' alt='ATR'></td>";
    echo "<td class='col-1'>" . $user->getUsername() . "</td>";
    echo "<td class='col-2'>" . $user->getName() . "</td>";
    echo "<td class='col-2'>" . $user->getEmail() . "</td>";
    echo "<td class='col-1'>" . $user->getPhone() . "</td>";
    echo "<td class='col-2'>" . $user->getAddress() . "</td>";
    echo "<td class='col-1'>" . RoleBUS::getInstance()->getModelById($user->getRoleId())->getName() . "</td>";
    echo "<td class='col-1'>" . $user->getStatus() . "</td>";
    echo "<td>";
    if ($canEdit) {
        echo "<a href='http://localhost/frontend/index.php?module=dashboard&view=account.update&id=" . $user->getId() . "' class='btn btn-sm btn-primary'>";
        echo "<span data-feather='tool'></span>";
        echo "</a>";
    }
    if ($user->getId() !== $currentLoggedInUser->getId() && $canEdit) {
        echo "<button class='btn btn-sm btn-warning' id='lockAccountBtn_" . $user->getId() . "'>";
        echo "<span data-feather='lock'></span>";
        echo "</button>";
        echo "<button class='btn btn-sm btn-danger' id='unlockAccountBtn_" . $user->getId() . "' name='unlockAccountBtn'>";
        echo "<span data-feather='unlock'></span>";
    }
    echo "</td>";
    echo "</tr>";
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
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                            data-bs-target="#addModal" id="addAcount" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>
                <form action="" method="POST">
                    <div class="search-group input-group">
                        <input type="text" id="accountSearch" name="accountSearch" class="searchInput form-control"
                            placeholder="Search account here.." style="width: 200px;">
                        <button type="submit" class="btn btn-sm btn-primary align-middle padx-0 pady-0"
                            name="accountSearchName">
                            <span data-feather="search"></span>
                        </button>
                    </div>
                </form>
                <!-- BODY DATABASE -->
                <table class="table align-middle table-borderless table-hover text-start">
                    <thead>
                        <tr class="align-middle">
                            <th></th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <?php
                    if (!isPost() || (isPost() && !isset($_POST['accountSearchName']))) {
                        if (count($userList) > 0) {
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;

                            if (!isPost() || (isPost() && !isset($_POST['accountSearchName']))) {
                                $userChunks = array_chunk($userList, 12);
                                $accountsForCurrentPage = $userChunks[$page - 1];
                                foreach ($accountsForCurrentPage as $account): ?>
                                    <?= showUserList($account, $userModel); ?>
                                <?php endforeach;
                            }

                            // Calculate the total number of pages
                            $totalPages = count($userChunks);

                            echo "<nav aria-label='Page navigation example'>";
                            echo "<ul class='pagination justify-content-center'>";

                            // Add previous button
                            if ($page > 1) {
                                echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=account.view&page=" . ($page - 1) . "'>Previous</a></li>";
                            }

                            for ($i = 1; $i <= $totalPages; $i++) {
                                // Highlight the current page
                                if ($i == $page) {
                                    echo "<li class='page-item active'><a class='page-link' href='?module=dashboard&view=account.view&page=$i'>$i</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=account.view&page=$i'>$i</a></li>";
                                }
                            }

                            // Add next button
                            if ($page < $totalPages) {
                                echo "<li class='page-item'><a class='page-link' href='?module=dashboard&view=account.view&page=" . ($page + 1) . "'>Next</a></li>";
                            }

                            echo "</ul>";
                            echo "</nav>";
                        }
                    }
                    ?>
                    <?php
                    // Handle search function:
                    if (isPost()) {
                        $filterAll = filter();
                        if (isset($_POST['accountSearchName'])) {
                            $searchQuery = $_POST['accountSearch'];
                            $searchResult = array();
                            if (empty($searchQuery) || trim($searchQuery) == "") {
                                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                echo "Please input the search bar to search!";
                                echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                echo "</div>";
                            } else {
                                $searchResult = UserBUS::getInstance()->searchModel($searchQuery, ['id', 'username', 'email', 'name', 'phone', 'address']);
                                if (empty($searchResult) || count($searchResult) == 0) {
                                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                    echo "No result found!";
                                    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                    echo "</div>";
                                } else {
                                    if (isset($_GET['page'])) {
                                        header('Location: http://localhost/frontend/index.php?module=dashboard&view=account.view');
                                        exit;
                                    }
                                    if (count($searchResult) > 0) {
                                        foreach ($searchResult as $account) {
                                            showUserList($account, $userModel);
                                        }
                                    } else {
                                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                                        echo "No result found!";
                                        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                                        echo "</div>";
                                    }
                                }
                            }
                        }
                    }
                    ?>
        </div>
    </div>
    </table>
    </main>

    <!-- Add modal -->
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" style="width: 100%">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label for="inputUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="inputUsername" name="inputUsernameName">
                        </div>
                        <div class="col-md-3">
                            <label for="inputPassword" class="form-label">Password</label>
                            <input type="password" id="inputPassword" name="inputPasswordName" class="form-control">
                        </div>
                        <div class="col-5">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="inputEmail" name="inputEmailName">
                        </div>
                        <div class="col-5">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputName" name="inputNameName">
                        </div>
                        <div class="col-3">
                            <label for="inputPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="inputPhone" name="inputPhoneName">
                        </div>
                        <div class="col-md-2">
                            <label for="inputGender" class="form-label">Gender</label>
                            <select id="inputGender" class="form-select">
                                <option value="0" selected>Male</option>
                                <option value="1">Female</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputRole" class="form-label">Role</label>
                            <select name="" id="inputRole" class="form-select">
                                <?php if ($userModel->getRoleId() == 1): ?>
                                    <option value="2">Manager</option>
                                    <option value="3">Employee</option>
                                    <option value="4">Customer</option>
                                <?php elseif ($userModel->getRoleId() == 2): ?>
                                    <option value="3">Employee</option>
                                    <option value="4">Customer</option>
                                <?php elseif ($userModel->getRoleId() == 3): ?>
                                    <option value="4">Customer</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">Address</label>
                            <input type="text" name="inputAddressName" id="inputAddress" class="form-control">
                        </div>
                        <div class="col-6  userImg">
                            <img id="imgPreview" src="" alt="Preview Image" a class="img-circle">
                        </div>
                        <div class="col-6">
                            <label for="inputImg">Image</label>
                            <input type="file" class="form-control" name="imgaccount" id="inputImg" accept="image/*">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php

    if (isPost()) {
        if (isset($_POST['lockBtn'])) {
            $id = $_POST['id'];
            $user = UserBUS::getInstance()->getModelById($id);
            $user->setStatus(strtolower(StatusEnums::BANNED));
            UserBUS::getInstance()->updateModel($user);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', "Account locked successfully!");
        }

        if (isset($_POST['unlockBtn'])) {
            $id = $_POST['id'];
            $user = UserBUS::getInstance()->getModelById($id);
            $user->setStatus(strtolower(StatusEnums::INACTIVE));
            UserBUS::getInstance()->updateModel($user);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', "Account unlocked successfully!");
        }

        if (isset($_POST['saveBtn'])) {
            $username = $_POST['username'];
            //Check validation:
            // if (validation::isValidUsername($username) == false) {
            //     echo "<script>alert('Username is invalid!')</script>";
            //     error_log("Username is invalid!");
            //     return;
            // }
            $newPassword = $_POST['password'];
            //Check validation:
            // if (validation::isValidPassword($newPassword) == false) {
            //     echo "<script>alert('Password is invalid!')</script>";
            //     error_log("Password is invalid!");
            //     return;
            // }
    
            $password = PasswordUtilities::hashPassword($newPassword);
            $email = $_POST['email'];
            if (UserBUS::getInstance()->isEmailTaken($email, $id)) {
                error_log("Email is already taken!");
                ob_end_clean();
                return jsonResponse('error', "Email is already taken!");
            }

            //check validation:
            if (validation::isValidEmail($email) == false) {
                error_log("Email is invalid!");
                ob_end_clean();
                return jsonResponse('error', "Email is invalid!");
            }

            $name = $_POST['name'];
            //check validation:
            // if (validation::isValidName($name) == false) {
            //     echo "<script>alert('Name is invalid!')</script>";
            //     error_log("Name is invalid!");
            //     return;
            // }
    
            $phone = $_POST['phone'];
            if (validation::isValidPhoneNumber($phone) == false) {
                error_log("Phone number is invalid!");
                ob_end_clean();
                return jsonResponse('error', "Phone number is invalid!");
            }

            if (UserBUS::getInstance()->isPhoneTaken($phone, $id)) {
                error_log("Phone number is already taken!");
                ob_end_clean();
                return jsonResponse('error', "Phone number is already taken!");
            }

            $gender = $_POST['gender'];
            $role = $_POST['role'];
            $address = $_POST['address'];
            if (validation::isValidAddress($address) == false) {
                error_log("Address is invalid!");
                return jsonResponse('error', "Address is invalid!");
            }
            $image = $_POST['image'];

            $newUser = new UserModel(null, $username, $password, $email, $name, $phone, $gender, $image, $role, StatusEnums::INACTIVE, $address, null, null, null, null);
            UserBUS::getInstance()->addModel($newUser);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', "Account added successfully!");
        }
    }
    ?>
    <?php include (__DIR__ . '/../inc/app/app.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/account_status.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_account.js"></script>
</body>

</html>