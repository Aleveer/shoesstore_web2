<?php
namespace backend\models;
class UserModel
{
    private $id;
    private $username;
    private $password;
    private $email;
    private $name;
    private $phone;
    private $gender;
    private $image;
    private $roleId;
    private $status;
    private $address;
    private $forgotToken;
    private $activeToken;
    private $create_at;
    private $update_at;

    public function __construct($id, $username, $password, $email, $name, $phone, $gender, $image, $roleId, $status, $address, $forgotToken, $activeToken, $create_at, $update_at)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
        $this->gender = $gender;
        $this->image = $image;
        $this->roleId = $roleId;
        $this->status = $status;
        $this->address = $address;
        $this->forgotToken = $forgotToken;
        $this->activeToken = $activeToken;
        $this->create_at = $create_at;
        $this->update_at = $update_at;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getRoleId()
    {
        return $this->roleId;
    }

    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getForgotToken() {
        return $this->forgotToken;
    }

    public function setForgotToken($forgotToken) {
        $this->forgotToken = $forgotToken;
    }

    public function getActiveToken() {
        return $this->activeToken;
    }

    public function setActiveToken($activeToken) {
        $this->activeToken = $activeToken;
    }

    public function getCreateAt() {
        return $this->create_at;
    }

    public function setCreateAt($create_at) {
        $this->create_at = $create_at;
    }

    public function getUpdateAt() {
        return $this->update_at;
    }

    public function setUpdateAt($update_at) {
        $this->update_at = $update_at;
    }
}
