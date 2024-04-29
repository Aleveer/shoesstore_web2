<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\UserPermissionModel;
use backend\services\DatabaseConnection;

class UserPermissionDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new UserPermissionDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $permissionList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM user_permissions");
        while ($row = $rs->fetch_assoc()) {
            $permissionModel = $this->createUserPermissionModel($row);
            array_push($permissionList, $permissionModel);
        }
        return $permissionList;
    }

    private function createUserPermissionModel($rs)
    {
        $id = $rs['id'];
        $permissionId = $rs['permission_id'];
        $userId = $rs['user_id'];
        $status = strtoupper($rs['status']);
        return new UserPermissionModel($id, $permissionId, $userId, $status);
    }

    public function getAll(): array
    {
        $permissionList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM user_permissions");
        while ($row = $rs->fetch_assoc()) {
            $permissionModel = $this->createUserPermissionModel($row);
            array_push($permissionList, $permissionModel);
        }
        return $permissionList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM user_permissions WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createUserPermissionModel($row);
            }
        }
        return null;
    }

    public function insert($userPermission): int
    {
        $query = "INSERT INTO user_permissions (permission_id, user_id, status) VALUES (?, ?, ?)";
        $args = [
            $userPermission->getPermissionId(),
            $userPermission->getUserId(),
            strtoupper($userPermission->getStatus())
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($userPermission): int
    {
        $query = "UPDATE user_permissions SET permission_id = ?, user_id = ?, status = ? WHERE id = ?";
        $args = [
            $userPermission->getPermissionId(),
            $userPermission->getUserId(),
            $userPermission->getStatus(),
            $userPermission->getId()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM user_permissions WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM user_permissions WHERE id LIKE ? OR permission_id LIKE ? OR user_id LIKE ? OR status LIKE ?";
            $args = array_fill(0,  4, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM user_permissions WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM user_permissions WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $userPermissionList = [];
        while ($row = $rs->fetch_assoc()) {
            $userPermissionModel = $this->createUserPermissionModel($row);
            array_push($userPermissionList, $userPermissionModel);
        }
        if (count($userPermissionList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $userPermissionList;
    }
}
