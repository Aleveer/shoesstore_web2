<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\ProductModel;
use backend\services\DatabaseConnection;

class ProductDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ProductDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $productList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM products");
        while ($row = $rs->fetch_assoc()) {
            $productModel = $this->createProductModel($row);
            array_push($productList, $productModel);
        }
        return $productList;
    }

    private function createProductModel($rs)
    {
        $id = $rs['id'];
        $name = $rs['name'];
        $categoryId = $rs['category_id'];
        $price = $rs['price'];
        $description = $rs['description'];
        $image = $rs['image'];
        $gender = $rs['gender'];
        $status = $rs['status'];
        return new ProductModel($id, $name, $categoryId, $price, $description, $image, $gender, $status);
    }

    public function getAll(): array
    {
        $productList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM products");
        while ($row = $rs->fetch_assoc()) {
            $productModel = $this->createProductModel($row);
            array_push($productList, $productModel);
        }
        return $productList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM products WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createProductModel($row);
            }
        }
        return null;
    }

    public function getActiveProducts()
    {
        $query = "SELECT * FROM products WHERE status = 'active'";
        $rs = DatabaseConnection::executeQuery($query);
        $productList = [];
        while ($row = $rs->fetch_assoc()) {
            $productModel = $this->createProductModel($row);
            array_push($productList, $productModel);
        }
        return $productList;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO products (name, category_id, price, description, image, gender, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $args = [
            $data->getName(),
            $data->getCategoryId(),
            $data->getPrice(),
            $data->getDescription(),
            $data->getImage(),
            $data->getGender(),
            $data->getStatus()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE products SET name = ?, category_id = ?, price = ?, description = ?, image = ?, gender = ?, status = ? WHERE id = ?";
        $args = [
            $data->getName(),
            $data->getCategoryId(),
            $data->getPrice(),
            $data->getDescription(),
            $data->getImage(),
            $data->getGender(),
            $data->getStatus(),
            $data->getId()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM products WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            return [];
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM products WHERE id LIKE ? OR name LIKE ? OR category_id LIKE ? OR price LIKE ? OR description LIKE ? OR image LIKE ? OR gender LIKE ? OR status LIKE ?";
            $args = array_fill(0, 7, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM products WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM products WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $productList = [];
        while ($row = $rs->fetch_assoc()) {
            $productModel = $this->createProductModel($row);
            array_push($productList, $productModel);
        }
        if (count($productList) === 0) {
            return [];
        }
        return $productList;
    }
}
