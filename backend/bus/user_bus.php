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

    public function getModelById(int $id)
    {
        return UserDAO::getInstance()->getById($id);
    }

    public function getModelByEmail(string $email)
    {
        $this->userList = UserDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getEmail() === $email) {
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

        // Check for possibly taken properties in database:
        if ($this->isUsernameTaken($userModel->getUsername())) {
            $errors['username']['taken'] = "Username is taken";
        }

        if ($this->isEmailTaken($userModel->getEmail())) {
            $errors['email']['taken'] = "Email is taken";
        }

        if ($this->isPhoneTaken($userModel->getPhone())) {
            $errors['phone']['taken'] = "Phone number is taken";
        }

        // Validate username and password
        // if (!$validation->isValidUsername($userModel->getUsername())) {
        //     $errors['username']['valid'] = "Invalid username";
        // }

        // if (!$validation->isValidPassword($userModel->getPassword())) {
        //     $errors['password']['valid'] = "Invalid password";
        // }

        if (strlen($userModel->getPassword()) < 8) {
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
        } else {
            $errors[] = "Invalid role";
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
        $usernames = array_column($this->userList, 'username');
        return in_array($username, $usernames);
    }

    public function isEmailTaken($email)
    {
        $emails = array_column($this->userList, 'email');
        return in_array($email, $emails);
    }

    public function isPhoneTaken($phone)
    {
        $phones = array_column($this->userList, 'phone');
        return in_array($phone, $phones);
    }
}