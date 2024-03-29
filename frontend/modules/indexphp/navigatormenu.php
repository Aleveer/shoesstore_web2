<div class="header__logo">
    <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/logo.png" alt="Wait a minute!!">
</div>
<div class="header__content">
    <ul class="navbar header__content__item">
        <li class="navbar__item">
            <a href="#">Home</a>
        </li>
        <li class="navbar__item">
            <a href="#">
                Shop
                <i class="fa-sharp fa-solid fa-caret-down navbar__item__icon"></i>
            </a>

            <ul class="navbar__item__subnav">
                <li class="navbar__item__subnav__subitem">
                    <a href="#">Product Archive</a>
                </li>
                <li class="navbar__item__subnav__subitem">
                    <a href="#">Single Product</a>
                </li>
            </ul>
        </li>
        <li class="navbar__item">
            <a href="#">
                News
                <i class="fa-sharp fa-solid fa-caret-down navbar__item__icon"></i>
            </a>
            <ul class="navbar__item__subnav">
                <li class="navbar__item__subnav__subitem">
                    <a href="#">News Archive</a>
                </li>
                <li class="navbar__item__subnav__subitem">
                    <a href="#">Single News</a>
                </li>
            </ul>
        </li>
        <li class="navbar__item">
            <a href="#">
                Pages
                <i class="fa-sharp fa-solid fa-caret-down navbar__item__icon"></i>
            </a>
            <ul class="navbar__item__subnav">
                <li class="navbar__item__subnav__subitem">
                    <a href="#">About Us</a>
                </li>
                <li class="navbar__item__subnav__subitem">
                    <a href="#">FAQ</a>
                </li>
                <li class="navbar__item__subnav__subitem">
                    <a href="#">404 Page</a>
                </li>
            </ul>
        </li>
        <li class="navbar__item">
            <a href="#">Contact Us</a>
        </li>
    </ul>
    <div class="search header__content__item">
        <i class="fa-sharp fa-solid fa-magnifying-glass search__icon"></i>
    </div>
    <div class="cart header__content__item">
        <i class="fa-sharp fa-solid fa-cart-shopping cart__icon"></i>
    </div>

    <div class="user header__content__item" style="height:100%">
        <!-- <a href="#">
            <img class="user__image" src="<?php echo _WEB_HOST_TEMPLATE ?>/images/avt.png" alt="">
            <i class="fa-solid fa-chevron-down user__dropdown"></i>
        </a>
        <ul class="user__dropdown__menu">
            
        </ul> -->
        <a class="btn btn-primary" href="?module=auth&action=login">Đăng nhập</a>
        <a class="btn btn-primary ml8" href="?module=auth&action=register">Đăng ký</a>
    </div>
</div>