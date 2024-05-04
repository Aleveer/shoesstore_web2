<?php
namespace backend\models;

class OrdersModel
{
    private $id, $userId, $orderDate, $totalAmount, $customerName, $customerPhone, $customerAddress, $status;
    //Generate everything:
    public function __construct($id, $userId, $orderDate, $totalAmount, $customerName, $customerPhone, $customerAddress, $status)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->customerName = $customerName;
        $this->customerPhone = $customerPhone;
        $this->customerAddress = $customerAddress;
        $this->status = $status;
    }
    //Generate getters:
    public function getId()
    {
        return $this->id;
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
    public function getCustomerName()
    {
        return $this->customerName;
    }
    public function getCustomerPhone()
    {
        return $this->customerPhone;
    }
    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }

    public function getStatus()
    {
        return $this->status;
    }
    //Generate setters:
    public function setId($id)
    {
        $this->id = $id;
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
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }
    public function setCustomerPhone($customerPhone)
    {
        $this->customerPhone = $customerPhone;
    }
    public function setCustomerAddress($customerAddress)
    {
        $this->customerAddress = $customerAddress;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'orderDate' => $this->orderDate,
            'totalAmount' => $this->totalAmount,
            'customerName' => $this->customerName,
            'customerPhone' => $this->customerPhone,
            'customerAddress' => $this->customerAddress,
            'status' => $this->status,
        ];
    }
}
