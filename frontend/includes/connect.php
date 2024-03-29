<!-- Kết nối với DB -->

<?php

if (!defined('_CODE')) {
    die('Access denied');
}

try {
    if (class_exists('PDO')) {
        $dsn = 'mysql:dbname='._DB.';host='._HOST;

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', // Set utf8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Tạo thông báo ra ngoại lệ khi gặp lỗi
        ];

        $conn = new PDO($dsn, _USER, _PASS, $options);

        // if ($conn) echo 'Kết nối thành công!'.'<br>';
    }
    
    
} catch (Exception $e) {
    echo '<div style="color: red;">';
    echo $e -> getMessage().'<br>';
    echo '</div>';
    
    die();
}