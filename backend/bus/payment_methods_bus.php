<?php
namespace backend\bus;

use backend\interfaces\BUSInterface;
use backend\dao\PaymentMethodsDAO;
use Exception;

class PaymentMethodsBUS implements BUSInterface
{
    private $paymentMethodsList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PaymentMethodsBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return PaymentMethodsDAO::getInstance()->getAll();
    }

    public function refreshData(): void
    {
        PaymentMethodsDAO::getInstance()->readDatabase();
    }

    public function getModelById($id)
    {
        return PaymentMethodsDAO::getInstance()->getById($id);
    }

    public function addModel($paymentMethodModel): int
    {
        $this->validateModel($paymentMethodModel);
        $result = PaymentMethodsDAO::getInstance()->insert($paymentMethodModel);
        if ($result) {
            $this->paymentMethodsList[] = $paymentMethodModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($paymentMethodModel): int
    {
        $this->validateModel($paymentMethodModel);
        $result = PaymentMethodsDAO::getInstance()->update($paymentMethodModel);
        if ($result) {
            $index = array_search($paymentMethodModel, $this->paymentMethodsList);
            $this->paymentMethodsList[$index] = $paymentMethodModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($paymentMethodModel): int
    {
        $result = PaymentMethodsDAO::getInstance()->delete($paymentMethodModel);
        if ($result) {
            $index = array_search($paymentMethodModel, $this->paymentMethodsList);
            unset($this->paymentMethodsList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return PaymentMethodsDAO::getInstance()->search($value, $columns);
    }

    private function validateModel($paymentMethodModel)
    {
        if ($paymentMethodModel->getName() == null || $paymentMethodModel->getName() == "") {
            throw new Exception("Name cannot be empty");
        }
    }
}
