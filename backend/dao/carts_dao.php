<?php
require_once(__DIR__ . "/../dao/database_connection.php");
require_once(__DIR__ . "/../models/carts_model.php");
require_once(__DIR__ . "/../interfaces/dao_interface.php");

class CartsDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CartsDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $cartsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM carts");
        while ($row = $rs->fetch_assoc()) {
            $cartsModel = $this->createCartsModel($row);
            array_push($cartsList, $cartsModel);
        }
        return $cartsList;
    }
    private function createCartsModel($rs)
    {
        $id = $rs['id'];
        $userId = $rs['user_id'];
        $productId = $rs['product_id'];
        $quantity = $rs['quantity'];
        $sizeId = $rs['size_id'];
        return new CartsModel($id, $userId, $productId, $quantity, $sizeId);
    }
    public function getAll(): array
    {
        $cartsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM carts");
        while ($row = $rs->fetch_assoc()) {
            $cartsModel = $this->createCartsModel($row);
            array_push($cartsList, $cartsModel);
        }
        return $cartsList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM carts WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createCartsModel($row);
            }
        }
        return null;
    }

    public function insert($cartsModel): int
    {
        $query = "INSERT INTO carts (user_id, product_id, quantity, size_id) VALUES (?, ?, ?, ?)";
        $args = [$cartsModel->getUserId(), $cartsModel->getProductId(), $cartsModel->getQuantity(), $cartsModel->getSizeId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($cartsModel): int
    {
        $query = "UPDATE carts SET user_id = ?, product_id = ?, quantity = ?, size_id = ? WHERE id = ?";
        $args = [$cartsModel->getUserId(), $cartsModel->getProductId(), $cartsModel->getQuantity(), $cartsModel->getSizeId(), $cartsModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($id): int
    {
        $query = "DELETE FROM carts WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM carts WHERE id LIKE ? OR user_id LIKE ? OR product_id LIKE ? OR quantity LIKE ? OR size_id LIKE ?";
            $args = array_fill(0,  5, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM carts WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM carts WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $cartsList = [];
        while ($row = $rs->fetch_assoc()) {
            $cartsModel = $this->createCartsModel($row);
            array_push($cartsList, $cartsModel);
        }
        if (count($cartsList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $cartsList;
    }
}
