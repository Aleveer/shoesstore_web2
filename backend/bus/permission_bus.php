<?php
require_once(__DIR__ . "/../../backend/dao/permission_dao.php");
require_once(__DIR__ . "/../../backend/interfaces/bus_interface.php");
require_once(__DIR__ . "/../../backend/models/permission_model.php");
class PermissionBUS implements BUSInterface
{
    private $permissionList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PermissionBUS();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->refreshData();
    }
    public function getAllModels(): array
    {
        return $this->permissionList;
    }
    public function refreshData(): void
    {
        $this->permissionList = PermissionDAO::getInstance()->getAll();
    }
    public function getModelById(int $id)
    {
        return PermissionDAO::getInstance()->getById($id);
    }
    public function getModelByName(string $name)
    {
        foreach ($this->permissionList as $permission) {
            if ($permission->getName() == $name) {
                return $permission;
            }
        }
        return null;
    }
    public function addModel($permissionModel): int
    {
        $this->validateModel($permissionModel);
        $result = PermissionDAO::getInstance()->insert($permissionModel);
        if ($result) {
            $this->permissionList[] = $permissionModel;
            $this->refreshData();
        }
        return $result;
    }
    public function updateModel($permissionModel): int
    {
        $this->validateModel($permissionModel);
        $result = PermissionDAO::getInstance()->update($permissionModel);
        if ($result) {
            $index = array_search($permissionModel, $this->permissionList);
            $this->permissionList[$index] = $permissionModel;
            $this->refreshData();
        }
        return $result;
    }
    public function deleteModel($permissionModel): int
    {
        $result = PermissionDAO::getInstance()->delete($permissionModel);
        if ($result) {
            $index = array_search($permissionModel, $this->permissionList);
            unset($this->permissionList[$index]);
            $this->refreshData();
        }
        return $result;
    }
    public function validateModel($permissionModel): void
    {
        if (!validation::isValidName($permissionModel->getName())) {
            throw new Exception("Invalid name");
        }
    }

    public function getModelByUserId(int $userId)
    {
        foreach ($this->permissionList as $permission) {
            if ($permission->getUserId() == $userId) {
                return $permission;
            }
        }
        return null;
    }

    public function getModelByRoleId(int $roleId)
    {
        foreach ($this->permissionList as $permission) {
            if ($permission->getRoleId() == $roleId) {
                return $permission;
            }
        }
        return null;
    }

    public function searchModel(string $value, array $columns)
    {
        return PermissionDAO::getInstance()->search($value, $columns);
    }
}
