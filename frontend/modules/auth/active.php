<!-- Kích hoạt tài khoản -->
<?php
require_once __DIR__ . '/../../../backend/bus/user_bus.php';

use services\session;

require_once __DIR__ . '/../../../backend/enums/status_enums.php';
if (!defined('_CODE')) {
    die('Access denied');
}

$data = [
    'active' => 'Active'
];

layouts('header-login', $data);

// Check if user is logged in
if (isLogin()) {
    $userId = $_SESSION['userId'];
    $userModel = UserBUS::getInstance()->getModelById($userId);
    // Update user status in the database
    $userModel->setStatus(StatusEnums::ACTIVE);

    $updateStatus = UserBUS::getInstance()->updateModel($userModel);
    $session = new session();
    if ($updateStatus) {
        $session->setFlash('msg', 'Kích hoạt tài khoản thành công!');
        $session->setFlash('msg_type', 'success');
        redirect('?module=auth&action=login');
    } else {
        $session->setFlash('msg', 'Kích hoạt tài khoản thất bại, vui lòng liên hệ quản trị viên!');
        $session->setFlash('msg_type', 'danger');
    }
} else {
    getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
}

layouts('footer-login');
