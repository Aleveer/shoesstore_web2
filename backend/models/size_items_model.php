<?php
class SizeItemsModel
{
    private $id;
    private $productId;
    private $sizeId;
    private $quantity;
    
    public function __construct($id, $productId, $sizeId, $quantity)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->sizeId = $sizeId;
        $this->quantity = $quantity;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
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
}
