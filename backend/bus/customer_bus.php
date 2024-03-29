<?php
require_once(__DIR__ . "/../models/customer_model.php");
require_once(__DIR__ . "/../dao/customer_dao.php");
require_once(__DIR__ . "/../interfaces/bus_interface.php");
require_once(__DIR__ . '/../services/validation.php');
class CustomerBUS implements BUSInterface
{
    private $customerList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CustomerBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->customerList;
    }

    public function refreshData(): void
    {
        $this->customerList = CustomerDAO::getInstance()->getAll();
    }

    public function getModelById(int $id)
    {
        return CustomerDAO::getInstance()->getById($id);
    }

    public function getModelByEmail(string $email)
    {
        foreach ($this->customerList as $customer) {
            if ($customer->getEmail() == $email) {
                return $customer;
            }
        }
        return null;
    }

    public function addModel($customerModel): int
    {
        $this->validateModel($customerModel);
        $result = CustomerDAO::getInstance()->insert($customerModel);
        if ($result) {
            $this->customerList[] = $customerModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($customerModel): int
    {
        $this->validateModel($customerModel);
        $result = CustomerDAO::getInstance()->update($customerModel);
        if ($result) {
            $index = array_search($customerModel, $this->customerList);
            $this->customerList[$index] = $customerModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($id): int
    {
        $result = CustomerDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, $this->customerList);
            unset($this->customerList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function validateModel($customerModel): bool
    {
        if (empty($customerModel->getName()) || empty($customerModel->getEmail())) {
            return false;
        }
        if (validation::isValidEmail($customerModel->getEmail()) == false) {
            return false;
        }
        return true;
    }

    public function searchModel(string $value, array $columns)
    {
        return CustomerDAO::getInstance()->search($value, $columns);
    }
}
