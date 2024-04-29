<?php
use backend\bus\ProductBUS;

$products = ProductBUS::getInstance()->getActiveProductOnly();
$randomProducts = ProductBUS::getInstance()->getRandomRecommendProducts();
?>

<div class="content">
    <div class="text__content">
        <ul>
            <i class="fa-sharp fa-regular fa-square-check" style="color: #0e5fec;"></i>
            ORIGINAL PRODUCT
        </ul>
        <ul>
            <i class="fa-regular fa-bell" style="color: #0e5fec;"></i>
            INTERESTING PROMO & DEALS
        </ul>
        <ul>
            <i class="fa-solid fa-rotate-left" style="color: #0e5fec;"></i>
            30 DAYS MONEY-BACK GUARANTE
        </ul>
        <ul>
            <i class="fa-solid fa-medal" style="color: #0e5fec;"></i>
            EXPEREINCED SELLER
        </ul>
    </div>

    <div id="card__area">
        <div class="wrap">
            <div class="box__area">
                <div class="box">
                    <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/class1.webp" alt>
                    <div class="overlay__box">
                        <h3>RUNNING</h3>
                    </div>
                </div>
                <div class="box">
                    <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/class2.jpg" alt>
                    <div class="overlay__box">
                        <h3>WOMAN</h3>
                    </div>
                </div>
                <div class="box">
                    <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/class3.jpg" alt>
                    <div class="overlay__box">
                        <h3>MAN</h3>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/navigator_content_redirect.js"></script>
    </div>
</div>
<div class="list__product">
    <div class="in__wrap">
        <ul id="content" class="products">
            <?php
            for ($i = 0; $i < 3; $i++) {
                $product = $randomProducts[$i];
                echo '
        <li>
            <div class="product-item">
                <div>
                    <a href="?module=indexphp&action=singleproduct&id=' . $product->getId() . '">
                        <img src="' . $product->getImage() . '" alt>
                    </a>
                </div>
                <div class="product-info">
                    <a href="?module=indexphp&action=singleproduct&id=' . $product->getId() . '" class="product-name">' . $product->getName() . '</a>
                    <div class="product-price">
                        <ul class="price"><span>$' . $product->getPrice() . '</span>
                            <a href="?module=indexphp&action=singleproduct&id=' . $product->getId() . '"><i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i></a>
                        </ul>
                    </div>
                </div>
        </li>
    ';
            }
            ?>
            <li>
                <div class="hot__product"
                    style="background: url('<?php echo _WEB_HOST_TEMPLATE ?> /images/hotproduct.jpg');">
                    <div class="overlay__product">
                        <h3>HOT PRODUCT</h3>
                        <p>Introducing our latest hot product, designed with cutting-edge technology and unparalleled
                            craftsmanship. This product is the epitome of innovation and style, setting a new standard
                            in the industry. Experience the difference with our hot product today.</p>
                        <span><a href="?module=indexphp&action=product">SEE MORE <i
                                    class="fa-solid fa-arrow-right"></i></a></span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="logo__product">
        <span>SNEAKER</span>
        <span>ADIDAS</span>
        <span>NIKE</span>
        <span>CONVERSE</span>
    </div>
    <div class="slider__content">
        <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/slidercontent.webp" alt>
        <div class="overlay__content">
            <h2>Makes Yourself Keep Sporty & Stylish</h2>
            <p>
                Embrace the perfect blend of athleticism and fashion with our products. Our offerings are designed to
                enhance your performance while keeping you chic and stylish. Experience the fusion of sporty
                functionality and elegant style like never before.
            </p>
        </div>
    </div>
    <div class="list__product">
        <div class="see__more">
            <span class="title">ALL OUR PRODUCT</span>
            <div class="divider"></div>
            <span><a href="?module=indexphp&action=product">SEE MORE <i class="fa-solid fa-arrow-right"></i></a></span>
        </div>

        <div class="in__wrap">
            <ul id="content" class="products">
                <?php
                for ($i = 0; $i < 8; $i++) {
                    $product = $products[$i];
                    echo '
        <li class="tbay">
            <div class="product-item">
                <div>
                    <a href="?module=indexphp&action=singleproduct&id=' . $product->getId() . '">
                        <img src="' . $product->getImage() . '" alt>
                    </a>
                </div>
                <div class="product-info">
                    <a href="?module=indexphp&action=singleproduct&id=' . $product->getId() . '" class="product-name">' . $product->getName() . '</a>
                    <div class="product-price">
                        <ul class="price"><span>$' . $product->getPrice() . '</span>
                            <a href="?module=indexphp&action=singleproduct&id=' . $product->getId() . '"><i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i></a>
                        </ul>
                    </div>
                </div>
        </li>
    ';
                }
                ?>
            </ul>
            <div class="in-wrap">
                <div class="swiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <div class="swiper-slide">
                            <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/img/Fila.jpg" alt="">
                            <div class="title">
                                <span>Fila</span>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/img/Fashion.jpg" alt="">
                            <div class="title">
                                <span>Fashion</span>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/img/Sneakers.jpg" alt="">
                            <div class="title">
                                <span>Sneakers</span>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/img/Naked Wolfe Pink.jpg" alt="">
                            <div class="title">
                                <span>Naked Wolfe Pink</span>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/img/Autumn.jpg" alt="">
                            <div class="title">
                                <span>Autumn</span>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/img/Naked Wolfe.jpg" alt="">
                            <div class="title">
                                <span>Naked Wolfe</span>
                            </div>
                        </div>
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>