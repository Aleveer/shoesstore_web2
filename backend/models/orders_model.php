<?php
class OrdersModel
{
    private $id, $customerId, $userId, $orderDate, $totalAmount;
    public function __construct($id, $customerId, $userId, $orderDate, $totalAmount)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getOrderDate()
    {
        return $this->orderDate;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }
}
