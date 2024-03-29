<?php
class ReviewStatusModel
{
    private $id, $productId, $status;
    public function __construct($id, $productId, $status)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->status = $status;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
