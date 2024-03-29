<?php
require_once(__DIR__ . "/../interfaces/bus_interface.php");
require_once(__DIR__ . "/../dao/user_dao.php");
require_once(__DIR__ . "/../models/user_model.php");
require_once(__DIR__ . "/../../../ShoesStore/backend/services/validation.php");
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
        for ($i = 0; $i < count($this->userList); $i++) {
            if ($this->userList[$i]->getEmail() === $email) {
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

    public function validateModel(UserModel $userModel)
    {
        $validation = Validation::getInstance();
        $errors = [];

        // Check for required fields:
        if ($userModel->getUsername() == null || trim($userModel->getUsername()) == "") {
            $errors[] = "Username is required";
        }

        if ($userModel->getPassword() == null || trim($userModel->getPassword()) == "") {
            $errors[] = "Password is required";
        }

        if ($userModel->getEmail() == null || trim($userModel->getEmail()) == "") {
            $errors[] = "Email is required";
        }

        if ($userModel->getName() == null || trim($userModel->getName()) ==  "") {
            $errors[] = "Name is required";
        }

        // Check for possibly taken properties in database:
        if ($this->isUsernameTaken($userModel->getUsername())) {
            $errors[] = "Username is taken";
        }

        if ($this->isEmailTaken($userModel->getEmail())) {
            $errors[] = "Email is taken";
        }

        if ($this->isPhoneTaken($userModel->getPhone())) {
            $errors[] = "Phone number is taken";
        }

        // Validate username and password
        if (!$validation->isValidUsername($userModel->getUsername())) {
            $errors[] = "Invalid username";
        }

        if (!$validation->isValidPassword($userModel->getPassword())) {
            $errors[] = "Invalid password";
        }

        if (strlen($userModel->getPassword()) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }

        // Validate email and name
        if (!$validation->isValidEmail($userModel->getEmail())) {
            $errors[] = "Invalid email";
        }

        if (!$validation->isValidName($userModel->getName())) {
            $errors[] = "Invalid name";
        }

        if (strlen($userModel->getName()) < 6) {
            $errors[] = "Name must be at least 6 characters";
        }

        // Validate role
        $roleId = $userModel->getRoleId();
        switch ($roleId) {
            case 1:
            case 2:
            case 3: // Add missing case for role 3
                break;
            case null:
                // Default is customer
                $userModel->setRoleId(0);
                break;
            default:
                $errors[] = "Invalid role";
                break;
        }

        // Validate phone number and address
        if ($userModel->getPhone() !== null && !$validation->isValidPhoneNumber($userModel->getPhone())) {
            $errors[] = "Invalid phone number";
        }

        if ($userModel->getAddress() !== null && !$validation->isValidAddress($userModel->getAddress())) {
            $errors[] = "Invalid address";
        }

        if ($userModel->getImage() === null) {
            // TODO: set default image, need a proper implementation.
        }

        if (count($errors) > 0) {
            //Output the error on and return error number count:
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
        return count($errors);
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

    public function imageUploadHandle($userId, $imageFile)
    {
        //TODO: May need a fix on image store location
        $target_dir = __DIR__ . "/../uploads/";
        $target_file = $target_dir . basename($imageFile["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($imageFile["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($imageFile["size"] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
                $user = $this->getModelById($userId);
                $user->setImage($target_file);
                $this->updateModel($user);
                return true;
            } else {
                return false;
            }
        }
    }
}
