<?php
require_once(__DIR__ . "/../dao/database_connection.php");
require_once(__DIR__ . "/../models/permission_model.php");
require_once(__DIR__ . "/../interfaces/dao_interface.php");


class PermissionDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PermissionDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $permissionList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM permissions");
        while ($row = $rs->fetch_assoc()) {
            $permissionModel = $this->createPermissionModel($row);
            array_push($permissionList, $permissionModel);
        }
        return $permissionList;
    }
    private function createPermissionModel($rs)
    {
        $id = $rs['id'];
        $name = $rs['name'];
        return new PermissionsModel($id, $name);
    }
    public function getAll(): array
    {
        $permissionList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM permissions");
        while ($row = $rs->fetch_assoc()) {
            $permissionModel = $this->createPermissionModel($row);
            array_push($permissionList, $permissionModel);
        }
        return $permissionList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM permissions WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createPermissionModel($row);
            }
        }
        return null;
    }

    public function insert($permission): int
    {
        $query = "INSERT INTO permissions (name) VALUES (?)";
        $args = [$permission->getName()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($permission): int
    {
        $query = "UPDATE permissions SET name = ? WHERE id = ?";
        $args = [$permission->getName(), $permission->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM permissions WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM permissions WHERE id LIKE ? OR name LIKE ?";
            $args = array_fill(0, 2, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM permissions WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM permissions WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $permissionList = [];
        while ($row = $rs->fetch_assoc()) {
            $permissionModel = $this->createPermissionModel($row);
            array_push($permissionList, $permissionModel);
        }
        if (count($permissionList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $permissionList;
    }
}
