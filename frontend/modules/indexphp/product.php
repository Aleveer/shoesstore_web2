<?php
use backend\bus\ProductBUS;
use backend\bus\SizeBUS;
use backend\bus\SizeItemsBUS;
use backend\bus\CategoriesBUS;
use backend\services\session;

$categoriesList = CategoriesBUS::getInstance()->getAllModels();
$size = SizeBUS::getInstance()->getAllModels();
$sizeItems = SizeItemsBUS::getInstance()->getAllModels();
$products = ProductBUS::getInstance()->getActiveProductOnly();

global $searchResult;
global $resultsBasedOnPrice;
global $page;
global $total_pages;
global $productToDisplayPerPage;
?>

<?php
function displayProduct($product)
{
    echo '
        <div class="pitem">
            <div class="imgitem">
                <img src="' . $product->getImage() . '" alt="">
            </div>
            <div class="content">
                <div class="name">' . $product->getName() . '</div>
                <div class="price">' . $product->getPrice() . '<sup>đ</sup></div>
                <button class="see_product">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <a href="?module=indexphp&action=singleproduct&id=' . $product->getId() . '">SEE MORE</a>
                </button>
            </div>
        </div>
        ';
}
?>
<div id="header" style="  background-color: rgb(18, 15, 40); ">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/product.css" />
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/product_slider.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php layouts("header") ?>
</div>

