<!-- Quản lí chung. Mặc định điều hướng vào đây khi đăng nhập -->

<?php

if (!defined('_CODE')) {
    die('Access denied');
}
$data = [
    'page_title' => 'DashBoard'
];
layouts('header', $data);


// Kiểm tra trạng thái đăng nhập

if (!isLogin()) {
    redirect('?module=auth&action=login');
}
?>


<h1>DASH BOARD</h1>


<?php

layouts('footer');