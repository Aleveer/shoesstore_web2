<?php

namespace backend\dao;

use backend\models\StatisticModel;
use backend\models\OrdersModel;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\services\DatabaseConnection;

class OrdersDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new OrdersDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $ordersList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM orders");
        while ($row = $rs->fetch_assoc()) {
            $ordersModel = $this->createOrdersModel($row);
            array_push($ordersList, $ordersModel);
        }
        return $ordersList;
    }
    private function createOrdersModel($rs)
    {
        $id = $rs['id'];
        $userId = $rs['user_id'];
        $orderDate = $rs['order_date'];
        $totalAmount = $rs['total_amount'];
        $customerName = $rs['customer_name'];
        $customerPhone = $rs['customer_phone'];
        $customerAddress = $rs['customer_address'];
        $status = $rs['status'];
        return new OrdersModel($id, $userId, $orderDate, $totalAmount, $customerName, $customerPhone, $customerAddress, $status);
    }

    public function getAll(): array
    {
        $ordersList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM orders");
        while ($row = $rs->fetch_assoc()) {
            $ordersModel = $this->createOrdersModel($row);
            array_push($ordersList, $ordersModel);
        }
        return $ordersList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM orders WHERE id = ?";
        $rs = DatabaseConnection::executeQuery($query, $id);
        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            if ($row) {
                return $this->createOrdersModel($row);
            }
        }
        return null;
    }

    public function getLastOrderId(): int
    {
        $sql = "SELECT MAX(id) as maxId FROM orders";
        $result = DatabaseConnection::getInstance()->getConnection()->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['maxId'];
        }
        return 0;
    }

    public function insert($ordersModel): int
    {
        $query = "INSERT INTO orders (user_id, order_date, total_amount, customer_name, customer_phone, customer_address, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $args = [
            $ordersModel->getUserId(),
            $ordersModel->getOrderDate(),
            $ordersModel->getTotalAmount(),
            $ordersModel->getCustomerName(),
            $ordersModel->getCustomerPhone(),
            $ordersModel->getCustomerAddress(),
            $ordersModel->getStatus()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($ordersModel): int
    {
        $query = "UPDATE orders SET user_id = ?, order_date = ?, total_amount = ?, customer_name = ?, customer_phone = ?, customer_address = ?, status = ? WHERE id = ?";
        $args = [
            $ordersModel->getUserId(),
            $ordersModel->getOrderDate(),
            $ordersModel->getTotalAmount(),
            $ordersModel->getCustomerName(),
            $ordersModel->getCustomerPhone(),
            $ordersModel->getCustomerAddress(),
            $ordersModel->getStatus(),
            $ordersModel->getId()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM orders WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM orders WHERE id LIKE ? OR user_id LIKE ? OR order_date LIKE ? OR total_amount LIKE ? or customer_name LIKE ? or customer_phone LIKE ? or customer_address like ?";
            $args = array_fill(0, 5, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM orders WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM orders WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $ordersList = [];
        while ($row = $rs->fetch_assoc()) {
            $ordersModel = $this->createOrdersModel($row);
            array_push($ordersList, $ordersModel);
        }
        return $ordersList;
    }

    public function getAllThongKe(): array
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new StatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayTuNgayDen($ngayTu, $ngayDen): array
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        WHERE
            o.order_date BETWEEN ? AND ?
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $args = [
            $ngayTu,
            $ngayDen
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new StatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayTu($ngayTu)
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        WHERE
            o.order_date >= ?
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query, $ngayTu);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new StatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayDen($ngayDen)
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        WHERE
            o.order_date <= ?
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query, $ngayDen);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new StatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }
}