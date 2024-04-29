<!-- Đăng xuất -->

<?php
use backend\enums\StatusEnums;

if (!defined('_CODE')) {
    die('Access denied');
}

use backend\bus\TokenLoginBUS;
use backend\services\session;
use backend\bus\UserBUS;

// Xoá token trong tokenLogin trong DB, đồng thời xoá token trong session khi đăng nhập tạo ra
if (isLogin()) {
    $token = session::getInstance()->getSession('tokenLogin');
    $tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
    $userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
    if ($userModel->getStatus() == StatusEnums::ACTIVE || $userModel->getStatus() == StatusEnums::INACTIVE) {
        $userModel->setStatus(StatusEnums::INACTIVE);
        UserBUS::getInstance()->updateModel($userModel);
    }

    UserBUS::getInstance()->updateModel($userModel);
    TokenLoginBUS::getInstance()->deleteModel($tokenModel);
    session::getInstance()->removeSession('tokenLogin');
    redirect('?module=auth&action=login');
}