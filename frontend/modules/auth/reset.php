<!-- reset password -->
<?php

// use services\session;
// use BUS\UserBUS;
// use services\validation;
// use services\PasswordUtilities;
// use Models\UserModel;
// use Enums\StatusEnums;
// use Enums\RolesEnums;
use backend\services\session; // added backend\
use backend\BUS\UserBUS; // added backend\
use backend\services\validation; // added backend\
use backend\services\PasswordUtilities; // added backend\
use backend\Models\UserModel; // added backend\
use backend\Enums\StatusEnums; // added backend\
use backend\Enums\RolesEnums; // added backend\

if (!defined('_CODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Reset Password'
];
layouts('header', $data);

if (!empty(filter()['token'])) $token = filter()['token'];

if (!empty($token)) {
    $userQuery = UserBUS::getInstance()->getModelByForgotToken($token);
    if (!empty($userQuery)) {
        if (isPost()) {
            $filterAll = filter();
            $errors = []; // Mảng chứa các lỗi

            $errors = UserBUS::getInstance()->validateResetPassword($filterAll['password'], $filterAll['password_confirm']);

            // Phải setFlashData vì nếu không set thì sau khi reload (redirect) sẽ mất
            // Đây là một trong những chức năng của session
            if (empty($errors)) {
                // Xử lí việc update mật khẩu
                $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);

                $userQuery->setPassword($passwordHash);
                $userQuery->setForgotToken(null);
                $userQuery->setUpdateAt(date('Y-m-d H:i:s'));
                $updateStatus = UserBUS::getInstance()->updateModel($userQuery);
                if ($updateStatus) {
                    session::getInstance()->setFlashData('msg', 'Thay đổi mật khẩu thành công!!!');
                    session::getInstance()->setFlashData('msg_type', 'success');
                    redirect('?module=auth&action=login');
                } else {
                    session::getInstance()->setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
                    session::getInstance()->setFlashData('msg_type', 'danger');
                }
            } else {
                session::getInstance()->setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu!');
                session::getInstance()->setFlashData('msg_type', 'danger');
                session::getInstance()->setFlashData('errors', $errors);
                redirect("?module=auth&action=reset&token=$token");
            }
        }
        $msg = session::getInstance()->getFlashData('msg');
        $msg_type = session::getInstance()->getFlashData('msg_type');
        $errors = session::getInstance()->getFlashData('errors');
?>
        <!-- Bảng đặt lại mật khẩu -->
        <div class="row">
            <div class="col-4" style="margin: 24px auto;">
                <form class="cw" action="" method="post">
                    <h2 style="text-align:center; text-transform: uppercase;">Đặt lại mật khấu</h2>
                    <?php if (!empty($msg)) {
                        getMsg($msg, $msg_type);
                    } ?>

                    <div class="form-group mg-form">
                        <label for="">Mật khẩu</label>
                        <input name="password" class="form-control" type="password" placeholder="Nhập mật khẩu...">
                        <?php echo formError('password', $errors) ?>
                    </div>

                    <div class="form-group mg-form mt-2">
                        <label for="">Nhập lại mật khẩu</label>
                        <input name="password_confirm" class="form-control" type="password" placeholder="Nhập lại mật khẩu...">
                        <?php echo formError('password_confirm', $errors) ?>
                    </div>

                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <button type="submit" class="btn btn-primary btn-block mg-form mt-3" style="width:100%;">Gửi</button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
                </form>
            </div>
        </div>

<?php
    } else {
        getMsg("Liên kết không tồn tại hoặc đã hết hạn.", "danger");
    }
} else {
    getMsg("Liên kết không tồn tại hoặc đã hết hạn.", "danger");
}
?>

<?php
layouts('footer', $data);

?>