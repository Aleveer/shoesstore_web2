<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\TokenLoginModel;
use backend\services\DatabaseConnection;

class TokenLoginDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new TokenLoginDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $tokenLoginList = [];

        $rs = DatabaseConnection::executeQuery("SELECT * FROM tokenlogin");
        while ($row = $rs->fetch_assoc()) {
            $tokenLoginModel = $this->createTokenLoginModel($row);
            $tokenLoginList[] = $tokenLoginModel;
        }

        return $tokenLoginList;
    }

    private function createTokenLoginModel($rs)
    {
        $id = $rs['id'];
        $user_id = $rs['user_id'];
        $token = $rs['token'];
        $create_at = $rs['create_at'];
        return new TokenLoginModel($id, $user_id, $token, $create_at);
    }

    public function getAll(): array
    {
        $tokenLoginList = [];

        $rs = DatabaseConnection::executeQuery("SELECT * FROM tokenlogin");
        while ($row = $rs->fetch_assoc()) {
            $tokenLoginModel = $this->createTokenLoginModel($row);
            $tokenLoginList[] = $tokenLoginModel;
        }

        return $tokenLoginList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM tokenlogin WHERE id = ?";
        $rs = DatabaseConnection::executeQuery($query, $id);

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            if ($row) {
                return $this->createTokenLoginModel($row);
            }
        }
        return null;
    }

    public function insert($tokenLoginModel): int
    {
        $query = "INSERT INTO tokenlogin (user_id, token, create_at) VALUES (?, ?, ?)";
        $args = [$tokenLoginModel->getUserId(), $tokenLoginModel->getToken(), $tokenLoginModel->getCreateAt()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($tokenLoginModel): int
    {
        $query = "UPDATE tokenlogin SET user_id = ?, token = ?, create_at = ? WHERE id = ?";
        $args = [$tokenLoginModel->getUserId(), $tokenLoginModel->getToken(), $tokenLoginModel->getCreateAt(), $tokenLoginModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM tokenlogin WHERE id = ?";
        $args = [$id];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function search(string $condition, array $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM tokenlogin WHERE id LIKE ? OR user_id LIKE ? OR token LIKE ? OR create_at LIKE ?";
            $args = array_fill(0, 5, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM tokenlogin WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM tokenlogin WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $tokenLoginList = [];
        while ($row = $rs->fetch_assoc()) {
            $tokenLoginModel = $this->createTokenLoginModel($row);
            array_push($tokenLoginList, $tokenLoginModel);
        }
        if (count($tokenLoginList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $tokenLoginList;
    }
}
