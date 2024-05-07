<?php

namespace backend\services;

class validation
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new validation();
        }
        return self::$instance;
    }

    private static function isMatch($input, $regex)
    {
        return preg_match($regex, $input);
    }

    public static function isValidName($name)
    {
        $regex = "/^[a-zA-Z0-9\\p{L}\\s.,\\-\\/]+$/";
        return self::isMatch($name, $regex);
    }

    public static function isValidUsername($username)
    {
        $regex = "/^[a-zA-Z0-9\\p{L}\\s.,\\-\\/]+$/";
        return self::isMatch($username, $regex);
    }

    public static function isValidPassword($password)
    {
        if (!preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least one uppercase letter";
        }

        if (!preg_match('/[a-z]/', $password)) {
            return "Password must contain at least one lowercase letter";
        }

        if (!preg_match('/\d/', $password)) {
            return "Password must contain at least one digit";
        }

        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            return "Password must contain at least one special character";
        }

        return true;
    }

    public static function isValidPhoneNumber($phoneNumber)
    {
        $regex = "/(84|0[3|5|7|8|9])+([0-9]{8})/";
        return self::isMatch($phoneNumber, $regex);
    }

    public static function isValidEmail($email)
    {
        $regex = "/^[A-Za-z0-9+_.-]+@(.+)$/";
        return self::isMatch($email, $regex);
    }

    public static function isValidPrice($input)
    {
        $regex = "/^[1-9]\\d*(\\.\\d+)?$/";
        return self::isMatch($input, $regex) && (float) $input > 0;
    }

    public static function isValidAddress($address)
    {
        $regex = "/^[a-zA-Z0-9., \\-\\/]+$/";
        return self::isMatch($address, $regex);
    }

    public static function isValidProductQuantity($quantity)
    {
        $regex = "/^(0|[1-9]\\d*)$/";
        return !empty($quantity) && self::isMatch($quantity, $regex);
    }

    public static function isValidCardNumber($cardNumber)
    {
        $regex = "/^[0-9]{16}$/";
        return self::isMatch($cardNumber, $regex);
    }

    public static function isValidCardExpiration($expiration)
    {
        $regex = "/^(0[1-9]|1[0-2])\\/([0-9]{2})$/";
        return self::isMatch($expiration, $regex);
    }

    public static function isValidCardCVV($cvv)
    {
        $regex = "/^[0-9]{3}$/";
        return self::isMatch($cvv, $regex);
    }

    public static function isValidPostalCode($postalCode)
    {
        $regex = "/^[0-9]{5}$/";
        return self::isMatch($postalCode, $regex);
    }

    public function isCouponValid($couponsModel): bool
    {
        $expirationDate = $couponsModel->getExpired();
        $currentDate = date("Y-m-d");
        return $expirationDate >= $currentDate;
    }

    function isNumberInt($number)
    {
        $checkNumberInt = filter_var($number, FILTER_VALIDATE_INT);
        return $checkNumberInt;
    }

    // Kiểm tra số thực
    function isNumberFloat($number)
    {
        $checkNumberFloat = filter_var($number, FILTER_VALIDATE_FLOAT);
        return $checkNumberFloat;
    }
}
