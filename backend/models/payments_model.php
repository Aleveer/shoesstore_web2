<?php
class PaymentsModel
{
    private $id, $orderId, $methodId, $paymentDate, $totalPrice;

    public function __construct($id, $orderId, $methodId, $paymentDate, $totalPrice)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->methodId = $methodId;
        $this->paymentDate = $paymentDate;
        $this->totalPrice = $totalPrice;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    public function getMethodId()
    {
        return $this->methodId;
    }

    public function setMethodId($methodId)
    {
        $this->methodId = $methodId;
    }

    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }
}
