<?php
require_once(__DIR__ . "/../bus/product_bus.php");

use PHPUnit\Framework\TestCase;

class ProductBUSTest extends TestCase
{
    private $productBUS;

    protected function setUp(): void
    {
        $this->productBUS = ProductBUS::getInstance();
    }

    public function testGetAllModels()
    {
        $productList = $this->productBUS->getAllModels();
        $this->assertIsArray($productList, "getAllModels should return an array");
        $this->assertNotEmpty($productList, "Product list should not be empty");
    }

    public function testGetModelById()
    {
        $product = $this->productBUS->getModelById(1);
        $this->assertInstanceOf(ProductModel::class, $product, "getModelById should return an instance of ProductModel");

        $product = $this->productBUS->getModelById(100);
        $this->assertNull($product, "getModelById should return null for non-existent product id");
    }

    public function testAddModel()
    {
        $productModel = new ProductModel();
        $productModel->setName("Test Product");
        $productModel->setCategoryId(1);
        $productModel->setPrice(10.99);
        $productModel->setDescription("Test description");
        $productModel->setImage("test.jpg");
        $productModel->setGender("Male");

        $result = $this->productBUS->addModel($productModel);
        $this->assertTrue($result, "addModel should return true for successful insertion");

        // Check if the product is added to the list
        $productList = $this->productBUS->getAllModels();
        $this->assertCount(1, $productList, "Product list should have 1 element after adding a product");
        $this->assertEquals($productModel, $productList[0], "Added product should match the retrieved product");

        // Test invalid product model
        $invalidProductModel = new ProductModel();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Please fill in all fields");
        $this->productBUS->addModel($invalidProductModel);
    }

    public function testUpdateModel()
    {
        $productModel = new ProductModel();
        $productModel->setId(1);
        $productModel->setName("Updated Product");
        $productModel->setCategoryId(2);
        $productModel->setPrice(19.99);
        $productModel->setDescription("Updated description");
        $productModel->setImage("updated.jpg");
        $productModel->setGender("Female");

        $result = $this->productBUS->updateModel($productModel);
        $this->assertTrue($result, "updateModel should return true for successful update");

        // Check if the product is updated in the list
        $updatedProduct = $this->productBUS->getModelById(1);
        $this->assertEquals($productModel, $updatedProduct, "Updated product should match the retrieved product");

        // Test invalid product model
        $invalidProductModel = new ProductModel();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Please fill in all fields");
        $this->productBUS->updateModel($invalidProductModel);
    }

    public function testDeleteModel()
    {
        $result = $this->productBUS->deleteModel(1);
        $this->assertTrue($result, "deleteModel should return true for successful deletion");

        // Check if the product is removed from the list
        $productList = $this->productBUS->getAllModels();
        $this->assertCount(0, $productList, "Product list should be empty after deleting the product");

        // Test deleting non-existent product
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid product id");
        $this->productBUS->deleteModel(100);
    }

    public function testSearchModel()
    {
        $condition = "Test";
        $columnNames = ["name", "description"];
        $result = $this->productBUS->searchModel($condition, $columnNames);
        $this->assertIsArray($result, "searchModel should return an array");
        $this->assertNotEmpty($result, "Search result should not be empty");

        // Test empty condition
        $emptyCondition = "";
        $result = $this->productBUS->searchModel($emptyCondition, $columnNames);
        $this->assertIsArray($result, "searchModel should return an array");
        $this->assertEmpty($result, "Search result should be empty for empty condition");
    }

    public function testSearchBetweenPrice()
    {
        $min = 10;
        $max = 20;
        $result = $this->productBUS->searchBetweenPrice($min, $max);
        $this->assertIsArray($result, "searchBetweenPrice should return an array");
        $this->assertNotEmpty($result, "Search result should not be empty");

        // Test invalid price range
        $invalidMin = 30;
        $invalidMax = 20;
        $result = $this->productBUS->searchBetweenPrice($invalidMin, $invalidMax);
        $this->assertIsArray($result, "searchBetweenPrice should return an array");
        $this->assertEmpty($result, "Search result should be empty for invalid price range");
    }

    public function testGetRandomRecommendProducts()
    {
        $result = $this->productBUS->getRandomRecommendProducts();
        $this->assertIsArray($result, "getRandomRecommendProducts should return an array");
        $this->assertCount(3, $result, "Recommend product list should have 3 elements");
    }
}