<div id="content">
    <div class="carousel">
        <div class="list">
            <div class="item active">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680098.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Nơi mà niềm đam mê thể thao và phong cách thời trang hiện đại hòa quyện.
                        Không chỉ là một điểm bán giày, mà còn là nguồn cảm hứng cho những người yêu thể thao.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680102.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Cam kết mang đến cho bạn những đôi giày chất lượng cao, từ các thương hiệu nổi tiếng như
                        Nike,
                        Adidas, và Puma, đến những thương hiệu mới nổi đầy sáng tạo.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Photo.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Mỗi đôi giày tại cửa hàng đều được chọn lọc kỹ càng, đảm bảo sự thoải mái, độ bền và phong
                        cách.
                        Với đa dạng mẫu mã và màu sắc, bạn chắc chắn sẽ tìm thấy đôi giày phù hợp với mình.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Picture.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Đội ngũ nhân viên thân thiện và chuyên nghiệp của chúng tôi luôn sẵn sàng tư vấn để bạn có
                        thể
                        chọn được đôi giày tốt nhất.
                        Chính sách đổi trả linh hoạt và dịch vụ sau bán hàng chu đáo sẽ làm bạn hài lòng.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="thumbnail">
            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680102.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">Tôn Chỉ</div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Photo.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">Đặc Điểm</div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Picture.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">Dịch Vụ</div>
                </div>
            </div>

            <div class="item active">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680098.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">FILLO Slogan</div>
                </div>
            </div>
        </div>

        <div class="arrows">
            <button id="prev">
                < </button>
                    <button id="next"> > </button>
        </div>
    </div>

    <div class="con_product">
        <form method="POST">
            <div class="psearch">
                <input type="text" name="searchbox" placeholder="Nhập sản phẩm bạn muốn tìm kiếm" required>
                <button class="custom-btn btn-14" name="searchBtn" id="searchBtnId" onchange="this.form.submit"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
        <?php
        if (isPost()) {
            $filterAll = filter();
            $searchQuery = isset($filterAll['searchbox']) ? $filterAll['searchbox'] : '';
            $searchResult = array();
            if ($searchQuery !== '' && $searchQuery !== null) {
                $searchResult = ProductBUS::getInstance()->searchModel($searchQuery, ['name']);
            }
        }
        ?>
        <div class="container_filter_pagination">
            <form id="filterForm" method="POST" action="">
                <div class="filter">
                    <fieldset>
                        <legend>Category</legend>
                        <input type="radio" name="category" value="All products">All products
                        <br>
                        <?php
                        foreach ($categoriesList as $category) {
                            echo '<input type="radio" name="categoryValue" value="' . $category->getName() . '">' . $category->getName() . '<br>';
                        }
                        ?>
                    </fieldset>
                    <fieldset>
                        <legend>Gender</legend>
                        <input type="radio" name="gender" value="Male">Male
                        <br>
                        <input type="radio" name="gender" value="Female">Female
                    </fieldset>
                    <fieldset>
                        <legend>Price</legend>
                        <label for="min_price">Minimum Price:</label>
                        <input type="number" name="min_price" min="0" placeholder="100000">
                        <br>
                        <label for="max_price">Maximum Price:</label>
                        <input type="number" name="max_price" min="<?php echo $_POST['min_price'] ?? '' ?>"
                            placeholder="100000">
                    </fieldset>
                </div>
                <button type="submit" name="submitBtn" id="submitBtnId" onchange="this.form.submit">Submit</button>
            </form>
            <div class="container_pagination">
                <!-- <div class="sort">
                    <form method="POST">
                        <label for="alphabet">Alphabet</label>
                        <select name="alphabet">
                            <option value="A_Z">A-Z</option>
                            <option value="Z_A">Z-A</option>
                        </select>
                        <label for="price">Price</label>
                        <select name="price">
                            <option value="high">Low -> High</option>
                            <option value="low">High -> Low</option>
                        </select>
                    </form>

                    <?php
                    // if (isGet()) {
                    //     $filterAll = filter();
                    //     $alphabet = $filterAll['alphabet'] ?? null;
                    //     $price = $filterAll['price'] ?? null;
                    //     $productsToDisplay = $products;
                    
                    //     if ($alphabet == "A-Z") {
                    //         usort($productsToDisplay, function ($a, $b) {
                    //             return $a->getName() <=> $b->getName();
                    //         });
                    //     } elseif ($alphabet == "Z-A") {
                    //         usort($productsToDisplay, function ($a, $b) {
                    //             return $b->getName() <=> $a->getName();
                    //         });
                    //     }
                    
                    //     if ($price == "low") {
                    //         usort($productsToDisplay, function ($a, $b) {
                    //             return $a->getPrice() <=> $b->getPrice();
                    //         });
                    //     } elseif ($price == "high") {
                    //         usort($productsToDisplay, function ($a, $b) {
                    //             return $b->getPrice() <=> $a->getPrice();
                    //         });
                    //     }
                    // }
                    ?>
                </div> -->
                <div class="areaproduct">
                    <?php
                    //By default, display all products:
                    if (!isPost() || (isPost() && !isset($_POST['submitBtn']) && !isset($_POST['searchBtn']))) {
                        $products_per_page = 12;
                        $total_products = count($products);
                        $total_pages = ceil($total_products / $products_per_page);
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($page - 1) * $products_per_page;
                        $productToDisplayPerPage = array_slice($products, $offset, $products_per_page);
                        //Show productToDisplayPerPage:
                        foreach ($productToDisplayPerPage as $product) {
                            displayProduct($product);
                        }
                    }

                    if (isPost()) {
                        $filterAll = filter();
                        //Check if submit button is clicked:
                        if (isset($_POST['submitBtn'])) {
                            //Create an array that stores all filter into 1 list:
                            $searchResultFromRadioButton = array();

                            $selectedCategory = $_POST['categoryValue'] ?? null;

                            //Check for category first:
                            //Check for gender from category above, if user don't choose category, check gender only:
                            //Then check for minimal price and maximal price:
                            //When checking is finished above, user clicks on submit button:
                            if (isset($selectedCategory) && $selectedCategory != null && $selectedCategory != "All products") {
                                $categoryModel = CategoriesBUS::getInstance()->getModelByName($selectedCategory);
                                $searchResultFromRadioButton = array_filter($products, function ($product) use ($categoryModel) {
                                    if ($categoryModel != null && $product->getCategoryId() != $categoryModel->getId()) {
                                        return false;
                                    }
                                    return true;
                                });
                            }

                            if ($selectedCategory == "All products" || $selectedCategory == null) {
                                $searchResultFromRadioButton = $products;
                            }

                            $searchResultFromRadioButton = array_values($searchResultFromRadioButton);

                            //Check for gender:
                            $selectedGender = $_POST['gender'] ?? null;
                            if ($selectedGender == "Male") {
                                $selectedGender = 0;
                            } elseif ($selectedGender == "Female") {
                                $selectedGender = 1;
                            }

                            if ($selectedGender !== null) {
                                $searchResultFromRadioButton = array_filter($searchResultFromRadioButton, function ($product) use ($selectedGender) {
                                    return $product->getGender() == $selectedGender;
                                });
                            }

                            $searchResultFromRadioButton = array_values($searchResultFromRadioButton);

                            $minimalPrice = isset($_POST['min_price']) && $_POST['min_price'] !== '' ? $_POST['min_price'] : false;
                            $maximalPrice = isset($_POST['max_price']) && $_POST['max_price'] !== '' ? $_POST['max_price'] : false;

                            $errors = array();
                            if (isset($minimalPrice) && isset($maximalPrice) && $minimalPrice != null && $maximalPrice != null) {
                                if ($minimalPrice > $maximalPrice) {
                                    $errors[] = "Giá tối thiểu không được lớn hơn giá tối đa";
                                } elseif ($minimalPrice < 0) {
                                    $errors[] = "Giá tối thiểu không được nhỏ hơn 0";
                                } elseif ($maximalPrice < 0) {
                                    $errors[] = "Giá tối đa không được nhỏ hơn 0";
                                } elseif ($maximalPrice < $minimalPrice) {
                                    $errors[] = "Giá tối đa không được nhỏ hơn giá tối thiểu";
                                } elseif ($maximalPrice < 0 && $minimalPrice < 0) {
                                    $errors[] = "Giá tối đa và giá tối thiểu không được nhỏ hơn 0";
                                } elseif ($maximalPrice == $minimalPrice && $maximalPrice < 0 && $minimalPrice < 0) {
                                    $errors[] = "Giá tối đa và giá tối thiểu không được nhỏ hơn 0 và bằng nhau";
                                } elseif ($maximalPrice == $minimalPrice && $maximalPrice < 0) {
                                    $errors[] = "Giá tối đa và giá tối thiểu không được nhỏ hơn 0 và bằng nhau";
                                } elseif ($maximalPrice == $minimalPrice && $minimalPrice < 0) {
                                    $errors[] = "Giá tối đa và giá tối thiểu không được nhỏ hơn 0 và bằng nhau";
                                }
                            }

                            if (!empty($errors)) {
                                foreach ($errors as $error) {
                                    echo '<script>alert("' . $error . '");</script>';
                                    echo '<script>window.location.href = "http://localhost/frontend/index.php?module=indexphp&action=product";</script>';
                                }
                            }

                            $searchResultFromRadioButton = array_filter($searchResultFromRadioButton, function ($product) use ($minimalPrice, $maximalPrice) {
                                $price = $product->getPrice();
                                if ($minimalPrice !== false && $maximalPrice !== false) {
                                    return $price >= $minimalPrice && $price <= $maximalPrice;
                                } elseif ($minimalPrice !== false && $maximalPrice === false) {
                                    return $price >= $minimalPrice;
                                } elseif ($maximalPrice !== false && $minimalPrice === false) {
                                    return $price <= $maximalPrice;
                                }
                                return true;
                            });

                            // If no products are found, display a message
                            if (count($searchResultFromRadioButton) == 0 && isset($submitButton)) {
                                echo '<script>alert("No products found")</script>';
                                echo '<script>window.location.href = "http://localhost/frontend/index.php?module=indexphp&action=product";</script>';
                            }

                            // $products_per_page = 12;
                            // $total_products = count($searchResultFromRadioButton);
                            // $total_pages = ceil($total_products / $products_per_page);
                            // $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                            // $page = min(max($page, 1), $total_pages); // Ensure $page is between 1 and $total_pages
                            // $offset = ($page - 1) * $products_per_page;
                            // $productToDisplayPerPage = array_slice($searchResultFromRadioButton, $offset, $products_per_page);
                            foreach ($searchResultFromRadioButton as $product) {
                                displayProduct($product);
                            }

                        } else {
                            if (isset($searchQuery)) {
                                if (count($searchResult) == 0) {
                                    echo '<div class="no_products">No products found</div>';
                                    echo '<script>alert("No product found")</script>';
                                    echo '<script>window.location.href = "http://localhost/frontend/index.php?module=indexphp&action=product";</script>';
                                }

                                if (count($searchResult) > 0) {
                                    // $products_per_page = 12;
                                    // $total_products = count($searchResult);
                                    // $total_pages = ceil($total_products / $products_per_page);
                                    // $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                                    // $page = min(max($page, 1), $total_pages); // Ensure $page is between 1 and $total_pages
                                    // $offset = ($page - 1) * $products_per_page;
                                    // $productToDisplayPerPage = array_slice($searchResult, $offset, $products_per_page);
                                    foreach ($searchResult as $product) {
                                        displayProduct($product);
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </div>
                <?php
                $selectedCategory = $_POST['categoryValue'] ?? null;
                if ((!isset($_POST['submitBtn']) && !isset($_POST['searchBtn'])) || ($selectedCategory == 'All products' && isset($_POST['submitBtn']))):
                    ?>
                    <div style="margin-bottom: 2rem;" class="page" data-total-pages="<?php echo $total_pages; ?>"
                        data-current-page="<?php echo $page; ?>">
                        <a class="custom-btn btn-7 prev" id="prevPage" name="prevPage"><span>
                                < </span></a>
                        <span class="current-page" id="currentPage" name="currentPage">Page <?php echo $page; ?> of
                            <?php echo $total_pages; ?></span>
                        <a class="custom-btn btn-7 next" id="nextPage" name="nextPage"><span>></span></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src=" <?php echo _WEB_HOST_TEMPLATE ?>/js/product_slider.js"></script>
    <script>
        //Get page:
        let currentPage = document.getElementById("currentPage");
        let prevPage = document.getElementById("prevPage");
        let nextPage = document.getElementById("nextPage");
        let page = document.querySelector(".page");
        let totalPages = page.getAttribute("data-total-pages");
        let currentPageValue = page.getAttribute("data-current-page");

        prevPage.addEventListener("click", function () {
            if (currentPageValue > 1) {
                currentPageValue--;
                window.location.href = "http://localhost/frontend/index.php?module=indexphp&action=product&page=" + currentPageValue;
            }
        });

        nextPage.addEventListener("click", function () {
            if (currentPageValue < totalPages) {
                currentPageValue++;
                window.location.href = "http://localhost/frontend/index.php?module=indexphp&action=product&page=" + currentPageValue;
            }
        });

        currentPage.innerHTML = "Page " + currentPageValue + " of " + totalPages;
    </script>
</div>

<div id="footer">
    <?php layouts("footer") ?>
</div>