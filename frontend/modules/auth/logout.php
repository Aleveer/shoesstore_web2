<!-- Đăng xuất -->

<?php

if (!defined('_CODE')) {
    die('Access denied');
}

if (isset($_SESSION['userId'])) {
    unset($_SESSION['userId']);
}

// Redirect to the login page after logout
header("Location: login.php");
exit();
?>