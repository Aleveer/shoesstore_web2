<?php

use services\session;

require_once __DIR__ . '/../../../backend/services/validation.php';
require_once __DIR__ . '/../../../backend/services/session.php';
require_once __DIR__ . '/../../../backend/bus/user_bus.php';
require_once __DIR__ . '/../../../backend/models/user_model.php';
require_once __DIR__ . '/../../../backend/enums/status_enums.php';
require_once __DIR__ . '/../../../backend/enums/roles_enums.php';
require_once __DIR__ . '/../../../backend/services/password-utilities.php';

if (!defined('_CODE')) {
    die('Access denied');
}
if (isPost()) {
    $filterAll = filter();
    $userModel = new UserModel(
        null,
        $filterAll['username'],
        $filterAll['password'],
        $filterAll['email'],
        $filterAll['fullname'],
        $filterAll['phone'],
        $filterAll['gender'],
        null,
        0,
        StatusEnums::ACTIVE,
        $filterAll['address']
    );
    //TODO: Fix the validate model section at backend:
    $count = UserBUS::getInstance()->validateModel($userModel);
    if ($count <= 0) {
        $userModel->setPassword(password_hash($filterAll['password'], PASSWORD_DEFAULT));
        $insertStatus = UserBUS::getInstance()->addModel($userModel);
        if ($insertStatus) {
            $_SESSION['registerUserId'] = $insertStatus;
            redirect('?module=auth&action=active');
        } else {
            $session->setFlash('msg', 'Registration failed!');
            $session->setFlash('msg_type', 'danger');
            redirect('?module=auth&action=register');
        }
    } else {
        $session = new session();
        $session->setFlash('msg', 'Validation failed!');
        $session->setFlash('msg_type', 'danger');
        redirect('?module=auth&action=register');
    }
}

$session = new session();
$msg = $session->getFlash('msg');
$msg_type = $session->getFlash('msg_type');
$errors = $session->getFlash('errors');
$duLieuDaNhap = $session->getFlash('duLieuDaNhap');

$data = [
    'pageTitle' => 'Đăng ký'
];
layouts('header', $data);
?>

<div class="row">
    <div class="col-4" style="margin: 24px auto;">
        <form class="cw" action="" method="post">
            <h2 style="text-align:center; text-transform: uppercase;">Đăng ký</h2>
            <?php if (!empty($msg)) {
                getMsg($msg, $msg_type);
            } ?>
            <div class="form-group mg-form">
                <label for="">Họ tên</label>
                <input name="fullname" class="form-control" type="text" placeholder="Họ tên..." value="<?php echo formOldInfor('fullname', $duLieuDaNhap) ?>">
                <?php echo formError('fullname', $errors) ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Tên đăng nhập</label>
                <input name="username" class="form-control" type="text" placeholder="Tên đăng nhập..." value="<?php echo formOldInfor('username', $duLieuDaNhap) ?>">
                <?php echo formError('username', $errors) ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" class="form-control" type="text" placeholder="Email..." value="<?php echo formOldInfor('email', $duLieuDaNhap) ?>">
                <?php echo formError('email', $errors) ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input name="phone" class="form-control" type="number" placeholder="Số điện thoại..." value="<?php echo formOldInfor('phone', $duLieuDaNhap) ?>">
                <?php echo formError('phone', $errors) ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Địa chỉ</label>
                <input name="address" class="form-control" type="text" placeholder="Nhập địa chỉ..." value="<?php echo formOldInfor('address', $duLieuDaNhap) ?>">
                <?php echo formError('address', $errors) ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Mật khẩu</label>
                <input name="password" class="form-control" type="password" placeholder="Nhập mật khẩu..." value="<?php echo formOldInfor('password', $duLieuDaNhap) ?>">
                <?php echo formError('password', $errors) ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Nhập lại mật khẩu</label>
                <input name="password_confirm" class="form-control" type="password" placeholder="Nhập lại mật khẩu..." value="<?php echo formOldInfor('password_confirm', $duLieuDaNhap) ?>">
                <?php echo formError('password_confirm', $errors) ?>
            </div>

            <div class="form-group mg-form">
                <label for="">Giới tính</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="male" <?php echo (formOldInfor('gender', $duLieuDaNhap) == 'male') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="gender-male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="female" <?php echo (formOldInfor('gender', $duLieuDaNhap) == 'female') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="gender-female">Female</label>
                </div>
                <?php echo formError('gender', $errors) ?>

                <button type="submit" class="btn btn-primary btn-block mg-form" style="width:100%; margin-top:16px;">Đăng ký</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
        </form>
    </div>
</div>


<?php
layouts('footer');
?>