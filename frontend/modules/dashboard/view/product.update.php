<?php
use backend\bus\CategoriesBUS;
use backend\bus\ProductBUS;

$title = 'Product';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}
include (__DIR__ . '/../inc/head.php');

global $id;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = ProductBUS::getInstance()->getModelById($id);
    if ($product === null) {
        // Redirect back or show an error message
        die('Product does not exist');
    }
} else {
    // Redirect back or show an error message
    die('Product id is missing');
}
?>

<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</div>

<body>
    <?php include (__DIR__ . '/../inc/header.php'); ?>
    <div class="row g-3">
        <div class="col-md-7">
            <label for="inputProductName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputEditProductName" value="<?php $name = ProductBUS::getInstance()->getModelById($id)->getName();
            echo $name;
            ?>">
        </div>
        <div class="col-md-5">
            <div class="col-4">
                <label for="inputProductCate" class="form-label">Categories</label>
                <select id="inputEditProductCate" class="form-select">
                    <?php
                    $categories = CategoriesBUS::getInstance()->getAllModels();
                    foreach ($categories as $category) {
                        $selected = ($category->getId() == ProductBUS::getInstance()->getModelById($id)->getCategoryId()) ? 'selected' : '';
                        echo '<option value="' . $category->getId() . '" ' . $selected . ' data-value="' . $category->getId() . '">' . $category->getName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-4">
                <label for="inputProductStatus" class="form-label">Status</label>
                <select id="inputEditProductStatus" class="form-select">
                    <?php
                    $status = ProductBUS::getInstance()->getModelById($id)->getStatus();
                    ?>
                    <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active</option>
                </select>
            </div>
            </select>
        </div>
        <div class="col-4">
            <label for="inputPrice" class="form-label">Price</label>
            <input type="text" class="form-control" id="inputEditPrice" value="<?php $price = ProductBUS::getInstance()->getModelById($id)->getPrice();
            echo $price; ?>">
        </div>
        <div class=" col-md-4">
            <label for="inputGender" class="form-label">Gender</label>
            <select id="inputEditGender" class="form-select">
                <?php
                $gender = ProductBUS::getInstance()->getModelById($id)->getGender();
                ?>
                <option value="0" <?php echo $gender == 0 ? 'selected' : ''; ?>>Male</option>
                <option value="1" <?php echo $gender == 1 ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>
        <div class="col-md-7">
            <label for="inputPhone" class="form-label">Description</label>
            <textarea class="form-control" id="w3Editreview" name="w3review" row="1"
                cols="40"><?php echo ProductBUS::getInstance()->getModelById($id)->getDescription(); ?></textarea>
        </div>

        <div class="col-7">
            <label for="inputImg">Image (.JPG, .JPEG, .PNG)</label>
            <input type="file" class="form-control" name="imgProduct" id="inputEditImg" accept=".jpg, .jpeg, .png">
        </div>

        <div class="col-5 productImg">
            <img id="imgEditPreview" src="<?php echo ProductBUS::getInstance()->getModelById($id)->getImage(); ?>"
                alt="Preview Image" class="form-image" style="width: 350px; height: 350px;">
        </div>

        <form method="POST">
            <div class="text-center">
                <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateEditBtn" name="updateEdtBtnName">Update</button>
            </div>
        </form>
        <?php
        if (isPost()) {
            if (isset($_POST['updateEditBtnName'])) {
                $productUpdate = ProductBUS::getInstance()->getModelById($id);
                $productName = $_POST['productNameEdit'] ?? '';
                $productCategory = $_POST['categoryEdit'] ?? '';
                $productPrice = $_POST['priceEdit'] ?? '';
                $productGender = $_POST['genderEdit'] ?? '';
                $productDescription = $_POST['descriptionEdit'] ?? '';
                $productStatus = $_POST['statusEdit'] ?? '';

                $productUpdate->setCategoryId($productCategory);
                $productUpdate->setGender($productGender);
                $productUpdate->setName($productName);
                $productUpdate->setPrice($productPrice);
                $productUpdate->setDescription($productDescription);
                $productUpdate->setStatus($productStatus);

                $data = $_POST['imageEdit'];
                $productUpdate->setImage($data);
                ProductBUS::getInstance()->updateModel($productUpdate);
                ProductBUS::getInstance()->refreshData();
            }
        }
        ?>
        <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/update_product.js"></script>
    </div>

</body>