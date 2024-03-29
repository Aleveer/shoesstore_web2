<?php
require_once(__DIR__ . "/../interfaces/dao_interface.php");
require_once(__DIR__ . "/../models/review_status_model.php");
require_once(__DIR__ . "/../dao/database_connection.php");


class ReviewStatusDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ReviewStatusDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $statusList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM review_status");
        while ($row = $rs->fetch_assoc()) {
            $statusModel = $this->createStatusModel($row);
            array_push($statusList, $statusModel);
        }
        return $statusList;
    }

    private function createStatusModel($rs)
    {
        $id = $rs['id'];
        $productId = $rs['product_id'];
        $status = strtoupper($rs['status']);
        return new ReviewStatusModel($id, $productId, $status);
    }

    public function getAll(): array
    {
        $statusList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM review_status");
        while ($row = $rs->fetch_assoc()) {
            $statusModel = $this->createStatusModel($row);
            array_push($statusList, $statusModel);
        }
        return $statusList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM review_status WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createStatusModel($row);
            }
        }
        return null;
    }

    public function insert($status): int
    {
        $insertSql = "INSERT INTO review_status (product_id, status) VALUES (?)";
        $args = [$status->getStatus()];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($status): int
    {
        $updateSql = "UPDATE review_status SET product_id = ?, status = ? WHERE id = ?";
        $args = [$status->getProductId(), $status->getStatus(), $status->getId()];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    public function delete(int $id): int
    {
        $deleteSql = "DELETE FROM review_status WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM review_status WHERE id LIKE ? OR product_id LIKE ? OR status LIKE ?";
            $args = array_fill(0,  3, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM review_status WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM review_status WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $statusList = [];
        while ($row = $rs->fetch_assoc()) {
            $statusModel = $this->createStatusModel($row);
            array_push($statusList, $statusModel);
        }
        if (count($statusList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $statusList;
    }
}
