<?php
require_once(__DIR__ . "/../dao/carts_dao.php");
require_once(__DIR__ . "/../interfaces/bus_interface.php");
require_once(__DIR__ . "/../models/carts_model.php");

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

    public function getModelById(int $id)
    {
        return CartsDAO::getInstance()->getById($id);
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
