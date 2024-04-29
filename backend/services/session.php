<?php

namespace backend\services;

if (!defined('_CODE')) {
    die('Access denied');
}
class session
{
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new session();
        }
        return self::$instance;
    }

    public function __construct()
    {
    }

    public function setSession($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    function getSession($key = '')
    {
        if (empty($key)) {
            return $_SESSION;
        } else {
            if (!empty($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }
    }

    function removeSession($key = '')
    {
        if (empty($key)) {
            session_destroy();
            return true;
        } else {
            if (!empty($_SESSION[$key])) {
                unset($_SESSION[$key]);
                return true;
            }
        }
    }

    public function destroy()
    {
        session_destroy();
    }

    function setFlashData($key, $value)
    {
        $key = 'flash_' . $key;
        return $this->setSession($key, $value);
    }

    // Hàm đọc flash data 
    function getFlashData($key)
    {
        $key = 'flash_' . $key;
        $data = $this->getSession($key);
        $this->removeSession($key);
        return $data;
    }

    public function __destruct()
    {
        session_write_close();
    }

    public function __get($name)
    {
        return $this->getSession($name);
    }

    public function __set($name, $value)
    {
        $this->setSession($name, $value);
    }

    public function __unset($name)
    {
        $this->removeSession($name);
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }
}
