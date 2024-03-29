<?php
require_once __DIR__ . '/../../../backend/bus/user_bus.php';
require_once __DIR__ . '/../../../backend/bus/user_permission_bus.php';
require_once __DIR__ . '/../../../backend/bus/permission_bus.php';
require_once __DIR__ . '/../../../backend/bus/role_bus.php';
require_once __DIR__ . '/../../../backend/bus/role_permissions_bus.php';
require_once __DIR__ . '/../../../backend/services/password-utilities.php';

if (!defined('_CODE')) {
    die('Access denied');
}

$data = [
    'pageTitle' => 'Đăng nhập'
];

layouts('header', $data);

if (isLogin()) {
    redirect('?module=indexphp&action=userhomepage');
}

if (isPost()) {
    $filterAll = filter();
    $response = ['success' => false, 'msg' => ''];

    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        $email = $filterAll['email'];
        $password = $filterAll['password'];
        //TODO: Double check the code if it's actually logged in successfully, after logging in successfully, redirect to the user's homepage:
        //TODO: Fix weird bug that will never direct.
        $userQuery = UserBUS::getInstance()->getModelByEmail($email);
        if (!empty($userQuery)) {
            $passwordHash = $userQuery->getPassword();
            if (PasswordUtilities::getInstance()->verifyPassword($password, $passwordHash)) {
                $_SESSION['userId'] = $userQuery->getId();
                $response['success'] = true;
                $response['msg'] = 'Login successful!';
            } else {
                $response['msg'] = 'Incorrect password!';
            }
        } else {
            $response['msg'] = 'Email does not exist!';
        }
    } else {
        $response['msg'] = 'Please enter email and password!';
    }
}
//header('Content-Type: application/json');
//echo json_encode($response);
?>

<div class="row">
    <div class="col-4" style="margin:50px auto;">
        <h2 class="cw" style="text-align: center; text-transform: uppercase;">Đăng Nhập</h2>
        <?php if (!empty($msg)) {
            getMsg($msg, $msgType);
        } ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label class="cw" for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ Email...">
            </div>
            <div class="form-group mg-form">
                <label class="cw" for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mật khẩu...">
            </div>

            <button type="submit" class="btn btn-primary btn-block mg-form" style="width:100%; margin-top:16px;">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng kí tài khoản</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer');
?>