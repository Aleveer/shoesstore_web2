<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use backend\dao\SizeDAO;
use Exception;

class SizeBUS implements BUSInterface
{
    private $sizeList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SizeBUS();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->refreshData();
    }
    public function getAllModels(): array
    {
        return $this->sizeList;
    }
    public function refreshData(): void
    {
        $this->sizeList = SizeDAO::getInstance()->getAll();
    }
    public function getModelById(int $id)
    {
        return SizeDAO::getInstance()->getById($id);
    }
    public function addModel($sizeModel): int
    {
        $this->validateModel($sizeModel);
        $result = SizeDAO::getInstance()->insert($sizeModel);
        if ($result) {
            $this->sizeList[] = $sizeModel;
            $this->refreshData();
        }
        return $result;
    }
    public function updateModel($sizeModel): int
    {
        $this->validateModel($sizeModel);
        $result = SizeDAO::getInstance()->update($sizeModel);
        if ($result) {
            $index = array_search($sizeModel, $this->sizeList);
            $this->sizeList[$index] = $sizeModel;
            $this->refreshData();
        }
        return $result;
    }
    public function deleteModel($sizeModel): int
    {
        $result = SizeDAO::getInstance()->delete($sizeModel);
        if ($result) {
            $index = array_search($sizeModel, $this->sizeList);
            unset($this->sizeList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return SizeDAO::getInstance()->search($value, $columns);
    }

    public function validateModel($sizeModel): bool
    {
        if ($sizeModel->getName() == null || empty($sizeModel->getName())) {
            throw new Exception("Size name cannot be empty");
        }
        return true;
    }

    public function getModelBySize($size)
    {
        $result = array();
        foreach ($this->sizeList as $sizeModel) {
            if ($sizeModel->getSize() == $size) {
                $result[] = $sizeModel;
            }
        }
        return $result;
    }
}
