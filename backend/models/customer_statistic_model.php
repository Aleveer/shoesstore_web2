<?php
namespace backend\models;

class CustomerStatisticModel
{
    private $userId;
    private $customerName;
    private $totalPurchaseAmount;

    public function __construct($userId, $customerName, $totalPurchaseAmount)
    {
        $this->userId = $userId;
        $this->customerName = $customerName;
        $this->totalPurchaseAmount = $totalPurchaseAmount;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setId($userId)
    {
        $this->userId = $userId;
    }

    public function getCustomerName()
    {
        return $this->customerName;
    }

    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    public function getTotalPurchaseAmount()
    {
        return $this->totalPurchaseAmount;
    }

    public function setTotalPurchaseAmount($totalPurchaseAmount)
    {
        $this->totalPurchaseAmount = $totalPurchaseAmount;
    }

    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'customerName' => $this->customerName,
            'totalPurchaseAmount' => $this->totalPurchaseAmount,
        ];
    }
}
