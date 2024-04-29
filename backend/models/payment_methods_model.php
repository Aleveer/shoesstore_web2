<?php
namespace backend\models;
class PaymentMethodModel
{
    private $id, $methodName;
    public function __construct($id, $methodName)
    {
        $this->id = $id;
        $this->methodName = $methodName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }
}
