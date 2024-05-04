<?php
ob_start();
use backend\bus\RoleBUS;
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;
use backend\enums\StatusEnums;
use backend\services\PasswordUtilities;
use backend\services\session;
use backend\services\validation;

$title = 'Edit Account Information';
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

//Get current logged in user
$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$currentLoggedInUser = UserBUS::getInstance()->getModelById($tokenModel->getUserId());

global $id;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = UserBUS::getInstance()->getModelById($id);
    if ($user === null) {
        die('User does not exist');
    }

    // Check if the current user is allowed to edit the user specified by the id parameter
    $currentUserRole = $currentLoggedInUser->getRoleId();
    $editUserRole = $user->getRoleId();
    $isCurrentUser = $currentLoggedInUser->getId() == $user->getId();

    $canEdit = (($currentUserRole == '1' && $editUserRole != '1') || ($currentUserRole == '1' && $isCurrentUser)) ||
        (($currentUserRole == '2' && $editUserRole > '2') || ($currentUserRole == '2' && $isCurrentUser)) ||
        ($currentUserRole == '3' && $editUserRole == '4');

    if (!$canEdit) {
        die('Access denied');
    }
} else {
    die('User id is missing');
}
?>


<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</div>

<body>
    <?php include (__DIR__ . '/../inc/header.php'); ?>
    <!-- Title -->
    <div class="container-fluid">
        <div class="row">
            <h1 class="text-center">Edit Account</h1>
        </div>
    </div>


    <!-- Edit Content -->
    <div class="container-fluid d-flex justify-content-around w-75 p-5 border border-black">

        <!-- Left Side Container -->
        <div class="container m-0">
            <!--Row 1-->
            <div class="row my-2">
                <div class="col">
                    <label for="inputAccountUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="inputEditAccountUsername" value="<?php $username = UserBUS::getInstance()->getModelById($id)->getUsername();
                    echo $username; ?>">
                </div>

                <div class="col">
                    <label for="inputAccountPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputEditAccountPassword" value="<?php $password = UserBUS::getInstance()->getModelById($id)->getPassword();
                    echo $password; ?>">
                </div>

                <!--Email-->
                <div class="col">
                    <label for="inputAccountEmail" class="form-label">Email</label>
                    <input type="text" class="form-control" id="inputEditAccountEmail" value="<?php $email = UserBUS::getInstance()->getModelById($id)->getEmail();
                    echo $email; ?>">
                </div>
            </div>
            <!-- Row 2 -->
            <div class="row my-2">
                <!-- Name -->
                <div class="col">
                    <label for="inputAccountName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="inputEditAccountName" value="<?php $name = UserBUS::getInstance()->getModelById($id)->getName();
                    echo $name; ?>">
                </div>
                <!--Phone-->
                <div class="col">
                    <label for="inputAccountPhone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="inputEditAccountPhone" value="<?php $phone = UserBUS::getInstance()->getModelById($id)->getPhone();
                    echo $phone; ?>">
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row my-2">
                <!--Address -->
                <div class="col">
                    <label for="inputAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="inputEditAddress" value="<?php $address = UserBUS::getInstance()->getModelById($id)->getAddress();
                    echo $address; ?>">
                </div>
            </div>

            <!-- Row 4-->
            <div class="row my-2">
                <!-- Role -->
                <div class="col">
                    <label for="inputAccountRole" class="form-label">Role</label>
                    <select id="inputEditAccountRole" class="form-select" <?= $isCurrentUser ? 'disabled' : '' ?>>
                        <?php
                        $editUserRole = $user->getRoleId();
                        if ($currentLoggedInUser->getRoleId() == 1): ?>
                            <option value="1" <?= $editUserRole == 1 ? 'selected' : '' ?>>Admin</option>
                            <option value="2" <?= $editUserRole == 2 ? 'selected' : '' ?>>Manager</option>
                            <option value="3" <?= $editUserRole == 3 ? 'selected' : '' ?>>Employee</option>
                            <option value="4" <?= $editUserRole == 4 ? 'selected' : '' ?>>Customer</option>
                        <?php elseif ($currentLoggedInUser->getRoleId() == 2): ?>
                            <option value="2" <?= $editUserRole == 2 ? 'selected' : '' ?>>Manager</option>
                            <option value="3" <?= $editUserRole == 3 ? 'selected' : '' ?>>Employee</option>
                            <option value="4" <?= $editUserRole == 4 ? 'selected' : '' ?>>Customer</option>
                        <?php elseif ($currentLoggedInUser->getRoleId() == 3): ?>
                            <option value="3" <?= $editUserRole == 3 ? 'selected' : '' ?>>Employee</option>
                            <option value="4" <?= $editUserRole == 4 ? 'selected' : '' ?>>Customer</option>
                        <?php endif; ?>
                    </select>
                </div>
                <!--Gender-->
                <div class="col">
                    <label for="inputGender" class="form-label">Gender</label>
                    <select id="inputEditGender" class="form-select">
                        <?php $gender = UserBUS::getInstance()->getModelById($id)->getGender(); ?>
                        <option value="0" <?php echo $gender == 0 ? 'selected' : ''; ?>>Male</option>
                        <option value="1" <?php echo $gender == 1 ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>

                <!--Status-->
                <div class="col">
                    <label for="inputStatus" class="form-label">Status</label>
                    <?php
                    $status = UserBUS::getInstance()->getModelById($id)->getStatus();
                    ?>
                    <select id="inputEditStatus" class="form-select" <?php echo $currentLoggedInUser->getId() == $id ? 'disabled' : ''; ?>>
                        <option value="active" <?php echo $status == StatusEnums::ACTIVE ? 'selected' : ''; ?>>Active
                        </option>
                        <option value="inactive" <?php echo $status == StatusEnums::INACTIVE ? 'selected' : ''; ?>>
                            Inactive</option>
                        <option value="banned" <?php echo $status == StatusEnums::BANNED ? 'selected' : ''; ?>>Banned
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!--Right Side Container-->
        <div>
            <div class="col my-2">
                <label for="inputImg">Image (.JPG, .JPEG, .PNG)</label>
                <input type="file" class="form-control" name="imgProduct" id="inputEditImg" accept=".jpg, .jpeg, .png">
                <div class="col">
                    <img src="<?php echo UserBUS::getInstance()->getModelById($id)->getImage(); ?>" alt="Image"
                        id="imageEdit" style="width: 100px; height: 100px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="col-md-12">
        <form method="POST">
            <div class="text-center">
                <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateEditBtn" name="updateEdtBtnName">Update</button>
            </div>
        </form>
    </div>
    <?php
    if (isPost()) {
        if (isset($_POST['updateEditBtnName'])) {
            $accountUpdate = UserBUS::getInstance()->getModelById($id);
            $username = $_POST['usernameEdit'] ?? '';

            //Handle Password:
            $newPassword = $_POST['passwordEdit'] ?? '';

            if (trim($newPassword) == '') {
                $newPassword = $accountUpdate->getPassword();
            }

            $currentPassword = $accountUpdate->getPassword();
            $newPasswordHash = null;
            if ($newPassword != $currentPassword) {
                $newPasswordHash = PasswordUtilities::getInstance()->hashPassword($newPassword);
            }

            $name = $_POST['nameEdit'] ?? '';
            $email = $_POST['emailEdit'] ?? '';

            //Check for valid email
            if (validation::isValidEmail($email) == false) {
                error_log("Invalid email!");
                ob_end_clean();
                return jsonResponse('error', 'Invalid email!');
            }

            if (UserBUS::getInstance()->isEmailTaken($email, $id)) {
                error_log("Email is already taken!");
                ob_end_clean();
                return jsonResponse('error', 'Email is already taken!');
            }

            $phone = $_POST['phoneEdit'] ?? '';
            if (validation::isValidPhoneNumber($phone) == false) {
                error_log("Invalid phone number!");
                ob_end_clean();
                return jsonResponse('error', 'Invalid phone number!');
            }

            if (UserBUS::getInstance()->isPhoneTaken($phone, $id)) {
                error_log("Phone number is already taken!");
                error_log($phone);
                error_log('User id: ' . $id);
                ob_end_clean();
                return jsonResponse('error', 'Phone number is already taken!');
            }

            $gender = $_POST['genderEdit'] ?? '';
            $status = $_POST['statusEdit'] ?? '';
            $address = $_POST['addressEdit'] ?? '';
            $role = $_POST['roleEdit'] ?? '';

            $accountUpdate->setUsername($username);
            if ($newPasswordHash != null) {
                $accountUpdate->setPassword($newPasswordHash);
            } else {
                $accountUpdate->setPassword($currentPassword);
            }
            $accountUpdate->setName($name);
            $accountUpdate->setEmail($email);
            $accountUpdate->setPhone($phone);
            $accountUpdate->setGender($gender);
            $accountUpdate->setStatus($status);
            $accountUpdate->setAddress($address);
            $accountUpdate->setRoleId($role);

            $imageData = $_POST['imageEdit'];
            $accountUpdate->setImage($imageData);
            UserBUS::getInstance()->updateModel($accountUpdate);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', 'Update account successfully!');
        }
    }
    ?>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_account.js"></script>
    </div>

</body>