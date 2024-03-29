<?php
require_once(__DIR__ . "/../interfaces/dao_interface.php");
require_once(__DIR__ . "/../models/payments_model.php");
require_once(__DIR__ . "/../dao/database_connection.php");

class PaymentsDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PaymentsDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $paymentList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM payments");
        while ($row = $rs->fetch_assoc()) {
            $paymentModel = $this->createPaymentModel($row);
            array_push($paymentList, $paymentModel);
        }
        return $paymentList;
    }
    private function createPaymentModel($rs)
    {
        $id = $rs['id'];
        $orderId = $rs['order_id'];
        $methodId = $rs['method_id'];
        $paymentDate = $rs['payment_date'];
        $totalPrice = $rs['total_price'];
        return new PaymentsModel($id, $orderId, $methodId, $paymentDate, $totalPrice);
    }

    public function getAll(): array
    {
        $paymentList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM payments");
        while ($row = $rs->fetch_assoc()) {
            $paymentModel = $this->createPaymentModel($row);
            array_push($paymentList, $paymentModel);
        }
        return $paymentList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM payments WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createPaymentModel($row);
            }
        }
        return null;
    }

    public function insert($payment): int
    {
        $query = "INSERT INTO payments (order_id, method_id, payment_date, total_price) VALUES (?, ?, ?, ?)";
        $args = [$payment->getOrderId(), $payment->getMethodId(), $payment->getPaymentDate(), $payment->getTotalPrice()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($payment): int
    {
        $query = "UPDATE payments SET order_id = ?, method_id = ?, payment_date = ?, total_price = ? WHERE id = ?";
        $args = [$payment->getOrderId(), $payment->getMethodId(), $payment->getPaymentDate(), $payment->getTotalPrice(), $payment->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM payments WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty(trim($condition))) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }

        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM payments WHERE CONCAT(id, order_id, method_id, payment_date, total_price) LIKE ?";
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM payments WHERE $column LIKE ?";
        } else {
            $columns = implode(", ", $columnNames);
            $query = "SELECT id, order_id, method_id, payment_date, total_price FROM payments WHERE CONCAT($columns) LIKE ?";
        }

        $args = ["%" . $condition . "%"];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $paymentList = [];
        while ($row = $rs->fetch_assoc()) {
            $paymentModel = $this->createPaymentModel($row);
            array_push($paymentList, $paymentModel);
        }

        if (count($paymentList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }

        return $paymentList;
    }
}
