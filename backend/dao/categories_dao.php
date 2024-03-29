<?php
require_once(__DIR__ . "/../interfaces/dao_interface.php");
require_once(__DIR__ . "/../models/categories_model.php");
require_once(__DIR__ . "/../dao/database_connection.php");

class CategoriesDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CategoriesDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $categoriesList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM categories");
        while ($row = $rs->fetch_assoc()) {
            $categoriesModel = $this->createCategoriesModel($row);
            array_push($categoriesList, $categoriesModel);
        }
        return $categoriesList;
    }
    private function createCategoriesModel($rs)
    {
        $id = $rs['id'];
        $name = $rs['name'];
        return new CategoriesModel($id, $name);
    }
    public function getAll(): array
    {
        $categoriesList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM categories");
        while ($row = $rs->fetch_assoc()) {
            $categoriesModel = $this->createCategoriesModel($row);
            array_push($categoriesList, $categoriesModel);
        }
        return $categoriesList;
    }
    public function getById($id)
    {
        $query = "SELECT * FROM categories WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createCategoriesModel($row);
            }
        }
        return null;
    }

    public function insert($categoriesModel): int
    {
        $query = "INSERT INTO categories (name) VALUES (?)";
        $args = [$categoriesModel->getName()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($categoriesModel): int
    {
        $query = "UPDATE categories SET name = ? WHERE id = ?";
        $args = [$categoriesModel->getName(), $categoriesModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($id): int
    {
        $query = "DELETE FROM categories WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM categories WHERE id LIKE ? OR name LIKE ?";
            $args = array_fill(0,  2, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM categories WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM categories WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $categoriesList = [];
        while ($row = $rs->fetch_assoc()) {
            $categoriesModel = $this->createCategoriesModel($row);
            array_push($categoriesList, $categoriesModel);
        }
        if (count($categoriesList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $categoriesList;
    }
}
