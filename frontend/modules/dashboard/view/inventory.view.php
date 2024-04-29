<?php
$title = 'Inventory';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}
include(__DIR__ .'/../inc/head.php');

use backend\bus\ProductBUS;

$productList = ProductBUS::getInstance()->getAllModels();
?>


<body>
    <!-- HEADER -->
    <?php include(__DIR__ .'/../inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR MENU -->
            <?php include(__DIR__ .'/../inc/sidebar.php'); ?>

            <!-- MAIN -->
            <main class="col-9 ms-sm-auto col-lg-10 px-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                    <div class="search-group input-group">
                        <input type="text" id="productSearch" class="searchInput form-control">
                        <button type="button" class="btn btn-sm btn-primary align-middle padx-0 pady-0">
                            <span data-feather="search"></span>
                        </button>
                    </div>
                    
                    <div class=""></div>
                </div>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th></th>
                            <th>Product Name</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr >
                            <td class='col-1'><img src='..\..\..\..\templates\images\680098.jpg' alt=''  class='rounded float-start'></td>
                            <td class='col-6'>Adidas</td>
                            <td class='col-2'>
                                <select name="form-select productSize" id="productSize">
                                    <option value="35" selected>35</option>
                                    <option value="36">36</option>
                                    <option value="37">37</option>
                                    <option value="38">38</option>
                                </select>
                            </td>
                            <td class='col-2'>100</td>
                            <td class='col-1'>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editQuantityModal">
                                    <span data-feather="tool"></span>
                                    Update
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </main>

            <!-- edit quantity modal -->
            <div class="modal fade" id="editQuantityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Size Quantity</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="inputSize" class="form-label">Size</label>
                            <select  name="inputSize" id="inputSize" class="form-select">
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                            </select>
                            <label for="inputQuantity" class="form-label">Quantity</label>
                            <input type="text" name="inputQuantity" id="inputQuantity" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
    <?php include(__DIR__ . '/../inc/app/app.php'); ?>
    <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
</body>
</html>
