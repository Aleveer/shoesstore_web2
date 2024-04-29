<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\RolePermissionDAO;

class RolePermissionBUS implements BUSInterface
{
    private $rolePermissionList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RolePermissionBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->rolePermissionList = RolePermissionDAO::getInstance()->getAll();
    }

    public function getAllModels(): array
    {
        return $this->rolePermissionList;
    }

    public function refreshData(): void
    {
        $this->rolePermissionList = RolePermissionDAO::getInstance()->getAll();
    }

    public function getModelById(int $id)
    {
        return RolePermissionDAO::getInstance()->getById($id);
    }

    public function addModel($rolePermissionModel): int
    {
        $this->validateModel($rolePermissionModel);
        $result = RolePermissionDAO::getInstance()->insert($rolePermissionModel);
        if ($result) {
            $this->rolePermissionList[] = $rolePermissionModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($rolePermissionModel): int
    {
        $this->validateModel($rolePermissionModel);
        $result = RolePermissionDAO::getInstance()->update($rolePermissionModel);
        if ($result) {
            $index = array_search($rolePermissionModel, $this->rolePermissionList);
            $this->rolePermissionList[$index] = $rolePermissionModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($rolePermissionModel): int
    {
        $result = RolePermissionDAO::getInstance()->delete($rolePermissionModel);
        if ($result) {
            $index = array_search($rolePermissionModel, $this->rolePermissionList);
            unset($this->rolePermissionList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return RolePermissionDAO::getInstance()->search($value, $columns);
    }

    public function validateModel($rolePermissionModel)
    {
        if (
            empty($rolePermissionModel->getRoleId()) ||
            empty($rolePermissionModel->getPermissionId()) ||
            $rolePermissionModel->getRoleId() == null ||
            $rolePermissionModel->getPermissionId() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }
}
