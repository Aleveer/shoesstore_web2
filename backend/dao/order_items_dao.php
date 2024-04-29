<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\OrderItemsModel;
use backend\services\DatabaseConnection;

class OrderItemsDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new OrderItemsDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $orderItemsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM order_items");
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createOrderItemsModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        return $orderItemsList;
    }
    private function createOrderItemsModel($rs)
    {
        $id = $rs['id'];
        $orderId = $rs['order_id'];
        $productId = $rs['product_id'];
        $sizeId = $rs['size_id'];
        $quantity = $rs['quantity'];
        $price = $rs['price'];
        return new OrderItemsModel($id, $orderId, $productId, $sizeId, $quantity, $price);
    }

    public function getAll(): array
    {
        $orderItemsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM order_items");
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createOrderItemsModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        return $orderItemsList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM order_items WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createOrderItemsModel($row);
            }
        }
        return null;
    }

    public function getOrderItemsListByOrderId($orderId)
    {
        $query = "SELECT * FROM order_items WHERE order_id = ?";
        $rs = DatabaseConnection::executeQuery($query, $orderId);
        $orderItemsList = [];
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createOrderItemsModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        return $orderItemsList;
    }

    public function getOrderItemsListByProductId($productId)
    {
        $query = "SELECT * FROM order_items WHERE product_id = ?";
        $rs = DatabaseConnection::executeQuery($query, $productId);
        $orderItemsList = [];
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createOrderItemsModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        return $orderItemsList;
    }

    public function insert($orderItemsModel): int
    {
        $query = "INSERT INTO order_items (order_id, product_id, size_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $args = [$orderItemsModel->getOrderId(), $orderItemsModel->getProductId(), $orderItemsModel->getSizeId(), $orderItemsModel->getQuantity(), $orderItemsModel->getPrice()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($orderItemsModel): int
    {
        $query = "UPDATE order_items SET order_id = ?, product_id = ?, size_id = ?, quantity = ?, price = ? WHERE id = ?";
        $args = [$orderItemsModel->getOrderId(), $orderItemsModel->getProductId(), $orderItemsModel->getSizeId(), $orderItemsModel->getQuantity(), $orderItemsModel->getPrice(), $orderItemsModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($id): int
    {
        $query = "DELETE FROM order_items WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function getByOrderId($orderId)
    {
        $query = "SELECT * FROM order_items WHERE order_id = ?";
        $rs = DatabaseConnection::executeQuery($query, $orderId);
        $orderItemsList = [];
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createOrderItemsModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        return $orderItemsList;
    }

    public function deleteByOrderId($orderId): int
    {
        $query = "DELETE FROM order_items WHERE order_id = ?";
        return DatabaseConnection::executeUpdate($query, $orderId);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM order_items WHERE id LIKE ? OR order_id LIKE ? OR product_id LIKE ? OR size_id LIKE ? OR quantity LIKE ? OR price LIKE ?";
            $args = array_fill(0, 6, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM order_items WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM order_items WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $orderItemsList = [];
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createOrderItemsModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        if (count($orderItemsList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $orderItemsList;
    }
}
