<?php

// use services\validation;
// use services\session;
// use BUS\UserBUS;
// use Models\UserModel;
// use Enums\StatusEnums;
// use Enums\RolesEnums;
// use services\PasswordUtilities;

use backend\services\session;
use backend\bus\UserBUS;
use backend\enums\StatusEnums;
use backend\models\UserModel;

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
        StatusEnums::INACTIVE,
        $filterAll['address'],
        null,
        null,
        null,
        null
    );
    //TODO: Fix the validate model section at backend:
    $errors = UserBUS::getInstance()->validateModel($userModel);
    if ($filterAll['password_confirm'] == null || trim($filterAll['password_confirm']) == "") {
        $errors['password_confirm']['required'] = 'Password confirm is required!';
    }
    if (!($filterAll['password_confirm'] == $filterAll['password'])) {
        $errors['password_confirm']['comfirm'] = 'Confirm password does not match!';
    }
    if (count($errors) <= 0) {
        $activeToken = sha1(uniqid() . time());
        $userModel->setPassword(password_hash($filterAll['password'], PASSWORD_DEFAULT));
        $userModel->setCreateAt(date("Y-m-d H:i:s"));
        $userModel->setActiveToken($activeToken);
        $insertStatus = UserBUS::getInstance()->addModel($userModel);
        if ($insertStatus) {
            $_SESSION['registerUserId'] = $insertStatus;
            // Tạo link kích hoạt
            $linkActive = _WEB_HOST . '?module=auth&action=active&token=' . $activeToken;
            // Thiết lập gửi mail
            $subject = $filterAll['fullname'] . ' vui lòng kích hoạt tài khoản!!!';
            $content = 'Chào ' . $filterAll['fullname'] . '<br/>';
            $content .= 'Vui lòng click vào đường link dưới đây để kích hoạt tài khoản:' . '<br/>';
            $content .= $linkActive . '<br/>';
            $content .= 'Trân trọng cảm ơn!!';


            // Tiến hành gửi mail
            $sendMailStatus = sendMail($filterAll['email'], $subject, $content);

            if ($sendMailStatus) {
                session::getInstance()->setFlashData('msg', 'Đăng kí thành công, vui lòng kiểm tra email để kích hoạt tài khoản!');
                session::getInstance()->setFlashData('msg_type', 'success');
            } else {
                session::getInstance()->setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
                session::getInstance()->setFlashData('msg_type', 'danger');
            }
        }
        redirect('?module=auth&action=register');
    } else {
        session::getInstance()->setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu!');
        session::getInstance()->setFlashData('msg_type', 'danger');
        session::getInstance()->setFlashData('errors', $errors);
        session::getInstance()->setFlashData('duLieuDaNhap', $filterAll);
        redirect('?module=auth&action=register');
    }
}

$msg = session::getInstance()->getFlashData('msg');
$msg_type = session::getInstance()->getFlashData('msg_type');
$errors = session::getInstance()->getFlashData('errors');
$duLieuDaNhap = session::getInstance()->getFlashData('duLieuDaNhap');

$data = [
    'pageTitle' => 'Đăng ký'
];
?>

<div id="header">
    <?php layouts('header', $data); ?>
</div>

<body>
    <div class="row">
        <div class="col-4" style="margin: 24px auto;">
            <form class="cw" action="" method="post">
                <h2 style="text-align:center; text-transform: uppercase;">Register</h2>
                <?php if (!empty($msg)) {
                    getMsg($msg, $msg_type);
                } ?>
                <div class="form-group mg-form mt-3">
                    <label for="">Full name</label>
                    <input name="fullname" class="form-control" type="text" placeholder="Your full name..."
                        value="<?php echo formOldInfor('fullname', $duLieuDaNhap) ?>">
                    <?php echo formError('fullname', $errors) ?>
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Username</label>
                    <input name="username" class="form-control" type="text" placeholder="Your username..."
                        value="<?php echo formOldInfor('username', $duLieuDaNhap) ?>">
                    <?php echo formError('username', $errors) ?>
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Email</label>
                    <input name="email" class="form-control" type="text" placeholder="Your email..."
                        value="<?php echo formOldInfor('email', $duLieuDaNhap) ?>">
                    <?php echo formError('email', $errors) ?>
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Phone number</label>
                    <input name="phone" class="form-control" type="number" placeholder="Your phone number... (optional)"
                        value="<?php echo formOldInfor('phone', $duLieuDaNhap) ?>">
                    <?php echo formError('phone', $errors) ?>
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Address</label>
                    <input name="address" class="form-control" type="text" placeholder="Your address..."
                        value="<?php echo formOldInfor('address', $duLieuDaNhap) ?>">
                    <?php echo formError('address', $errors) ?>
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Password</label>
                    <input name="password" class="form-control" type="password" placeholder="Your password..."
                        value="<?php echo formOldInfor('password', $duLieuDaNhap) ?>">
                    <?php echo formError('password', $errors) ?>
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Re-type password</label>
                    <input name="password_confirm" class="form-control" type="password"
                        placeholder="Re-type your password..."
                        value="<?php echo formOldInfor('password_confirm', $duLieuDaNhap) ?>">
                    <?php echo formError('password_confirm', $errors) ?>
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Gender</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="male" <?php echo (formOldInfor('gender', $duLieuDaNhap) == 'male') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="gender-male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="female" <?php echo (formOldInfor('gender', $duLieuDaNhap) == 'female') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="gender-female">Female</label>
                    </div>
                    <?php echo formError('gender', $errors) ?>

                    <button type="submit" class="btn btn-primary btn-block mg-form"
                        style="width:100%; margin-top:16px;">Register</button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">Already have an account? Log in here</a></p>
            </form>
        </div>
    </div>
</body>

<div id="footer">
    <?php layouts('footer'); ?>
</div>