<?php
namespace backend\models;
class TokenLoginModel
{
    private $id, $user_id, $token, $create_at;

    public function __construct($id, $user_id, $token, $create_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->token = $token;
        $this->create_at = $create_at;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($id)
    {
        $this->user_id = $id;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }
}
