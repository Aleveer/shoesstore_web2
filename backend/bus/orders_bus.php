<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\OrdersDAO;

class OrdersBUS implements BUSInterface
{
    private $ordersList = array();
    private $statisticList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new OrdersBUS();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->refreshData();
    }
    public function getAllModels(): array
    {
        return $this->ordersList;
    }
    public function refreshData(): void
    {
        $this->ordersList = OrdersDAO::getInstance()->getAll();
    }
    public function getModelById(int $id)
    {
        return OrdersDAO::getInstance()->getById($id);
    }

    public function addModel($ordersModel): int
    {
        $this->validateModel($ordersModel);
        $result = OrdersDAO::getInstance()->insert($ordersModel);
        if ($result) {
            $ordersModel->setId($result);
            $this->ordersList[] = $ordersModel;
            $this->refreshData();
            return $result;
        }
        return 0;
    }

    public function getLastInsertId()
    {
        return OrdersDAO::getInstance()->getLastOrderId();
    }

    public function updateModel($ordersModel): int
    {
        $this->validateModel($ordersModel);
        $result = OrdersDAO::getInstance()->update($ordersModel);
        if ($result) {
            $index = array_search($ordersModel, $this->ordersList);
            $this->ordersList[$index] = $ordersModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($ordersModel): int
    {
        $result = OrdersDAO::getInstance()->delete($ordersModel);
        if ($result) {
            $index = array_search($ordersModel, $this->ordersList);
            unset($this->ordersList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function validateModel($ordersModel)
    {
        if (
            empty($ordersModel->getUserId()) ||
            empty($ordersModel->getOrderDate()) ||
            empty($ordersModel->getTotalAmount()) ||
            empty($ordersModel->getCustomerName()) ||
            empty($ordersModel->getCustomerPhone()) ||
            empty($ordersModel->getCustomerAddress()) ||
            empty($ordersModel->getStatus()) ||
            $ordersModel->getUserId() == null ||
            $ordersModel->getOrderDate() == null ||
            $ordersModel->getTotalAmount() == null ||
            $ordersModel->getCustomerName() == null ||
            $ordersModel->getCustomerPhone() == null ||
            $ordersModel->getCustomerAddress() == null ||
            $ordersModel->getStatus() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }
    public function getOrdersByUserId($userId)
    {
        $ordersByUserId = array();
        foreach ($this->ordersList as $orders) {
            if ($orders->getUserId() == $userId) {
                array_push($ordersByUserId, $orders);
            }
        }
        return $ordersByUserId;
    }
    public function getOrdersByPaymentStatus($paymentStatus)
    {
        $ordersByPaymentStatus = array();
        foreach ($this->ordersList as $orders) {
            if ($orders->getPaymentStatus() == $paymentStatus) {
                array_push($ordersByPaymentStatus, $orders);
            }
        }
    }

    public function searchModel(string $value, array $columns)
    {
        return OrdersDAO::getInstance()->search($value, $columns);
    }

    public function getOrdersByStatus($status)
    {
        $ordersByStatus = array();
        foreach ($this->ordersList as $orders) {
            if ($orders->getStatus() == $status) {
                array_push($ordersByStatus, $orders);
            }
        }
        return $ordersByStatus;
    }

    public function searchBetweenDate($before, $after)
    {
        return array_filter($this->ordersList, function ($orders) use ($before, $after) {
            return $orders->getOrderDate() >= $before && $orders->getOrderDate() <= $after;
        });
    }

    public function searchBeforeDate($before)
    {
        return array_filter($this->ordersList, function ($orders) use ($before) {
            return $orders->getOrderDate() <= $before;
        });
    }

    public function searchAfterDate($after)
    {
        return array_filter($this->ordersList, function ($orders) use ($after) {
            return $orders->getOrderDate() >= $after;
        });
    }

    public function filterByDateRange($ngayTu = null, $ngayDen = null): array
    {
        if (empty($ngayTu) && empty($ngayDen)) {
            return $statisticList = OrdersDAO::getInstance()->getAllThongKe();
        } elseif (!empty($ngayTu) && empty($ngayDen)) {
            return $statisticList = OrdersDAO::getInstance()->getByNgayTu($ngayTu);
        } elseif (empty($ngayTu) && !empty($ngayDen)) {
            return $statisticList = OrdersDAO::getInstance()->getByNgayDen($ngayDen);
        } elseif (!empty($ngayTu) && !empty($ngayDen)) {
            return $statisticList = OrdersDAO::getInstance()->getByNgayTuNgayDen($ngayTu, $ngayDen);
        }

        // Add a default return statement
        return array();
    }

}
