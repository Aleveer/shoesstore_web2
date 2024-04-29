<?php
if (!defined('_CODE')) {
    die('Access denied');
}

$data = [
    'pageTitle' => 'Trang chủ'
];

?>
<!-- Header -->
<div id="header">
    <?php layouts("header") ?>
</div>

<!-- Slider -->
<div id="slider">
    <?php require("navigatorslider.php") ?>
</div>

<!-- Content -->
<div id="content">
    <?php require("navigatorcontent.php") ?>
</div>

<!--Footer-->
<div id="footer">
    <?php layouts('footer') ?>
</div>