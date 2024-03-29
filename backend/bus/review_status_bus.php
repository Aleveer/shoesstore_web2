<?php
require_once(__DIR__ . "/../models/review_model.php");
require_once(__DIR__ . "/../interfaces/bus_interface.php");
require_once(__DIR__ . "/../dao/review_dao.php");

class ReviewStatusBUS implements BUSInterface
{
    private $reviewStatusList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ReviewStatusBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->reviewStatusList;
    }

    public function refreshData(): void
    {
        $this->reviewStatusList = ReviewStatusDAO::getInstance()->getAll();
    }

    public function getModelById(int $id)
    {
        return ReviewStatusDAO::getInstance()->getById($id);
    }

    public function addModel($reviewStatusModel): int
    {
        $this->validateModel($reviewStatusModel);
        $result = ReviewStatusDAO::getInstance()->insert($reviewStatusModel);
        if ($result) {
            $this->reviewStatusList[] = $reviewStatusModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($reviewStatusModel): int
    {
        $this->validateModel($reviewStatusModel);
        $result = ReviewStatusDAO::getInstance()->update($reviewStatusModel);
        if ($result) {
            $index = array_search($reviewStatusModel, $this->reviewStatusList);
            $this->reviewStatusList[$index] = $reviewStatusModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($reviewStatusModel): int
    {
        $result = ReviewStatusDAO::getInstance()->delete($reviewStatusModel);
        if ($result) {
            $index = array_search($reviewStatusModel, $this->reviewStatusList);
            unset($this->reviewStatusList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return ReviewStatusDAO::getInstance()->search($value, $columns);
    }

    private function validateModel($reviewStatusModel)
    {
        if (
            empty($reviewStatusModel->getProductId()) ||
            empty($reviewStatusModel->getStatus()) ||
            $reviewStatusModel->getProductId() == null ||
            $reviewStatusModel->getStatus() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }
}
