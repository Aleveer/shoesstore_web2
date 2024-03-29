<?php

namespace services;

class session
{
    public function __construct()
    {
    }

    public function set($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        session_destroy();
    }

    public function setFlash($key, $value)
    {
        $_SESSION["_flash"][$key] = $value;
    }

    public function getFlash($key)
    {
        $value = $_SESSION["_flash"][$key] ?? null;
        unset($_SESSION["_flash"][$key]);
        return $value;
    }

    public function __destruct()
    {
        session_write_close();
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __unset($name)
    {
        $this->remove($name);
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }
}
