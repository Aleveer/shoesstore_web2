<!-- Quên mật khẩu -->

<!-- Đăng nhập tài khoản -->

<?php
require_once __DIR__.'/../../../backend/bus/user_bus.php';
use services\session;

if (!defined('_CODE')) {
    die('Access denied');
}

$data = [
    'pageTitle' => 'Quên mật khẩu'
];

// Đã nhúng file function.php bên index.php
layouts('header-login', $data);

// Kiểm tra trạng thái đăng nhập
if (isLogin()) {
    redirect('?module=home&action=dashboard');
} else {
    if (isPost()) {
        $filterAll = filter();
        if (!empty($filterAll['email'])) {
            $email = $filterAll['email'];
            $userQuery = UserBUS::getInstance()->getModelByEmail($email);
            if (!empty($userQuery)) {
                $userId = $userQuery->getId();
                $_SESSION['forgotUserId'] = $userId;
                redirect('?module=auth&action=reset');
            } else {
                $session = new session();
                $session->setFlash('msg', 'Tài khoản không tồn tại!');
                $session->setFlash('msg_type', 'danger');
                redirect('?module=auth&action=forgot');
            }
        } else {
            // Email not provided, display error message
            $session = new session();
            $session->setFlash('msg', 'Vui lòng nhập địa chỉ email!');
            $session->setFlash('msg_type', 'danger');
            redirect('?module=auth&action=forgot');
        }
    }
}

$session = new session();
$msg = $session->getFlash('msg');
$msgType = $session->getFlash('msg_type');

?>

<div class="row">
    <div class="col-4" style="margin:50px auto;">
        <h2 style="text-align: center; text-transform: uppercase;">Quên mật khẩu</h2>
        <?php
        if (!empty($msg)) {
            getMsg($msg, $msgType);
        } ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ Email...">
            </div>
            <button type="submit" class="btn btn-primary btn-block mg-form" style="width:100%;">Gửi</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng kí tài khoản</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer-login');
?>