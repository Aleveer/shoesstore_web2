<?php
require_once(__DIR__ . "/../dao/user_dao.php");

use PHPUnit\Framework\TestCase;

class user_dao_test extends TestCase
{
    private $userDAO;

    protected function setUp(): void
    {
        $this->userDAO = UserDAO::getInstance();
    }

    public function testReadDatabase()
    {
        $userList = $this->userDAO->readDatabase();
        $this->assertIsArray($userList);
        $this->assertNotEmpty($userList);
        // Add more assertions to validate the returned user list
    }

    public function testGetAll()
    {
        $userList = $this->userDAO->getAll();
        $this->assertIsArray($userList);
        $this->assertNotEmpty($userList);
        //return number of elements in userList
        $this->assertEquals(2, count($userList));
        // Add more assertions to validate the returned user list
    }

    public function testGetById()
    {
        $id = 3;
        $user = $this->userDAO->getById($id);
        $this->assertInstanceOf(UserModel::class, $user);
        $this->assertIsObject($user);
        $this->assertEquals(3, $user->getId());
        // $this->assertEquals("john doe", $user->getName());
        // $this->assertEquals("aiuwghduya@gmail.com", $user->getEmail());
        // Add more assertions to validate the returned user object
    }

    public function testInsert()
    {
        //generate fake data:
        //INSERT INTO users (username, password, email, name, phone, gender, image, role_id, address, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $user = new UserModel(1, "john", "123", "johndoe@gmail.com", "john doe", "1234567890", "0", null, null, "active", "test address");
        $userId = $this->userDAO->insert($user);
        $this->assertIsInt($userId);
        $this->assertGreaterThan(0, $userId);
        // Add more assertions to validate the inserted user
    }

    public function testUpdate()
    {
        $user = new UserModel(1, "john", "123", "johndoe@gmail.com", "john doe", "1234567890", "0", null, null, "active", "test address");
        $affectedRows = $this->userDAO->update($user);
        $this->assertIsInt($affectedRows);
        $this->assertGreaterThan(0, $affectedRows);
        // Add more assertions to validate the updated user
    }

    public function testDelete()
    {
        $userId = 1; // Provide an existing user ID for testing
        $affectedRows = $this->userDAO->delete($userId);
        $this->assertIsInt($affectedRows);
        //test if affected rows is greater than 0
        $this->assertGreaterThan(0, $affectedRows);
        // Add more assertions to validate the deletion
    }

    public function testSearch()
    {
        $condition = "john doe"; // Provide a search condition for testing
        $userList = $this->userDAO->search($condition, ['name']);
        $this->assertIsArray($userList);
        // Add more assertions to validate the returned user list
        //Check result of search:
        $this->assertEquals(1, count($userList));
    }
}
