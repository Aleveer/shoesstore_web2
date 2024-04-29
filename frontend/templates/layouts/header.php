<?php
use backend\enums\RolesEnums;
use backend\services\session;
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;

if (isLogin()) {
    $token = session::getInstance()->getSession('tokenLogin');
    $tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
    $userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
}

if (!defined('_CODE')) {
    die('Access denied');
} ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? ($data['pageTitle']) : 'Home'; ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css?ver=1">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/grid.css?ver=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="header__logo">
        <a href="?module=indexphp">
            <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/logoShoes.jpg" alt="Wait a minute!!"
                style="width: 50px; height: 50px;">
        </a>
    </div>
    <div class="header__content">
        <ul class="navbar header__content__item">
            <li class="navbar__item">
                <a href="?module=indexphp">Home</a>
            </li>
            <li class="navbar__item">
                <a href="?module=indexphp&action=product">
                    Shop
                </a>
            </li>
            <li class="navbar__item">
                <a href="?module=indexphp&action=FAQ">FAQ</a>
        </ul>

        <?php
        if (!isLogin() || ($userModel->getRoleId() == RolesEnums::CUSTOMER)) {
            echo '<div class="cart header__content__item">
        <a href="?module=cartsection&action=cart">
            <i class="fa-sharp fa-solid fa-cart-shopping cart__icon"></i>
        </a></div>';
        }
        ?>

        <div class="user header__content__item" style="height:100%">
            <?php
            if (isLogin()):
                ?>
                <div class="user__logo" onclick="showDetailUser()">
                    <img class="user__image" src="<?php echo _WEB_HOST_TEMPLATE ?>/images/avt.png" alt="">
                    <i class="fa-solid fa-angle-down user__dropdown"></i>
                    <ul class="user__dropdown__menu hide">
                        <li class="user__dropdown__menu__item">
                            <a href="?module=account&action=profilesetting">Profile Setting</a>
                        </li>
                        <?php if ($userModel->getRoleId() == RolesEnums::CUSTOMER) {
                            echo '<li class="user__dropdown__menu__item">
                            <a href="?module=account&action=order-list">Purchase List</a>
                        </li>';
                        } else {
                            echo '<li class="user__dropdown__menu__item">
                            <a href="?module=dashboard&view=dashboard.view">Dashboard</a>';
                        }
                        ; ?>
                        <li class="user__dropdown__menu__item">
                            <a href="?module=auth&action=logout">Log out</a>
                        </li>
                    </ul>
                </div>
                <?php
            else:
                ?>
                <a class="btn btn-primary" href="?module=auth&action=login">Login</a>
                <a class="btn btn-primary ml8" href="?module=auth&action=register">Register</a>
                <?php
            endif;
            ?>
        </div>
    </div>
</body>