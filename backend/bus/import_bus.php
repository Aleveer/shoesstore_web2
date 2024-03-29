<?php
require_once(__DIR__ . "/../models/import_model.php");
require_once(__DIR__ . "/../dao/import_dao.php");
require_once(__DIR__ . "/../interfaces/bus_interface.php");
class ImportBUS implements BUSInterface
{
    private $importList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ImportBUS();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->refreshData();
    }
    public function getAllModels(): array
    {
        return $this->importList;
    }
    public function refreshData(): void
    {
        $this->importList = ImportDAO::getInstance()->getAll();
    }
    public function getModelById(int $id)
    {
        return ImportDAO::getInstance()->getById($id);
    }

    public function addModel($importModel): int
    {
        $this->validateModel($importModel);
        $result = ImportDAO::getInstance()->insert($importModel);
        if ($result) {
            $this->importList[] = $importModel;
            $this->refreshData();
        }
        return $result;
    }
    public function updateModel($importModel): int
    {
        $this->validateModel($importModel);
        $result = ImportDAO::getInstance()->update($importModel);
        if ($result) {
            $index = array_search($importModel, $this->importList);
            $this->importList[$index] = $importModel;
            $this->refreshData();
        }
        return $result;
    }
    public function deleteModel($importModel): int
    {
        $result = ImportDAO::getInstance()->delete($importModel);
        if ($result) {
            $index = array_search($importModel, $this->importList);
            unset($this->importList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function validateModel($importModel): void
    {
        if ($importModel->getProductId() == null) {
            throw new Exception("Product ID is required");
        }
        if ($importModel->getQuantity() == null) {
            throw new Exception("Quantity is required");
        }
        if ($importModel->getPrice() == null) {
            throw new Exception("Price is required");
        }
    }

    public function searchModel(string $value, array $columns)
    {
        return ImportDAO::getInstance()->search($value, $columns);
    }
}
