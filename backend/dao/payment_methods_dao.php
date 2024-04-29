<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\PaymentMethodModel;
use backend\services\DatabaseConnection;

class PaymentMethodsDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PaymentMethodsDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $paymentMethodsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM payment_methods");
        while ($row = $rs->fetch_assoc()) {
            $paymentMethodsModel = $this->createPaymentMethodsModel($row);
            array_push($paymentMethodsList, $paymentMethodsModel);
        }
        return $paymentMethodsList;
    }

    private function createPaymentMethodsModel($rs)
    {
        $id = $rs['id'];
        $name = $rs['method_name'];
        return new PaymentMethodModel($id, $name);
    }

    public function getAll(): array
    {
        $paymentMethodsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM payment_methods");
        while ($row = $rs->fetch_assoc()) {
            $paymentMethodsModel = $this->createPaymentMethodsModel($row);
            array_push($paymentMethodsList, $paymentMethodsModel);
        }
        return $paymentMethodsList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM payment_methods WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createPaymentMethodsModel($row);
            }
        }
        return null;
    }

    public function insert($paymentMethodModel): int
    {
        $query = "INSERT INTO payment_methods (method_name) VALUES (?)";
        $args = [$paymentMethodModel->getName()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($paymentMethodModel): int
    {
        $query = "UPDATE payment_methods SET method_name = ? WHERE id = ?";
        $args = [$paymentMethodModel->getName(), $paymentMethodModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM payment_methods WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM payment_methods WHERE id LIKE ? OR method_name LIKE ?";
            $args = array_fill(0,  2, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM payment_methods WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM payment_methods WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $paymentMethodsList = [];
        while ($row = $rs->fetch_assoc()) {
            $paymentMethodsModel = $this->createPaymentMethodsModel($row);
            array_push($paymentMethodsList, $paymentMethodsModel);
        }
        if (count($paymentMethodsList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $paymentMethodsList;
    }
}
