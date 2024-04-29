<?php
namespace backend\services;

use Exception;
use mysqli;

class DatabaseConnection
{
    private $connection = null;
    private static $instance;
    private static $host = "localhost";
    private static $port = "3306";
    private static $dbname = "shoesstore";
    private static $user = "root";
    private static $password = "";

    // This is the constructor of the class
    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        try {
            if ($this->connection == null || $this->connection->connect_error) {
                $this->connection = new mysqli(self::$host, self::$user, self::$password, self::$dbname, self::$port);
            }
            return $this->connection;
        } catch (Exception $e) {
            error_log("Error connecting to database: " . $e->getMessage());
        }
        return null;
    }

    public static function getPreparedStatement($sql, ...$args)
    {
        try {
            $preparedStatement = self::getInstance()->getConnection()->prepare($sql);
            $types = "";
            foreach ($args as $arg) {
                if (is_int($arg)) {
                    $types .= "i";
                } elseif (is_float($arg)) {
                    $types .= "d";
                } elseif (is_string($arg)) {
                    $types .= "s";
                } else {
                    $types .= "b";
                }
            }
            if (!empty($types) && !empty($args)) {
                $preparedStatement->bind_param($types, ...$args);
            }
            return $preparedStatement;
        } catch (Exception $e) {
            error_log("Error preparing statement: " . $e->getMessage());
        }
    }

    public static function executeQuery($sql, ...$args)
    {
        $preparedStatement = self::getPreparedStatement($sql, ...$args);
        $preparedStatement->execute();
        return $preparedStatement->get_result();
    }

    public static function executeUpdate($sql, ...$args)
    {
        try {
            $preparedStatement = self::getPreparedStatement($sql, ...$args);
            $preparedStatement->execute();
            $affectedRows = $preparedStatement->affected_rows;
            error_log("Executed query: $sql, affected rows: $affectedRows");
            return $affectedRows;
        } catch (Exception $e) {
            error_log("Error executing update: " . $e->getMessage());
        }
    }

    public static function closeConnection()
    {
        try {
            $instance = self::getInstance();
            if ($instance->connection != null && !$instance->connection->connect_error) {
                $instance->connection->close();
            }
        } catch (Exception $e) {
            error_log("Error closing connection: " . $e->getMessage());
        }
    }

    public function checkConnection()
    {
        $this->getConnection();
        try {
            return $this->connection != null && !$this->connection->connect_error;
        } catch (Exception $e) {
            error_log("Error checking connection: " . $e->getMessage());
        }
        return false;
    }

    public function beginTransaction()
    {
        try {
            $this->getConnection()->begin_transaction();
        } catch (Exception $e) {
            error_log("Error beginning transaction: " . $e->getMessage());
        }
    }

    public function endTransaction()
    {
        try {
            $this->getConnection()->commit();
        } catch (Exception $e) {
            error_log("Error ending transaction: " . $e->getMessage());
        }
    }

    public function rollbackTransaction()
    {
        try {
            $this->getConnection()->rollback();
        } catch (Exception $e) {
            error_log("Error rolling back transaction: " . $e->getMessage());
        }
    }
}
