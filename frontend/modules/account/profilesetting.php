<?php
use backend\bus\UserBUS;
use backend\bus\OrdersBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\ProductsBUS;
use backend\bus\CategoriesBUS;
use backend\bus\TokenLoginBUS;
use backend\services\PasswordUtilities;
use backend\services\session;
use backend\services\validation;

requireLogin();

$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
$ordersListFromUser = OrdersBUS::getInstance()->getOrdersByUserId($userModel->getId());
//$orderItemsListBasedOnOrderFromUser = OrderItemsBUS::getInstance()->getAllModels();
?>

<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/account-setting.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php layouts("header") ?>
</div>

<body>
    <div class="container light-style flex-grow-1 container-p-y" style="margin-left: 5rem;">
        <h4 class="font-weight-bold py-3 mb-4">
            Account settings
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body media align-items-center">
                                <img src="<?php echo $userModel->getImage(); ?>" alt="" class="d-block ui-w-80"
                                    name="showImage" id="showImageId">
                                <div class="media-body ml-4">
                                    <label class="btn btn-outline-primary">
                                        Upload new photo
                                        <input type="file" class="account-settings-fileinput" name="imageUploadButton"
                                            id="imageUploadIdButton" accept=".jpg, .jpeg, .png">
                                    </label> &nbsp;
                                    <?php
                                    if (isPost()) {
                                        if (isset($_POST['image'])) {
                                            $data = $_POST['image'];
                                            $userModel->setImage($data);
                                            UserBUS::getInstance()->updateModel($userModel);
                                            UserBUS::getInstance()->refreshData();
                                        }
                                    }
                                    ?>
                                    <button type="button" class="btn btn-default md-btn-flat" name="resetButton"
                                        id="resetButton">Reset</button>
                                    <div class="text-bold small mt-1">Allowed JPG, JPEG or PNG. Max size of 1MB</div>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control mb-1" name="username" id="usernameId"
                                        value="<?php echo $userModel->getUsername() ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="account-name" id="accountNameId"
                                        value="<?php echo $userModel->getName() ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" class="form-control mb-1" name="mailAccount" id="mailAccountId"
                                        value="<?php echo $userModel->getEmail() ?>" required>
                                    <!-- <div class="alert alert-warning mt-3">
                                        Your email is not confirmed. Please check your inbox.<br>
                                        <a href="javascript:void(0)">Resend confirmation</a>
                                    </div> -->
                                </div>
                                <div class="form-group">
                                    <!-- Radio button for choosing gender male or female -->
                                    <label class="form-label">Gender</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male"
                                            value="male" <?php echo ($userModel->getGender() == 0) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="male">
                                            Male
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female"
                                            value="female" <?php echo ($userModel->getGender() == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="female">
                                            Female
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-right mt-3">
                                        <button type="button" class="btn btn-primary" name="saveButton"
                                            id="applyChangesFirstCard">Apply changes</button>&nbsp;
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (isPost()) {
                                if (isset($_POST['resetButton'])) {
                                    // Convert the gender to a number: 0 for male, 1 for female
                                    $gender = isset($_POST['maleGender']) && $_POST['maleGender'] ? 0 : (isset($_POST['femaleGender']) && $_POST['femaleGender'] ? 1 : null);
                                    // Check for any changes
                                    if (
                                        $_POST['username'] != $userModel->getUsername() ||
                                        $_POST['account-name'] != $userModel->getName() ||
                                        $_POST['mailAccount'] != $userModel->getEmail() ||
                                        $gender != $userModel->getGender()
                                    ) {
                                        // Update the user model with the new values
                                        $userModel->setUsername($_POST['username']);
                                        $userModel->setName($_POST['account-name']);
                                        $userModel->setEmail($_POST['mailAccount']);
                                        $userModel->setGender($gender);
                                        // Save the updated user model
                                        UserBUS::getInstance()->updateModel($userModel);
                                        UserBUS::getInstance()->refreshData();
                                    }
                                }
                                //TODO: Fix the bug that it skips checking validation.
                                if (isset($_POST['saveButton'])) {
                                    // Update the user model with the new values
                                    $username = $_POST['username'];
                                    $accountName = $_POST['account-name'];
                                    $mailAccount = $_POST['mailAccount'];
                                    $gender = ($_POST['gender'] == 'male') ? 0 : 1;

                                    $userBUS = UserBUS::getInstance();

                                    if ($username != $userModel->getUsername() && !$userBUS->isUsernameTaken($username)) {
                                        $userModel->setUsername($username);
                                    }

                                    if ($accountName != $userModel->getName()) {
                                        $userModel->setName($accountName);
                                    }

                                    if ($mailAccount != $userModel->getEmail() && $mailAccount != "" && !$userBUS->isEmailTaken($mailAccount) && validation::isValidEmail($mailAccount)) {
                                        $userModel->setEmail($mailAccount);
                                    }

                                    if ($gender != $userModel->getGender()) {
                                        $userModel->setGender($gender);
                                    }

                                    // Save the updated user model
                                    $userBUS->updateModel($userModel);
                                    $userBUS->refreshData();
                                    echo '<script>alert("Apply changes successfully!")</script>';
                                }
                            }
                            ?>

                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input type="password" class="form-control" name="currentPassword"
                                        id="currentPassword">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" class="form-control" name="newPassword" id="newPassword">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input type="password" class="form-control" name="repeatNewPassword"
                                        id="repeatNewPassword">
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" name="saveButtonPassword"
                                        id="applyChangesSecondCard">Change password</button>&nbsp;
                                </div>
                                <?php
                                if (isPost()) {
                                    if (isset($_POST['saveButtonPassword'])) {
                                        error_log("Change password button clicked!");

                                        $currentPassword = $_POST['currentPassword'];
                                        $newPassword = $_POST['newPassword'];
                                        $repeatNewPassword = $_POST['repeatNewPassword'];
                                        $userBUS = UserBUS::getInstance();

                                        $passwordHash = $userModel->getPassword();
                                        error_log("Current password hash: $passwordHash");

                                        if (!PasswordUtilities::getInstance()->verifyPassword($currentPassword, $passwordHash)) {
                                            error_log("Current password is incorrect!");
                                            //echo '<script>alert("Current password is incorrect!")</script>';
                                            echo json_encode(['success' => false, 'message' => 'Current password is incorrect!']);
                                            return;
                                        }

                                        if ($newPassword != $repeatNewPassword) {
                                            error_log("New passwords do not match!");
                                            echo '<script>alert("New passwords do not match!")</script>';
                                            return;
                                        }

                                        $newPasswordHash = PasswordUtilities::getInstance()->hashPassword($newPassword);
                                        error_log("New password hash: $newPasswordHash");

                                        $userModel->setPassword($newPasswordHash);
                                        $result = $userBUS->updateModel($userModel);
                                        error_log("Update result: $result");

                                        if (!$result) {
                                            error_log("Failed to change password!");
                                            //echo '<script>alert("Failed to change password!")</script>';
                                            echo json_encode(['success' => false, 'message' => 'Failed to change password!']);
                                            return;
                                        }

                                        $userBUS->refreshData();
                                        error_log("Changed password successfully!");
                                        echo json_encode(['success' => true, 'message' => 'Changed password successfully!']);
                                        echo '<script>location.reload();</script>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-info">
                            <hr class="border-light m-0">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Contacts</h6>
                                <div class="form-group">
                                    <label class="form-label">Phone:</label>
                                    <input type="text" class="form-control" name="phone-customer" id="phoneField"
                                        value="<?php echo $userModel->getPhone() ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Address:</label>
                                    <input type="text" class="form-control" name="address-customer" id="addressField"
                                        value="<?php echo $userModel->getAddress() ?>">
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" name="saveContactButton"
                                        id="applyChangesThirdCard">Apply</button>&nbsp;
                                </div>

                                <?php
                                if (isPost()) {
                                    if (isset($_POST['saveContactButton'])) {
                                        $phone = $_POST['phone-customer'];
                                        $address = $_POST['address-customer'];
                                        $userBUS = UserBUS::getInstance();

                                        if ($phone != $userModel->getPhone()) {
                                            if (UserBUS::getInstance()->isPhoneTaken($phone)) {
                                                error_log("Phone number is already taken!");
                                                echo '<script>alert("Phone number is already taken!")</script>';
                                            } else {
                                                if (validation::isValidPhoneNumber($phone)) {
                                                    $userModel->setPhone($phone);
                                                } else {
                                                    error_log("Invalid phone number!");
                                                    echo '<script>alert("Invalid phone number!")</script>';
                                                }
                                            }
                                        }

                                        if ($address != $userModel->getAddress() && validation::isValidAddress($address)) {
                                            $userModel->setAddress($address);
                                        }

                                        // Save the updated user model
                                        $userBUS->updateModel($userModel);
                                        $userBUS->refreshData();
                                        echo '<script>alert("Apply changes successfully!")</script>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/profile_setting.js"></script>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<div id="footer">
    <?php layouts("footer") ?>
</div>