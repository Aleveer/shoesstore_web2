<?php

const _MODULE = 'indexphp';
const _ACTION = 'userhomepage';
const _CODE = true;

// thông tin người dùng liên kết tới DB
const _HOST = 'localhost';
const _DB = 'shoesstore';
const _USER = 'root';
const _PASS = '';

// Thiết lập host
define('_WEB_HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/frontend');
define('_WEB_HOST_TEMPLATE', _WEB_HOST . '/templates');


// Thiết lập path
define('_WEB_PATH', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH . '/templates');



