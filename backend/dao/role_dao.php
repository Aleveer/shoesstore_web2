<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\RoleModel;
use backend\services\DatabaseConnection;

class RoleDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RoleDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $roleList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM roles");
        while ($row = $rs->fetch_assoc()) {
            $roleModel = $this->createRoleModel($row);
            array_push($roleList, $roleModel);
        }
        return $roleList;
    }

    private function createRoleModel($rs)
    {
        $id = $rs['id'];
        $name = $rs['name'];
        return new RoleModel($id, $name);
    }

    public function getAll(): array
    {
        $roleList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM roles");
        while ($row = $rs->fetch_assoc()) {
            $roleModel = $this->createRoleModel($row);
            array_push($roleList, $roleModel);
        }
        return $roleList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM roles WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createRoleModel($row);
            }
        }
        return null;
    }

    public function insert($role): int
    {
        $insertSql = "INSERT INTO roles (name) VALUES (?)";
        $args = [$role->getName()];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($role): int
    {
        $updateSql = "UPDATE roles SET name = ? WHERE id = ?";
        $args = [$role->getName(), $role->getId()];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    public function delete(int $id): int
    {
        $deleteSql = "DELETE FROM roles WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM roles WHERE id LIKE ? OR name LIKE ?";
            $args = array_fill(0,  2, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM roles WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM roles WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $roleList = [];
        while ($row = $rs->fetch_assoc()) {
            $roleModel = $this->createRoleModel($row);
            array_push($roleList, $roleModel);
        }
        if (count($roleList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $roleList;
    }
}