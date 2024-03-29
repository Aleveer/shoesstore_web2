<?php
require_once(__DIR__ . "/../bus/user_bus.php");

use PHPUnit\Framework\TestCase;

class user_busTest extends TestCase
{
    private $userBUS;

    protected function setUp(): void
    {
        $this->userBUS = UserBUS::getInstance();
    }

    public function testGetAllModels()
    {
        $userList = $this->userBUS->getAllModels();
        // Check how many elements from userList:
        $this->assertCount(1, $userList, "User list should have 1 elements");
        $this->assertIsArray($userList, "getAllModels should return an array");
        $this->assertNotEmpty($userList, "User list should not be empty");
    }

    public function testGetModelById()
    {
        $user = $this->userBUS->getModelById(1);
        //return $user data:
        var_dump($user);
        $user->getId();
        $this->assertEquals(1, $user->getId(), "User id should be 1");

        $this->userBUS->getModelById(1);
    }

    // Add more test methods for other functions in the UserBUS class

    public function testRunAllTests()
    {
        $this->testGetAllModels();
        $this->testGetModelById();
        // Call other test methods here
    }
}
