<div class="img__background">
  <video autoplay muted loop id="background-video" src="<?php echo _WEB_HOST_TEMPLATE ?> /images/slider.mp4"></video>
  <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/slider.png" alt="" class="img__slider" />
  <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/slider1.png" alt="" class="overlay__img" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <div class="overlay__text">
    <h1>XTREMA 3</h1>
    <h3>COMFY AND TRENDY</h3>
    <p>
      Experience the perfect blend of comfort and style with XTREMA 3.<br/>
      Our products are designed with utmost attention to detail, <br/>
      ensuring not just a trendy look, but also an
      unparalleled comfort.
    </p>

    <div class="overlay">
      <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/pic1.png" alt="" class="thumbnail" />
      <img src="<?php echo _WEB_HOST_TEMPLATE ?> /images/pic2.png" alt="" class="thumbnail" />
    </div>

    <span class="btn__slider">SHOP HERE</span>
    <script>
      $(document).ready(function () {
        $('.btn__slider').click(function () {
          window.location.href = '?module=indexphp&action=product';
        });
      });
    </script>

    <span class="icon1">STRONG & TRENDY</span>
    <span class="icon2">ZUPPER LIGHTWEIGHT</span>

  </div>
</div>