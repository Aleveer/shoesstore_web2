<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\ImportModel;
use backend\services\DatabaseConnection;

class ImportDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ImportDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $importList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM imports");
        while ($row = $rs->fetch_assoc()) {
            $importModel = $this->createImportModel($row);
            array_push($importList, $importModel);
        }
        return $importList;
    }

    private function createImportModel($rs)
    {
        $id = $rs['id'];
        $userId = $rs['user_id'];
        $totalPrice = $rs['total_price'];
        $importDate = $rs['import_date'];
        return new ImportModel($id, $userId, $totalPrice, $importDate);
    }

    public function getAll(): array
    {
        $importList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM imports");
        while ($row = $rs->fetch_assoc()) {
            $importModel = $this->createImportModel($row);
            array_push($importList, $importModel);
        }
        return $importList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM imports WHERE id = ?";
        $rs = DatabaseConnection::executeQuery($query, $id);
        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            if ($row) {
                return $this->createImportModel($row);
            }
        }
    }

    public function insert($importModel): int
    {
        $query = "INSERT INTO imports (user_id, total_price, import_date) VALUES (?, ?, ?)";
        $args = [$importModel->getUserId(), $importModel->getTotalPrice(), $importModel->getImportDate()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($importModel): int
    {
        $query = "UPDATE imports SET user_id = ?, total_price = ?, import_date = ? WHERE id = ?";
        $args = [$importModel->getUserId(), $importModel->getTotalPrice(), $importModel->getImportDate(), $importModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($id): int
    {
        $query = "DELETE FROM imports WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM imports WHERE id LIKE ? OR user_id LIKE ? OR total_price LIKE ? OR import_date LIKE ?";
            $args = array_fill(0,  4, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM imports WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM imports WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $importList = [];
        while ($row = $rs->fetch_assoc()) {
            $importModel = $this->createImportModel($row);
            array_push($importList, $importModel);
        }
        if (count($importList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $importList;
    }
}
