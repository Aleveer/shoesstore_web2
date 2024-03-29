<?php

class UserPermissionModel
{
    private $id;
    private $permissionId;
    private $userId;
    private $status;

    public function __construct($id, $permissionId, $userId, $status)
    {
        $this->id = $id;
        $this->permissionId = $permissionId;
        $this->userId = $userId;
        $this->status = $status;
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

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
