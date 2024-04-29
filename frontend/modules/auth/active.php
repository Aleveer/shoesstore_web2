<!-- Kích hoạt tài khoản -->
<?php

use backend\bus\UserBUS;
use backend\enums\StatusEnums;
use backend\services\session;

if (!defined('_CODE')) {
    die('Access denied');
}

$data = [
    'active' => 'Active'
];

layouts('header', $data);


if (!empty(filter()['token']))  $token = filter()['token'];
if (!empty($token)) {
    //Nếu có tồn tại token thì truy vấn và CSDL để check xem có trùng activeToken trong user hay không
    $userQuery = UserBUS::getInstance()->getModelByActiveToken($token);
    $userList = UserBUS::getInstance()->getAllModels();
    if (!empty($userQuery)) {
        $userQuery->setStatus(StatusEnums::ACTIVE);
        $userQuery->setUpdateAt(date("Y-m-d H:i:s"));
        $userQuery->setActiveToken("");
        $updateStatus = UserBUS::getInstance()->updateModel($userQuery);

        if ($updateStatus) {
            session::getInstance()->setFlashData('msg', 'Your account has been activated successfully!');
            session::getInstance()->setFlashData('msg_type', 'success');
            redirect('?module=auth&action=login');
        } else {
            session::getInstance()->setFlashData('msg', 'Activation failed! Please contact the administrator for assistance!');
            session::getInstance()->setFlashData('msg_type', 'danger');
        }
    } else {
        echo 123;
        getMsg('The url is not available or expired', 'danger');
    }
} else {
    echo 456;
    getMsg('The url is not available or expired', 'danger');
}

layouts('footer');
