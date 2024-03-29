<?php
class ImportModel
{
    private $id, $userId, $totalPrice, $importDate;

    public function __construct($id, $userId, $totalPrice, $importDate)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->totalPrice = $totalPrice;
        $this->importDate = $importDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function getImportDate()
    {
        return $this->importDate;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    public function setImportDate($importDate)
    {
        $this->importDate = $importDate;
    }
}
