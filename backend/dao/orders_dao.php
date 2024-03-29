<?php
require_once(__DIR__ . "/../dao/database_connection.php");
require_once(__DIR__ . "/../models/orders_model.php");
require_once(__DIR__ . "/../interfaces/dao_interface.php");

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
        $customerId = $rs['customer_id'];
        $userId = $rs['user_id'];
        $orderDate = $rs['order_date'];
        $totalAmount = $rs['   total_amount'];
        return new OrdersModel($id, $customerId, $userId, $orderDate, $totalAmount);
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

    public function insert($ordersModel): int
    {
        $query = "INSERT INTO orders (customer_id, user_id, order_date, total_amount) VALUES (?, ?, ?, ?)";
        $args = [$ordersModel->getCustomerId(), $ordersModel->getUserId(), $ordersModel->getOrderDate(), $ordersModel->getTotalAmount()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }
    public function update($ordersModel): int
    {
        $query = "UPDATE orders SET customer_id = ?, user_id = ?, order_date = ?, total_amount = ? WHERE id = ?";
        $args = [$ordersModel->getCustomerId(), $ordersModel->getUserId(), $ordersModel->getOrderDate(), $ordersModel->getTotalAmount(), $ordersModel->getId()];
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
            $query = "SELECT * FROM orders WHERE id LIKE ? OR customer_id LIKE ? OR user_id LIKE ? OR order_date LIKE ? OR total_amount LIKE ?";
            $args = array_fill(0,  5, "%" . $condition . "%");
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
        if (count($ordersList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $ordersList;
    }
}
