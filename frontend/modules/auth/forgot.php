<!-- Quên mật khẩu -->
<?php

use backend\bus\UserBUS;
use backend\services\session;

if (!defined('_CODE')) {
    die('Access denied');
}

$data = [
    'pageTitle' => 'Forgot password'
];

//Check login status
if (isLogin()) {
    redirect('?module=indexphp&action=userhomepage');
} else {
    if (isPost()) {
        $filterAll = filter();
        if (!empty($filterAll['email'])) {
            $email = $filterAll['email'];
            $userQuery = UserBUS::getInstance()->getModelByEmail($email);
            if (!empty($userQuery)) {
                // Tạo forgotToken
                $forgotToken = sha1(uniqid() . time());

                $userQuery->setForgotToken($forgotToken);
                $updateStatus = UserBUS::getInstance()->updateModel($userQuery);

                if ($updateStatus) {
                    // Tạo link khôi phục mật khẩu
                    $linkReset = _WEB_HOST . '?module=auth&action=reset&token=' . $forgotToken;

                    $subject = 'Request password reset!';
                    $content = 'Hello there! <br/>';
                    $content .= 'We have received a password reset request from you, please click on the following link to reset your password: <br/>';
                    $content .= $linkReset . '<br/>';
                    $content .= 'Thank you very much!!';

                    $sendMailStatus = sendMail($email, $subject, $content);
                    if ($sendMailStatus) {
                        session::getInstance()->setFlashData('msg', 'Please check your email to reset your password!');
                        session::getInstance()->setFlashData('msg_type', 'success');
                    } else {
                        session::getInstance()->setFlashData('msg', 'System error! Please try again later!(email)');
                        session::getInstance()->setFlashData('msg_type', 'danger');
                    }
                } else {
                    session::getInstance()->setFlashData('msg', 'System error! Please try again later!');
                    session::getInstance()->setFlashData('msg_type', 'danger');
                }
            } else {
                session::getInstance()->setFlashData('msg', 'Account doesn\'t exist!');
                session::getInstance()->setFlashData('msg_type', 'danger');
            }
        } else {
            // Email not provided, display error message
            session::getInstance()->setFlashData('msg', 'Please type in your email!');
            session::getInstance()->setFlashData('msg_type', 'danger');
        }
        redirect('?module=auth&action=forgot');
    }
}

$msg = session::getInstance()->getFlashData('msg');
$msgType = session::getInstance()->getFlashData('msg_type');

?>

<div id="header">
    <?php layouts('header', $data); ?>
</div>

<body>
    <div class="row">
        <div class="cw col-4" style="margin:50px auto;">
            <h2 style="text-align: center; text-transform: uppercase;">Forgot password</h2>
            <?php
            if (!empty($msg)) {
                getMsg($msg, $msgType);
            } ?>
            <form action="" method="post">
                <div class="form-group mg-form">
                    <label for="">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Your email...">
                </div>
                <button type="submit" class="btn btn-primary btn-block mg-form mt-3" style="width:100%;">Send</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=login">Login</a></p>
                <p class="text-center"><a href="?module=auth&action=register">Don't have an account? Register here</a></p>
            </form>
        </div>
    </div>
</body>