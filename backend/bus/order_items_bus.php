<?php
require_once(__DIR__ . "/../models/order_items_model.php");
require_once(__DIR__ . "/../dao/order_items_dao.php");
require_once(__DIR__ . "/../interfaces/bus_interface.php");

class OrderItemsBUS implements BUSInterface
{
    private $orderItemsList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new OrderItemsBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->orderItemsList;
    }

    public function refreshData(): void
    {
        $this->orderItemsList = OrderItemsDAO::getInstance()->getAll();
    }

    public function getModelById(int $id)
    {
        return OrderItemsDAO::getInstance()->getById($id);
    }

    public function addModel($orderItemsModel): int
    {
        $this->validateModel($orderItemsModel);
        $result = OrderItemsDAO::getInstance()->insert($orderItemsModel);
        if ($result) {
            $this->orderItemsList[] = $orderItemsModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($orderItemsModel): int
    {
        $this->validateModel($orderItemsModel);
        $result = OrderItemsDAO::getInstance()->update($orderItemsModel);
        if ($result) {
            $index = array_search($orderItemsModel, $this->orderItemsList);
            $this->orderItemsList[$index] = $orderItemsModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($orderItemsModel): int
    {
        $result = OrderItemsDAO::getInstance()->delete($orderItemsModel);
        if ($result) {
            $index = array_search($orderItemsModel, $this->orderItemsList);
            unset($this->orderItemsList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function validateModel($orderItemsModel)
    {
        if (
            empty($orderItemsModel->getOrderId()) ||
            empty($orderItemsModel->getProductId()) ||
            empty($orderItemsModel->getQuantity()) ||
            empty($orderItemsModel->getPrice()) ||
            $orderItemsModel->getOrderId() == null ||
            $orderItemsModel->getProductId() == null ||
            $orderItemsModel->getQuantity() == null ||
            $orderItemsModel->getPrice() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }

    public function searchModel(string $value, array $columns)
    {
        return OrderItemsDAO::getInstance()->search($value, $columns);
    }
}
