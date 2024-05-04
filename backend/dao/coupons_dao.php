<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\CouponsModel;
use backend\services\DatabaseConnection;

class CouponsDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CouponsDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $couponsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM coupons");
        while ($row = $rs->fetch_assoc()) {
            $couponsModel = $this->createCouponsModel($row);
            array_push($couponsList, $couponsModel);
        }
        return $couponsList;
    }
    private function createCouponsModel($rs)
    {
        $id = $rs['id'];
        $code = $rs['code'];
        $quantity = $rs['quantity'];
        $percent = $rs['percent'];
        $expired = $rs['expired'];
        $description = $rs['description'];
        return new CouponsModel($id, $code, $quantity, $percent, $expired, $description);
    }

    public function getAll(): array
    {
        $couponsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM coupons");
        while ($row = $rs->fetch_assoc()) {
            $couponsModel = $this->createCouponsModel($row);
            array_push($couponsList, $couponsModel);
        }
        return $couponsList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM coupons WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createCouponsModel($row);
            }
        }
        return null;
    }

    public function insert($couponsModel): int
    {
        $query = "INSERT INTO coupons (code, quantity, percent, expired, description) VALUES (?, ?, ?, ?, ?)";
        $args = [$couponsModel->getCode(), $couponsModel->getQuantity(), $couponsModel->getPercent(), $couponsModel->getExpired(), $couponsModel->getDescription()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($couponsModel): int
    {
        $query = "UPDATE coupons SET code = ?, quantity = ?, percent = ?, expired = ?, description = ? WHERE id = ?";
        $args = [$couponsModel->getCode(), $couponsModel->getQuantity(), $couponsModel->getPercent(), $couponsModel->getExpired(), $couponsModel->getDescription(), $couponsModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($id): int
    {
        $query = "DELETE FROM coupons WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM coupons WHERE id LIKE ? OR code LIKE ? OR quantity LIKE ? OR percent LIKE ? OR expired LIKE ? OR description LIKE ?";
            $args = array_fill(0, 7, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM coupons WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM coupons WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $couponsList = [];
        while ($row = $rs->fetch_assoc()) {
            $couponsModel = $this->createCouponsModel($row);
            array_push($couponsList, $couponsModel);
        }
        return $couponsList;
    }
}
