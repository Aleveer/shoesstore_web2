<?php
require_once(__DIR__ . "/../interfaces/dao_interface.php");
require_once(__DIR__ . "/../models/size_model.php");
require_once(__DIR__ . "/../dao/database_connection.php");

class SizeDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SizeDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $sizeList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM sizes");
        while ($row = $rs->fetch_assoc()) {
            $sizeModel = $this->createSizeModel($row);
            array_push($sizeList, $sizeModel);
        }
        return $sizeList;
    }

    private function createSizeModel($rs)
    {
        $id = $rs['id'];
        $name = $rs['name'];
        return new SizeModel($id, $name);
    }

    public function getAll(): array
    {
        $sizeList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM sizes");
        while ($row = $rs->fetch_assoc()) {
            $sizeModel = $this->createSizeModel($row);
            array_push($sizeList, $sizeModel);
        }
        return $sizeList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM sizes WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createSizeModel($row);
            }
        }
        return null;
    }

    public function insert($size): int
    {
        $insertSql = "INSERT INTO sizes (name) VALUES (?)";
        $args = [$size->getName()];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($size): int
    {
        $updateSql = "UPDATE sizes SET name = ? WHERE id = ?";
        $args = [$size->getName(), $size->getId()];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    public function delete(int $id): int
    {
        $deleteSql = "DELETE FROM sizes WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM sizes WHERE id LIKE ? OR name LIKE ?";
            $args = array_fill(0,  2, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM sizes WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM sizes WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $sizeList = [];
        while ($row = $rs->fetch_assoc()) {
            $sizeModel = $this->createSizeModel($row);
            array_push($sizeList, $sizeModel);
        }
        if (count($sizeList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $sizeList;
    }
}
