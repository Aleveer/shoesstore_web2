<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\CartsDAO;

class CartsBUS implements BUSInterface
{
    private $cartsList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CartsBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->cartsList;
    }

    public function refreshData(): void
    {
        $this->cartsList = CartsDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return CartsDAO::getInstance()->getById($id);
    }

    public function getModelByUserId(int $userId)
    {
        $this->cartsList = CartsDAO::getInstance()->getAll();
        $result = array();
        foreach ($this->cartsList as $carts) {
            if ($carts->getUserId() == $userId) {
                $result[] = $carts;
            }
        }
        return $result;
    }

    public function getModelByUserIdAndProductId($userId, $productId)
    {
        $this->cartsList = CartsDAO::getInstance()->getAll();
        $result = array();
        foreach ($this->cartsList as $carts) {
            if ($carts->getUserId() == $userId && $carts->getProductId() == $productId) {
                $result[] = $carts;
            }
        }
        return $result;
    }

    public function getModelByUserIdAndProductIdAndSizeId($userId, $productId, $sizeId)
    {
        $this->cartsList = CartsDAO::getInstance()->getAll();
        foreach ($this->cartsList as $carts) {
            if ($carts->getUserId() == $userId && $carts->getProductId() == $productId && $carts->getSizeId() == $sizeId) {
                return $carts;
            }
        }
        return null;
    }

    public function checkDuplicateProduct($userId, $productId, $sizeId)
    {
        foreach ($this->cartsList as $carts) {
            if ($carts->getUserId() == $userId && $carts->getProductId() == $productId && $carts->getSizeId() == $sizeId) {
                //If duplicated, increase the quantity by 1:
                $carts->setQuantity($carts->getQuantity() + 1);
                $this->updateModel($carts);
                return $carts;
            }
        }
        return null;
    }

    public function addModel($cartsModel): int
    {
        $this->validateModel($cartsModel);
        $result = CartsDAO::getInstance()->insert($cartsModel);
        if ($result) {
            $this->cartsList[] = $cartsModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($cartsModel): int
    {
        $this->validateModel($cartsModel);
        $result = CartsDAO::getInstance()->update($cartsModel);
        if ($result) {
            $index = array_search($cartsModel, $this->cartsList);
            $this->cartsList[$index] = $cartsModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel(int $id): int
    {
        $result = CartsDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->cartsList, 'id'));
            unset($this->cartsList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function searchModel(string $value, array $columns)
    {
        return CartsDAO::getInstance()->search($value, $columns);
    }

    private function validateModel($cartsModel): void
    {
        if (
            empty($cartsModel->getUserId()) ||
            empty($cartsModel->getProductId()) ||
            empty($cartsModel->getQuantity()) ||
            $cartsModel->getUserId() == null ||
            $cartsModel->getProductId() == null ||
            $cartsModel->getQuantity() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }
}
