<?php
require_once(__DIR__ . "/../interfaces/dao_interface.php");
require_once(__DIR__ . "/../models/user_model.php");
require_once(__DIR__ . "/../dao/database_connection.php");
class UserDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new UserDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $userList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM users");
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }

    private function createUserModel($rs)
    {
        $id = $rs['id'];
        $username = $rs['username'];
        $password = $rs['password'];
        $email = $rs['email'];
        $name = $rs['name'];
        $phone = $rs['phone'];
        $gender = $rs['gender'];
        $image = $rs['image'];
        $roleId = $rs['role_id'];
        $address = $rs['address'];
        $status = strtoupper($rs['status']);
        return new UserModel($id, $username, $password, $email, $name, $phone, $gender, $image, $roleId, $status, $address);
    }

    public function getAll(): array
    {
        $userList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM users");
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $result = DatabaseConnection::executeQuery($sql, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createUserModel($row);
            }
        }
        return null;
    }

    public function insert($user): int
    {
        $insertSql = "INSERT INTO users (username, password, email, name, phone, gender, image, role_id, address, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $args = [
            $user->getUsername(),
            $user->getPassword(),
            $user->getEmail(),
            $user->getName(),
            $user->getPhone(),
            $user->getGender(),
            $user->getImage(),
            $user->getRoleId(),
            $user->getAddress(),
            strtoupper($user->getStatus())
        ];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($user): int
    {
        $updateSql = "UPDATE users SET username = ?, password = ?, email = ?, name = ?, phone = ?, gender = ?, image = ?, role_id = ?, address = ?, status = ? WHERE id = ?";
        $args = [
            $user->getUsername(),
            $user->getPassword(),
            $user->getEmail(),
            $user->getName(),
            $user->getPhone(),
            $user->getGender(),
            $user->getImage(),
            $user->getRoleId(),
            $user->getAddress(),
            strtoupper($user->getStatus()),
            $user->getId()
        ];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    public function delete(int $id): int
    {
        $deleteSql = "DELETE FROM users WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM users WHERE id LIKE ? OR username LIKE ? OR email LIKE ? OR name LIKE ? OR phone LIKE ? OR gender LIKE ? OR role_id LIKE ? OR address LIKE ? OR status LIKE ?";
            $args = array_fill(0,  9, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM users WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM users WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $userList = [];
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        if (count($userList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $userList;
    }
}
