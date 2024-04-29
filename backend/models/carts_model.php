<?php
namespace backend\models;
class CartsModel
{
    private $id, $userId, $productId, $quantity, $sizeId;
    public function __construct($id, $userId, $productId, $quantity, $sizeId)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->sizeId = $sizeId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getSizeId()
    {
        return $this->sizeId;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function setSizeId($sizeId)
    {
        $this->sizeId = $sizeId;
    }
}
