<?php
require_once(__DIR__ . "/../interfaces/dao_interface.php");
require_once(__DIR__ . "/../models/review_model.php");
require_once(__DIR__ . "/../dao/database_connection.php");

class ReviewDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ReviewDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $reviewList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM reviews");
        while ($row = $rs->fetch_assoc()) {
            $reviewModel = $this->createReviewModel($row);
            array_push($reviewList, $reviewModel);
        }
        return $reviewList;
    }

    private function createReviewModel($rs)
    {
        $id = $rs['id'];
        $productId = $rs['product_id'];
        $userId = $rs['user_id'];
        $content = $rs['content'];
        $rating = $rs['rating'];
        return new ReviewModel($id, $userId, $productId, $content, $rating);
    }

    public function getAll(): array
    {
        $reviewList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM reviews");
        while ($row = $rs->fetch_assoc()) {
            $reviewModel = $this->createReviewModel($row);
            array_push($reviewList, $reviewModel);
        }
        return $reviewList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM reviews WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createReviewModel($row);
            }
        }
        return null;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO reviews (user_id, product_id, content, rating) VALUES (?, ?, ?, ?)";
        $args = [$data->getUserId(), $data->getProductId(), $data->getContent(), $data->getRating()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE reviews SET user_id = ?, product_id = ?, content = ?, rating = ? WHERE id = ?";
        $args = [$data->getUserId(), $data->getProductId(), $data->getContent(), $data->getRating(), $data->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM reviews WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM reviews WHERE id LIKE ? OR user_id LIKE ? OR product_id LIKE ? OR content LIKE ? OR rating LIKE ?";
            $args = array_fill(0,  5, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM reviews WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM reviews WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $reviewList = [];
        while ($row = $rs->fetch_assoc()) {
            $reviewModel = $this->createReviewModel($row);
            array_push($reviewList, $reviewModel);
        }
        if (count($reviewList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $reviewList;
    }
}
