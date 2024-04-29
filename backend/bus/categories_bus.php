<?php
namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\CategoriesDAO;

class CategoriesBUS implements BUSInterface
{
    private $categoriesList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CategoriesBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->categoriesList;
    }

    public function refreshData(): void
    {
        $this->categoriesList = CategoriesDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return CategoriesDAO::getInstance()->getById($id);
    }

    public function addModel($categoriesModel)
    {
        $this->validateModel($categoriesModel);
        $result = CategoriesDAO::getInstance()->insert($categoriesModel);
        if ($result) {
            $this->categoriesList[] = $categoriesModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($categoriesModel): int
    {
        $this->validateModel($categoriesModel);
        $result = CategoriesDAO::getInstance()->update($categoriesModel);
        if ($result) {
            $index = array_search($categoriesModel, $this->categoriesList);
            $this->categoriesList[$index] = $categoriesModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($id)
    {
        $result = CategoriesDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->categoriesList, 'id'));
            unset($this->categoriesList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function validateModel($categoriesModel)
    {
        if (
            empty($categoriesModel->getName()) ||
            $categoriesModel->getName() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }

    public function getModelByName($name)
    {
        foreach ($this->categoriesList as $categories) {
            if ($categories->getName() == $name) {
                return $categories;
            }
        }
        return null;
    }

    public function searchModel(string $value, array $columns)
    {
        return CategoriesDAO::getInstance()->search($value, $columns);
    }
}
