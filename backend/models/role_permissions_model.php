<?php

class RolePermissionsModel
{
    private $id;
    private $permissionId;
    private $roleId;

    public function __construct($id, $permissionId, $roleId)
    {
        $this->id = $id;
        $this->permissionId = $permissionId;
        $this->roleId = $roleId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPermissionId()
    {
        return $this->permissionId;
    }

    public function setPermissionId($permissionId)
    {
        $this->permissionId = $permissionId;
    }

    public function getRoleId()
    {
        return $this->roleId;
    }

    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }
}
