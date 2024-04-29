<?php
namespace backend\models;
class ImportItemsModel
{
    private $id, $importId, $productId, $sizeId, $quantity, $price;

    public function __construct($id, $importId, $productId, $sizeId, $quantity, $price)
    {
        $this->id = $id;
        $this->importId = $importId;
        $this->productId = $productId;
        $this->sizeId = $sizeId;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getImportId()
    {
        return $this->importId;
    }

    public function setImportId($importId)
    {
        $this->importId = $importId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getSizeId()
    {
        return $this->sizeId;
    }

    public function setSizeId($sizeId)
    {
        $this->sizeId = $sizeId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }
}
