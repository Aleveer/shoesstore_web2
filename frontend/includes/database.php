<!-- Các hàm xử lí liên quan tới DB -->

<?php

if (!defined('_CODE')) {
    die('Access denied');
}

require_once 'connect.php';

function query($sql, $data = [], $check = false)
{
    global $conn;
    $ketQua = false;
    try {
        $statement = $conn->prepare($sql);

        if (!empty($data)) $ketQua = $statement->execute($data);
        else $ketQua = $statement->execute();
    } catch (Exception $e) {
        echo 'Error: ' . $err = $e->getMessage() . '<br>';
        echo 'File: ' . $err = $e->getFile() . '<br>';
        echo 'Line: ' . $err = $e->getLine() . '<br>';
        die();
    }
    
    if ($check) {
        return $statement;
    }

    return $ketQua;
}

function insert($table, $data)
{
    $keys = array_keys($data);
    $truong = implode(',', $keys);
    $valuetb = ':' . implode(',:', $keys);

    $sql = 'INSERT INTO ' . $table . ' (' . $truong . ') ' . ' VALUES ' . '(' . $valuetb . ')';

    $kq = query($sql, $data);
    return $kq;
}


function update($table, $data, $condition = '')
{
    $update = '';
    foreach ($data as $key => $value) {
        $update .= $key . ' = :' . $key . ', ';
    }
    $update = substr($update, 0, strlen($update) - 2);
    if (!empty($condition)) {
        $sql = 'UPDATE ' . $table . ' SET ' . $update . ' WHERE ' . $condition;
    } else {
        $sql = 'UPDATE ' . $table . ' SET ' . $update;
    }
    $kq = query($sql, $data);
    return $kq;
}

function delete($table, $condition = '')
{
    'DELETE FROM user WHERE id = :id';

    if (!empty($condition)) {
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
    } else {
        $sql = 'DELETE FROM ' . $table;
    }

    $kq = query($sql);
    return $kq;
}

// Lấy nhiều dòng dữ liệu   
function getRows($sql) {
    $kq = query($sql, '', true);
    if (is_object($kq)) {
        $dataFetch = $kq -> fetchAll(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}


// Lấy một dòng dữ liệu   
function getRow($sql) {
    $kq = query($sql, '', true);
    if (is_object($kq)) {
        $dataFetch = $kq -> fetch(PDO::FETCH_ASSOC);
    }

    return $dataFetch;
}


// Đếm số dòng dữ liệu
function getQuantityRows($sql) {
    $kq = query($sql, '', true);
    if (!empty($kq)) {
        return $kq -> rowCount();
    }
}
