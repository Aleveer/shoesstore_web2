<?php
class CouponsModel
{
    private $id, $code, $quantity, $required, $percent, $expired, $description;
    public function __construct($id, $code, $quantity, $required, $percent, $expired, $description)
    {
        $this->id = $id;
        $this->code = $code;
        $this->quantity = $quantity;
        $this->required = $required;
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

    public function getRequired()
    {
        return $this->required;
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

    public function setRequired($required)
    {
        $this->required = $required;
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
