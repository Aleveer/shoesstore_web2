<?php
require_once(__DIR__ . "/../interfaces/bus_interface.php");
require_once(__DIR__ . "/../dao/review_dao.php");
require_once(__DIR__ . "/../models/review_model.php");
class ReviewBUS implements BUSInterface
{
    private $reviewList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ReviewBUS();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->refreshData();
    }
    public function getAllModels(): array
    {
        return $this->reviewList;
    }
    public function refreshData(): void
    {
        $this->reviewList = ReviewDAO::getInstance()->getAll();
    }
    public function getModelById(int $id)
    {
        return ReviewDAO::getInstance()->getById($id);
    }
    public function addModel($reviewModel): int
    {
        $this->validateModel($reviewModel);
        $result = ReviewDAO::getInstance()->insert($reviewModel);
        if ($result) {
            $this->reviewList[] = $reviewModel;
            $this->refreshData();
        }
        return $result;
    }
    public function updateModel($reviewModel): int
    {
        $this->validateModel($reviewModel);
        $result = ReviewDAO::getInstance()->update($reviewModel);
        if ($result) {
            $index = array_search($reviewModel, $this->reviewList);
            $this->reviewList[$index] = $reviewModel;
            $this->refreshData();
        }
        return $result;
    }
    public function deleteModel($reviewModel): int
    {
        $result = ReviewDAO::getInstance()->delete($reviewModel);
        if ($result) {
            $index = array_search($reviewModel, $this->reviewList);
            unset($this->reviewList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return ReviewDAO::getInstance()->search($value, $columns);
    }

    private function validateModel($reviewModel)
    {
        if (
            empty($reviewModel->getProductId()) ||
            empty($reviewModel->getUserId()) ||
            empty($reviewModel->getRating()) ||
            empty($reviewModel->getContent()) ||
            $reviewModel->getProductId() == null ||
            $reviewModel->getUserId() == null ||
            $reviewModel->getRating() == null ||
            $reviewModel->getContent() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
        if ($reviewModel->getRating() < 1 || $reviewModel->getRating() > 5) {
            throw new InvalidArgumentException("Rating must be between 1 and 5");
        }
    }
}
