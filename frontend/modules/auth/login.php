<?php

use backend\bus\UserBUS;
use backend\bus\UserPermissionBUS;
use backend\bus\PermissionBUS;
use backend\bus\RoleBUS;
use backend\bus\RolePermissionBUS;
use backend\bus\TokenLoginBUS;
use backend\services\PasswordUtilities;
use backend\services\session;
use backend\models\TokenLoginModel;
use backend\enums\StatusEnums;

if (!defined('_CODE')) {
    die('Access denied');
}

$data = [
    'pageTitle' => 'Đăng nhập'
];

if (isLogin()) {
    redirect('?module=indexphp&action=userhomepage');
}

if (isPost()) {
    $filterAll = filter();
    $response = ['success' => false, 'msg' => ''];

    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        $email = $filterAll['email'];
        $password = $filterAll['password'];
        $userQuery = UserBUS::getInstance()->getModelByEmail($email);
        if (!empty($userQuery)) {
            $passwordHash = $userQuery->getPassword();
            // Kiểm tra password verify
            if (PasswordUtilities::getInstance()->verifyPassword($password, $passwordHash)) {
                // Tạo tokenLogin
                $tokenLogin = sha1(uniqid() . time());

                $loginTkn = new TokenLoginModel(0, $userQuery->getId(), $tokenLogin, date("Y-m-d H:i:s"));
                $insertTokenLoginStatus = TokenLoginBUS::getInstance()->addModel($loginTkn);

                if ($insertTokenLoginStatus) {
                    $status = $userQuery->getStatus();
                    error_log('User status before update: ' . $status);
                    if ($status === StatusEnums::INACTIVE || $status === StatusEnums::ACTIVE) {
                        $userQuery->setStatus(StatusEnums::ACTIVE);
                        UserBUS::getInstance()->updateModel($userQuery);
                        UserBUS::getInstance()->refreshData();
                        error_log('User status updated to active');
                    }
                    session::getInstance()->setSession('tokenLogin', $tokenLogin);
                    redirect('?module=indexphp&action=userhomepage');
                } else {
                    session::getInstance()->setFlashData('msg', 'Cannot login! Please try again!');
                    session::getInstance()->setFlashData('msg_type', 'danger');
                }
            } else {
                session::getInstance()->setFlashData('msg', 'Incorrect password!');
                session::getInstance()->setFlashData('msg_type', 'danger');
            }
        } else {
            session::getInstance()->setFlashData('msg', 'Email does not exist!');
            session::getInstance()->setFlashData('msg_type', 'danger');
        }
    } else {
        session::getInstance()->setFlashData('msg', 'Please fill in all fields!');
        session::getInstance()->setFlashData('msg_type', 'danger');
    }
    // redirect('?module=auth&action=login');
}
//header('Content-Type: application/json');
//echo json_encode($response);

$msg = session::getInstance()->getFlashData('msg');
$msgType = session::getInstance()->getFlashData('msg_type');
?>

<div id="header">
    <?php layouts('header', $data); ?>
</div>

<body>
    <div class="row">
        <div class="col-4" style="margin:50px auto;">
            <h2 class="cw" style="text-align: center; text-transform: uppercase;">Login</h2>
            <?php if (!empty($msg)) {
                getMsg($msg, $msgType);
            } ?>
            <form action="" method="post">
                <div class="form-group mg-form">
                    <label class="cw" for="">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Your email address...">
                </div>
                <div class="form-group mg-form">
                    <label class="cw" for="">Password</label>
                    <input name="password" type="password" class="form-control" placeholder="Your password...">
                </div>

                <button type="submit" class="btn btn-primary btn-block mg-form"
                    style="width:100%; margin-top:16px;">Login</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=forgot">Forgot password?</a></p>
                <p class="text-center"><a href="?module=auth&action=register">Register</a></p>
            </form>
        </div>
    </div>
</body>