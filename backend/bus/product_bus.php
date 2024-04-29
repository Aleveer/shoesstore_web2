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
        if (
            empty($productModel->getName()) ||
            empty($productModel->getCategoryId()) ||
            empty($productModel->getPrice()) ||
            empty($productModel->getDescription()) ||
            empty($productModel->getImage()) ||
            empty($productModel->getGender() ||
            empty($productModel->getStatus()) ||
            $productModel->getName() == null ||
            $productModel->getCategoryId() == null ||
            $productModel->getPrice() == null ||
            $productModel->getDescription() == null ||
            $productModel->getImage() == null ||
            $productModel->getGender() == null ||
            $productModel->getStatus() == null)
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }

        if ($productModel->getPrice() < 0) {
            throw new InvalidArgumentException("Price must be greater than 0");
        }

        if ($productModel->getCategoryId() < 0) {
            throw new InvalidArgumentException("Category ID must be greater than 0");
        }

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

}
