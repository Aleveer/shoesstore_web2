<?php
require_once(__DIR__ . "/../dao/product_dao.php");
require_once(__DIR__ . "/../interfaces/bus_interface.php");
require_once(__DIR__ . "/../models/product_model.php");

class ProductBUS implements BUSInterface
{
    private $productList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ProductBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->productList = ProductDAO::getInstance()->getAll();
    }

    public function getAllModels(): array
    {
        return $this->productList;
    }

    public function refreshData(): void
    {
        $this->productList = ProductDAO::getInstance()->getAll();
    }

    public function getModelById(int $id)
    {
        return ProductDAO::getInstance()->getById($id);
    }

    public function addModel($productModel): int
    {
        if (
            empty($productModel->getName()) ||
            empty($productModel->getCategoryId()) ||
            empty($productModel->getPrice()) ||
            empty($productModel->getDescription()) ||
            empty($productModel->getImage()) ||
            empty($productModel->getGender() ||
                $productModel->getName() == null ||
                $productModel->getCategoryId() == null ||
                $productModel->getPrice() == null ||
                $productModel->getDescription() == null ||
                $productModel->getImage() == null ||
                $productModel->getGender() == null)
        ) {
            throw new InvalidArgumentException("Please fill in all fields");
        }

        if ($productModel->getPrice() < 0) {
            throw new InvalidArgumentException("Price must be greater than 0");
        }

        if ($productModel->getCategoryId() < 0) {
            throw new InvalidArgumentException("Category ID must be greater than 0");
        }

        $newProduct = ProductDAO::getInstance()->insert($productModel);
        if ($newProduct) {
            $this->productList[] = $productModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = ProductDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->productList);
            $this->productList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel(int $id)
    {
        $result = ProductDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->productList, 'id'));
            unset($this->productList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function searchModel(string $condition, $columnNames): array
    {
        return ProductDAO::getInstance()->search($condition, $columnNames);
    }

    public function searchBetweenPrice($min, $max)
    {
        $result = [];
        foreach ($this->productList as $product) {
            if ($product->getPrice() >= $min && $product->getPrice() <= $max) {
                $result[] = $product;
            }
        }
        return $result;
    }

    public function getRandomRecommendProducts()
    {
        $result = [];
        $randomKeys = array_rand($this->productList, 3);
        foreach ($randomKeys as $key) {
            $result[] = $this->productList[$key];
        }
        return $result;
    }

    //Handle multiple images upload at once
    public function imageUploadHandle($productId, array $imageFiles)
    {
        $target_dir = __DIR__ . "/../../public/images/";
        $uploaded_files = [];
        foreach ($imageFiles as $imageFile) {
            $target_file = $target_dir . basename($imageFile["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($imageFile["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
            if (file_exists($target_file)) {
                $uploadOk = 0;
            }
            if ($imageFile["size"] > 500000) {
                $uploadOk = 0;
            }
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {
                return false;
            } else {
                if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
                    $uploaded_files[] = $target_file;
                } else {
                    return false;
                }
            }
        }
        $product = $this->getModelById($productId);
        $product->setImage(implode(",", $uploaded_files));
        $this->updateModel($product);
        return true;
    }
}
