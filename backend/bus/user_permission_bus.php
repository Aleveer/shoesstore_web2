<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use backend\dao\UserPermissionDAO;

class UserPermissionBUS implements BUSInterface
{
    private $userPermissionList = array();

    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new UserPermissionBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->userPermissionList = UserPermissionDAO::getInstance()->getAll();
    }

    public function getAllModels(): array
    {
        return $this->userPermissionList;
    }

    public function refreshData(): void
    {
        $this->userPermissionList = UserPermissionDAO::getInstance()->getAll();
    }

    public function getModelById(int $id)
    {
        return UserPermissionDAO::getInstance()->getById($id);
    }

    public function addModel($userPermissionModel): int
    {
        $this->validateModel($userPermissionModel);
        $result = UserPermissionDAO::getInstance()->insert($userPermissionModel);
        if ($result) {
            $this->userPermissionList[] = $userPermissionModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($userPermissionModel): int
    {
        $this->validateModel($userPermissionModel);
        $result = UserPermissionDAO::getInstance()->update($userPermissionModel);
        if ($result) {
            $index = array_search($userPermissionModel, $this->userPermissionList);
            $this->userPermissionList[$index] = $userPermissionModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($userPermissionModel): int
    {
        $result = UserPermissionDAO::getInstance()->delete($userPermissionModel);
        if ($result) {
            $index = array_search($userPermissionModel, $this->userPermissionList);
            unset($this->userPermissionList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return UserPermissionDAO::getInstance()->search($value, $columns);
    }

    public function validateModel($userPermissionModel): bool
    {
        //permissionId, userId, status: 1: active, 0: inactive, status can be null, default is active.
        if (
            $userPermissionModel->permissionId == null ||
            $userPermissionModel->userId == null
        ) {
            return false;
        }
        return true;
    }
}
