<?php
namespace backend\services;
class PasswordUtilities
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PasswordUtilities();
        }
        return self::$instance;
    }

    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public static function getUserHashedPassword($username)
    {
        // Retrieve the user's hashed password from the database based on the provided username
        // Replace this with your actual implementation
        $hashedPassword = ''; // Replace this line with your database query to retrieve the hashed password

        return $hashedPassword;
    }
}
