<?php
namespace backend\models;

class CouponsModel
{
    private $id, $code, $quantity, $percent, $expired, $description;
    public function __construct($id, $code, $quantity, $percent, $expired, $description)
    {
        $this->id = $id;
        $this->code = $code;
        $this->quantity = $quantity;
        $this->percent = $percent;
        $this->expired = $expired;
        $this->description = $description;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getCode()
    {
        return $this->code;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getPercent()
    {
        return $this->percent;
    }
    public function getExpired()
    {
        return $this->expired;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setCode($code)
    {
        $this->code = $code;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }
    public function setExpired($expired)
    {
        $this->expired = $expired;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }

}
