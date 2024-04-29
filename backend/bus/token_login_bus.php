<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\TokenLoginDAO;

class TokenLoginBUS implements BUSInterface
{
    private $tokenLoginList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new TokenLoginBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->tokenLoginList;
    }

    public function refreshData(): void
    {
        $this->tokenLoginList = TokenLoginDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return TokenLoginDAO::getInstance()->getById($id);
    }

    public function getModelByToken(string $token)
    {
        $this->tokenLoginList = TokenLoginDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->tokenLoginList); $i++) {
            if ($this->tokenLoginList[$i]->getToken() === $token) {
                return $this->tokenLoginList[$i];
            }
        }
    }

    public function validateModel($tokenLoginModel)
    {
        if (
            empty($tokenLoginModel->getUserId()) ||
            empty($tokenLoginModel->getToken()) ||
            empty($tokenLoginModel->getCreateAt()) ||
            $tokenLoginModel->getUserId() == null ||
            $tokenLoginModel->getToken() == null ||
            $tokenLoginModel->getCreateAt() == null
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }
    }

    public function addModel($tokenLoginModel)
    {
        $this->validateModel($tokenLoginModel);
        $result = TokenLoginDAO::getInstance()->insert($tokenLoginModel);
        if ($result) {
            $this->tokenLoginList[] = $tokenLoginModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($tokenLoginModel)
    {
        $this->validateModel($tokenLoginModel);
        $result = TokenLoginDAO::getInstance()->update($tokenLoginModel);
        if ($result) {
            $index = array_search($tokenLoginModel, $this->tokenLoginList);
            $this->tokenLoginList[$index] = $tokenLoginModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($tokenLoginModel)
    {
        $result = TokenLoginDAO::getInstance()->delete($tokenLoginModel->getId());
        if ($result) {
            $index = array_search($tokenLoginModel, $this->tokenLoginList);
            unset($this->tokenLoginList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function getTokenLoginByUserId($userId)
    {
        $tokenLoginByUserId = null;
        foreach ($this->tokenLoginList as $tokenLogin) {
            if ($tokenLogin->getUserId() == $userId) {
                $tokenLoginByUserId = $tokenLogin;
            }
        }
        return $tokenLoginByUserId;
    }

    public function searchModel(string $value, array $columns)
    {
        return TokenLoginDAO::getInstance()->search($value, $columns);
    }
}
