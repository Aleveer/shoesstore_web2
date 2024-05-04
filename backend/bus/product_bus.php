<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\ProductDAO;
use Exception;

class ProductBUS implements BUSInterface
{
    private $productList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ProductBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->productList = ProductDAO::getInstance()->getAll();
    }

    public function getAllModels(): array
    {
        return $this->productList;
    }

    public function refreshData(): void
    {
        $this->productList = ProductDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return ProductDAO::getInstance()->getById($id);
    }

    public function getActiveProductOnly()
    {
        return ProductDAO::getInstance()->getActiveProducts();
    }

    public function addModel($productModel)
    {
        $this->validateModel($productModel);
        $newProduct = ProductDAO::getInstance()->insert($productModel);
        if ($newProduct) {
            $this->productList[] = $productModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $this->validateModel($model);
        $result = ProductDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->productList);
            $this->productList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = ProductDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->productList, 'id'));
            unset($this->productList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function searchModel(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            return [];
        }
        return ProductDAO::getInstance()->search($condition, $columnNames);
    }

    public function searchBetweenPrice($min, $max)
    {
        $result = [];
        foreach ($this->productList as $product) {
            if ($product->getPrice() >= $min && $product->getPrice() <= $max && $product->getStatus() == 'active') {
                $result[] = $product;
            }
        }
        return $result;
    }

    public function searchByMinimalPrice($min)
    {
        $result = [];
        foreach ($this->productList as $product) {
            if ($product->getPrice() >= $min && $product->getStatus() == 'active') {
                $result[] = $product;
            }
        }
        return $result;
    }

    public function searchByMaximalPrice($max)
    {
        $result = [];
        foreach ($this->productList as $product) {
            if ($product->getPrice() <= $max && $product->getStatus() == 'active') {
                $result[] = $product;
            }
        }
        return $result;
    }

    public function getRandomRecommendProducts()
    {
        $result = [];
        $activeProducts = array_filter($this->productList, function ($product) {
            return $product->getStatus() == 'active';
        });

        if (count($activeProducts) < 3) {
            throw new Exception('Not enough active products');
        }

        $randomKeys = array_rand($activeProducts, 3);
        foreach ($randomKeys as $key) {
            $result[] = $activeProducts[$key];
        }
        return $result;
    }

    public function validateModel($model)
    {
        if (empty($model->getName())) {
            throw new InvalidArgumentException("Name is required");
        }

        if (empty($model->getCategoryId())) {
            throw new InvalidArgumentException("Category ID is required");
        }

        if (empty($model->getPrice())) {
            throw new InvalidArgumentException("Price is required");
        }

        if (empty($model->getDescription())) {
            throw new InvalidArgumentException("Description is required");
        }

        if (empty($model->getImage())) {
            throw new InvalidArgumentException("Image is required");
        }

        if ($model->getGender() != 0 && $model->getGender() != 1) {
            throw new InvalidArgumentException("Gender is not valid");
        }

        if (empty($model->getStatus())) {
            throw new InvalidArgumentException("Status is required");
        }

        if (
            $model->getName() == null ||
            $model->getCategoryId() == null ||
            $model->getPrice() == null ||
            $model->getDescription() == null ||
            $model->getImage() == null ||
            $model->getStatus() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }

        if ($model->getPrice() < 0) {
            throw new InvalidArgumentException("Price must be greater than 0");
        }

        if ($model->getCategoryId() < 0) {
            throw new InvalidArgumentException("Category ID must be greater than 0");
        }
    }
}
