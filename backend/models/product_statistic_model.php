<?php
namespace backend\models;

class ProductStatisticModel
{
    private $productId;
    private $productName;
    private $totalQuantitySold;
    private $totalAmount;

    public function __construct($productId, $productName, $totalQuantitySold, $totalAmount)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->totalQuantitySold = $totalQuantitySold;
        $this->totalAmount = $totalAmount;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    public function getTotalQuantitySold()
    {
        return $this->totalQuantitySold;
    }

    public function setTotalQuantitySold($totalQuantitySold)
    {
        $this->totalQuantitySold = $totalQuantitySold;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    public function toArray()
    {
        return [
            'productId' => $this->productId,
            'productName' => $this->productName,
            'totalQuantitySold' => $this->totalQuantitySold,
            'totalAmount' => $this->totalAmount
        ];
    }
}
