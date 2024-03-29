<?php
require_once(__DIR__ . "/../dao/database_connection.php");
require_once(__DIR__ . "/../models/size_items_model.php");
require_once(__DIR__ . "/../interfaces/dao_interface.php");

class SizeItemsDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SizeItemsDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $sizeItemList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM size_items");
        while ($row = $rs->fetch_assoc()) {
            $sizeItemModel = $this->createSizeItemModel($row);
            array_push($sizeItemList, $sizeItemModel);
        }
        return $sizeItemList;
    }

    private function createSizeItemModel($rs)
    {
        $id = $rs['id'];
        $productId = $rs['product_id'];
        $sizeId = $rs['size_id'];
        $quantity = $rs['quantity'];
        return new SizeItemsModel($id, $productId, $sizeId, $quantity);
    }

    public function getAll(): array
    {
        $sizeItemList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM size_items");
        while ($row = $rs->fetch_assoc()) {
            $sizeItemModel = $this->createSizeItemModel($row);
            array_push($sizeItemList, $sizeItemModel);
        }
        return $sizeItemList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM size_items WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createSizeItemModel($row);
            }
        }
        return null;
    }

    public function insert($sizeItem): int
    {
        $insertSql = "INSERT INTO size_items (product_id, size_id, quantity) VALUES (?, ?, ?)";
        $args = [
            $sizeItem->getProductId(),
            $sizeItem->getSizeId(),
            $sizeItem->getQuantity(),
        ];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($sizeItem): int
    {
        $updateSql = "UPDATE size_items SET product_id = ?, size_id = ? WHERE quantity = ?";
        $args = [
            $sizeItem->getProductId(),
            $sizeItem->getSizeId(),
            $sizeItem->getQuantity(),
        ];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    public function delete(int $id): int
    {
        $deleteSql = "DELETE FROM size_items WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM size_items WHERE id LIKE ? OR product_id LIKE ? OR size_id LIKE ? OR quantity LIKE ?";
            $args = array_fill(0,  4, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM size_items WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM size_items WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $sizeItemList = [];
        while ($row = $rs->fetch_assoc()) {
            $sizeItemModel = $this->createSizeItemModel($row);
            array_push($sizeItemList, $sizeItemModel);
        }
        if (count($sizeItemList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $sizeItemList;
    }
}
