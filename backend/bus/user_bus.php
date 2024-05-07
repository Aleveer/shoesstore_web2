<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use backend\dao\UserDAO;
use backend\services\validation;

class UserBUS implements BUSInterface
{
    private $userList = array();
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new UserBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->refreshData();
    }

    public function getAllModels(): array
    {
        return $this->userList;
    }

    public function refreshData(): void
    {
        $this->userList = UserDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return UserDAO::getInstance()->getById($id);
    }

    public function getModelByEmail($email)
    {
        $this->userList = UserDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getEmail() === $email) {
                return $this->userList[$i];
            }
        }
        return null;
    }

    public function getModelByPhone($phone)
    {
        $this->userList = UserDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getPhone() === $phone) {
                return $this->userList[$i];
            }
        }
        return null;
    }

    public function getModelByActiveToken(string $token)
    {
        $this->userList = UserDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getActiveToken() === $token) {
                return $this->userList[$i];
            }
        }
        return null;
    }

    public function getModelByForgotToken(string $token)
    {
        $this->userList = UserDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getForgotToken() === $token) {
                return $this->userList[$i];
            }
        }
        return null;
    }

    public function addModel($userModel): int
    {
        $this->validateModel($userModel);
        $result = UserDAO::getInstance()->insert($userModel);
        if ($result) {
            $this->userList[] = $userModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($userModel): int
    {
        $this->validateModel($userModel);
        $result = UserDAO::getInstance()->update($userModel);
        if ($result) {
            $index = array_search($userModel, $this->userList);
            $this->userList[$index] = $userModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($userModel): int
    {
        $result = UserDAO::getInstance()->delete($userModel);
        if ($result) {
            $index = array_search($userModel, $this->userList);
            unset($this->userList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return UserDAO::getInstance()->search($value, $columns);
    }

    public function validateModel($userModel)
    {
        $validation = Validation::getInstance();
        $errors = [];

        // Check for required fields:
        if ($userModel->getUsername() == null || trim($userModel->getUsername()) == "") {
            $errors['username']['required'] = "Username is required";
        }

        if ($userModel->getPassword() == null || trim($userModel->getPassword()) == "") {
            $errors['password']['required'] = "Password is required";
        }

        if ($userModel->getEmail() == null || trim($userModel->getEmail()) == "") {
            $errors['email']['required'] = "Email is required";
        }

        if ($userModel->getName() == null || trim($userModel->getName()) == "") {
            $errors['fullname']['required'] = "Name is required";
        }

        if ($userModel->getGender() == null || trim($userModel->getGender()) == "") {
            $errors['gender']['required'] = "Gender is required";
        }

        //Validate username and password
        if (!$validation->isValidUsername($userModel->getUsername())) {
            $errors['username']['valid'] = "Invalid username";
        }

        if (strlen($userModel->getPassword()) < 6) {
            $errors['password']['min-length'] = "Password must be at least 8 characters";
        }

        $passwordValidationResult = $validation->isValidPassword($userModel->getPassword());

        if ($passwordValidationResult !== true) {
            $errors['password']['valid'] = $passwordValidationResult;
        }

        if (strlen($userModel->getPassword()) < 6) {
            $errors['password']['min-length'] = "Password must be at least 8 characters";
        }

        // Validate email and name
        if (!$validation->isValidEmail($userModel->getEmail())) {
            $errors['email']['valid'] = "Invalid email";
        }

        if (!$validation->isValidName($userModel->getName())) {
            $errors['fullname']['valid'] = "Invalid name";
        }

        if (strlen($userModel->getName()) < 6) {
            $errors['fullname']['min-length'] = "Name must be at least 6 characters";
        }

        // Validate role
        $roleId = $userModel->getRoleId();
        if ($roleId === null) {
            $errors[] = "Role is required";
        }

        // Validate phone number and address
        if ($userModel->getPhone() !== null && !$validation->isValidPhoneNumber($userModel->getPhone())) {
            $errors['phone']['valid'] = "Invalid phone number";
        }

        if ($userModel->getAddress() !== null && !$validation->isValidAddress($userModel->getAddress())) {
            $errors['address']['valid'] = "Invalid address";
        }

        return $errors;
    }

    public function validateResetPassword($password, $password_confirm)
    {
        $validation = Validation::getInstance();
        $errors = [];

        if ($password == null || trim($password) == "") {
            $errors['password']['required'] = "Password is required";
        }

        if (!$validation->isValidPassword($password)) {
            $errors['password']['valid'] = "Invalid password";
        }

        if (strlen($password) < 8) {
            $errors['password']['min-length'] = "Password must be at least 8 characters";
        }

        if ($password != $password_confirm) {
            $errors['password_confirm']['match'] = "Passwords do not match";
        }
        return $errors;
    }

    public function isUsernameTaken($username)
    {
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getUsername() == $username) {
                return true;
            }
        }
    }

    public function isEmailTaken($email, $userIdToSkip)
    {
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getEmail() == $email && $this->userList[$i]->getId() != $userIdToSkip) {
                return true;
            }
        }
    }

    public function isPhoneTaken($phone, $userIdToSkip)
    {
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getPhone() == $phone && $this->userList[$i]->getId() != $userIdToSkip) {
                return true;
            }
        }
    }
}