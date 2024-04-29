<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\RoleDAO;

class RoleBUS implements BUSInterface
{
    private $roleList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RoleBUS();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->refreshData();
    }
    public function getAllModels(): array
    {
        return $this->roleList;
    }
    public function refreshData(): void
    {
        $this->roleList = RoleDAO::getInstance()->getAll();
    }
    public function getModelById(int $id)
    {
        return RoleDAO::getInstance()->getById($id);
    }
    public function addModel($roleModel): int
    {
        $this->validateModel($roleModel);
        $result = RoleDAO::getInstance()->insert($roleModel);
        if ($result) {
            $this->roleList[] = $roleModel;
            $this->refreshData();
        }
        return $result;
    }
    public function updateModel($roleModel): int
    {
        $this->validateModel($roleModel);
        $result = RoleDAO::getInstance()->update($roleModel);
        if ($result) {
            $index = array_search($roleModel, $this->roleList);
            $this->roleList[$index] = $roleModel;
            $this->refreshData();
        }
        return $result;
    }
    public function deleteModel($roleModel): int
    {
        $result = RoleDAO::getInstance()->delete($roleModel);
        if ($result) {
            $index = array_search($roleModel, $this->roleList);
            unset($this->roleList[$index]);
            $this->refreshData();
        }
        return $result;
    }
    public function searchModel(string $value, array $columns)
    {
        return RoleDAO::getInstance()->search($value, $columns);
    }
    public function validateModel($roleModel): void
    {
        if (
            empty($roleModel->getName()) ||
            $roleModel->getName() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }
}
