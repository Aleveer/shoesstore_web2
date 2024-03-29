<?php
require_once(__DIR__ . "/../dao/database_connection.php");
require_once(__DIR__ . "/../models/import_items_model.php");
require_once(__DIR__ . "/../interfaces/dao_interface.php");

class ImportItemsDAO
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ImportItemsDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $importItemsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM import_items");
        while ($row = $rs->fetch_assoc()) {
            $importItemsModel = $this->createImportItemsModel($row);
            array_push($importItemsList, $importItemsModel);
        }
        return $importItemsList;
    }
    private function createImportItemsModel($rs)
    {
        $id = $rs['id'];
        $importId = $rs['import_id'];
        $productId = $rs['product_id'];
        $sizeId = $rs['size_id'];
        $quantity = $rs['quantity'];
        $price = $rs['price'];
        return new ImportItemsModel($id, $importId, $productId, $sizeId, $quantity, $price);
    }

    public function getAll(): array
    {
        $importItemsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM import_items");
        while ($row = $rs->fetch_assoc()) {
            $importItemsModel = $this->createImportItemsModel($row);
            array_push($importItemsList, $importItemsModel);
        }
        return $importItemsList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM import_items WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createImportItemsModel($row);
            }
        }
        return null;
    }

    public function insert($importItemsModel): int
    {
        $query = "INSERT INTO import_items (import_id, product_id, size_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $args = [$importItemsModel->getImportId(), $importItemsModel->getProductId(), $importItemsModel->getSizeId(), $importItemsModel->getQuantity(), $importItemsModel->getPrice()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($importItemsModel): int
    {
        $query = "UPDATE import_items SET import_id = ?, product_id = ?, size_id = ?, quantity = ?, price = ? WHERE id = ?";
        $args = [$importItemsModel->getImportId(), $importItemsModel->getProductId(), $importItemsModel->getSizeId(), $importItemsModel->getQuantity(), $importItemsModel->getPrice(), $importItemsModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($id): int
    {
        $query = "DELETE FROM import_items WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function deleteByImportId($id): int
    {
        $query = "DELETE FROM import_items WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM import_items WHERE id LIKE ? OR import_id LIKE ? OR product_id LIKE ? OR size_id LIKE ? OR quantity LIKE ? OR price LIKE ?";
            $args = array_fill(0,  6, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM import_items WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM import_items WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $importItemsList = [];
        while ($row = $rs->fetch_assoc()) {
            $importItemsModel = $this->createImportItemsModel($row);
            array_push($importItemsList, $importItemsModel);
        }
        if (count($importItemsList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $importItemsList;
    }
}
