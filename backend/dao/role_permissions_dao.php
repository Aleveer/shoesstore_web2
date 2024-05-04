<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\RolePermissionsModel;
use backend\services\DatabaseConnection;

class RolePermissionDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RolePermissionDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $rolePermissionList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM roles_permissions");
        while ($row = $rs->fetch_assoc()) {
            $rolePermissionModel = $this->createRolePermissionModel($row);
            array_push($rolePermissionList, $rolePermissionModel);
        }
        return $rolePermissionList;
    }

    private function createRolePermissionModel($rs)
    {
        $id = $rs['id'];
        $roleId = $rs['role_id'];
        $permissionId = $rs['permission_id'];
        return new RolePermissionsModel($id, $permissionId, $roleId);
    }

    public function getAll(): array
    {
        $rolePermissionList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM roles_permissions");
        while ($row = $rs->fetch_assoc()) {
            $rolePermissionModel = $this->createRolePermissionModel($row);
            array_push($rolePermissionList, $rolePermissionModel);
        }
        return $rolePermissionList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM roles_permissions WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createRolePermissionModel($row);
            }
        }
        return null;
    }

    public function getByRoleId($roleId)
    {
        $query = "SELECT * FROM roles_permissions WHERE role_id = ?";
        $result = DatabaseConnection::executeQuery($query, $roleId);
        $rolePermissionList = [];
        while ($row = $result->fetch_assoc()) {
            $rolePermissionModel = $this->createRolePermissionModel($row);
            array_push($rolePermissionList, $rolePermissionModel);
        }
        return $rolePermissionList;
    }

    public function getByRoleIdAndPermissionId($roleId, $permissionId)
    {
        $query = "SELECT * FROM roles_permissions WHERE role_id = ? AND permission_id = ?";
        $result = DatabaseConnection::executeQuery($query, $roleId, $permissionId);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createRolePermissionModel($row);
            }
        }
        return null;
    }

    public function insert($rolePermission): int
    {
        $insertSql = "INSERT INTO roles_permissions (role_id, permission_id) VALUES (?, ?)";
        $args = [
            $rolePermission->getRoleId(),
            $rolePermission->getPermissionId()
        ];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($rolePermission): int
    {
        $updateSql = "UPDATE roles_permissions SET role_id = ?, permission_id = ? WHERE id = ?";
        $args = [
            $rolePermission->getRoleId(),
            $rolePermission->getPermissionId(),
            $rolePermission->getId()
        ];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    public function delete(int $id): int
    {
        $deleteSql = "DELETE FROM roles_permissions WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function deleteByRoleId($roleId): int
    {
        $deleteSql = "DELETE FROM roles_permissions WHERE role_id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $roleId);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM roles_permissions WHERE id LIKE ? OR role_id LIKE ? OR permission_id LIKE ?";
            $args = array_fill(0, 3, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM roles_permissions WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM roles_permissions WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $rolePermissionList = [];
        while ($row = $rs->fetch_assoc()) {
            $rolePermissionModel = $this->createRolePermissionModel($row);
            array_push($rolePermissionList, $rolePermissionModel);
        }
        return $rolePermissionList;
    }
}
