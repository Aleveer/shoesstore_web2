<!-- reset password -->
<!-- TODO: FIX -->
<?php

use services\session;

require_once(__DIR__ . "/../../../ShoesStore/backend/services/validation.php");
require_once(__DIR__ . "/../../../ShoesStore/backend/services/session.php");
require_once(__DIR__ . "/../../../ShoesStore/backend/bus/user_bus.php");
require_once(__DIR__ . "/../../../ShoesStore/backend/models/user_model.php");
require_once(__DIR__ . "/../../../ShoesStore/backend/enums/status_enums.php");
require_once(__DIR__ . "/../../../ShoesStore/backend/enums/roles_enums.php");
require_once(__DIR__ . "/../../../ShoesStore/backend/services/password-utilities.php");

if (!defined('_CODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Reset Password'
];
layouts('header-login', $data);

if (!empty(filter()['token'])) $token = filter()['token'];



if (!empty($token)) {
    $tokenQuery = getRow("SELECT id, fullname, email FROM user WHERE forgotToken = '$token'");
    if (!empty($tokenQuery)) {
        if (isPost()) {
            $filterAll = filter();
            $errors = []; // Mảng chứa các lỗi

            // Validate password: required, min-length = 8
            if (empty($filterAll['password'])) {
                $errors['password']['required'] = "Phải nhập mật khẩu!";
            } else {
                if (strlen($filterAll['password']) < 8) {
                    $errors['password']['length'] = "Mật khẩu phải có tối thiểu 8 kí tự";
                }
            }

            // Validate confirm password: required, giống password
            if (empty($filterAll['password_confirm'])) {
                $errors['password_confirm']['required'] = "Phải nhập lại mật khẩu!";
            } else {
                if (!($filterAll['password_confirm'] == $filterAll['password'])) {
                    $errors['password_confirm']['match'] = "Mật khẩu nhập lại không đúng!";
                }
            }


            // Phải $session->setFlash vì nếu không set thì sau khi reload (redirect) sẽ mất
            // Đây là một trong những chức năng của session
            if (empty($errors)) {
                // Xử lí việc update mật khẩu
                $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);

                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];

                $session = new session();
                $updateStatus = update('user', $dataUpdate, "id = $tokenQuery[id]");
                if ($updateStatus) {
                    $session->setFlash('msg', 'Thay đổi mật khẩu thành công!!!');
                    $session->setFlash('msg_type', 'success');
                    redirect('?module=auth&action=login');
                } else {
                    $session->setFlash('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
                    $session->setFlash('msg_type', 'danger');
                }
            } else {
                $session->setFlash('msg', 'Vui lòng kiểm tra lại dữ liệu!');
                $session->setFlash('msg_type', 'danger');
                $session->setFlash('errors', $errors);
                redirect("?module=auth&action=reset&token=$token");
            }
        }
        $session = new session();

        $msg = $session->getFlash('msg');
        $msg_type = $session->getFlash('msg_type');
        $errors = $session->getFlash('errors');
?>
        <!-- Bảng đặt lại mật khẩu -->
        <div class="row">
            <div class="col-4" style="margin: 24px auto;">
                <form action="" method="post">
                    <h2 style="text-align:center; text-transform: uppercase;">Đặt lại mật khấu</h2>
                    <?php if (!empty($msg)) {
                        getMsg($msg, $msg_type);
                    } ?>

                    <div class="form-group mg-form">
                        <label for="">Mật khẩu</label>
                        <input name="password" class="form-control" type="password" placeholder="Nhập mật khẩu...">
                        <?php echo formError('password', $errors) ?>
                    </div>

                    <div class="form-group mg-form">
                        <label for="">Nhập lại mật khẩu</label>
                        <input name="password_confirm" class="form-control" type="password" placeholder="Nhập lại mật khẩu...">
                        <?php echo formError('password_confirm', $errors) ?>
                    </div>

                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <button type="submit" class="btn btn-primary btn-block mg-form" style="width:100%;">Gửi</button>
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
layouts('footer-login', $data);

?>