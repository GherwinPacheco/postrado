<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(1));

    $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!--Bootstrap and JQuery-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>


    <!--Fontawesome-->
    <script src="https://kit.fontawesome.com/2487355853.js" crossorigin="anonymous"></script>

    <!--CSS-->
    <link rel="stylesheet" href="./css/animations.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/styles.css?t=<?=time()?>">
    
    <link rel="stylesheet" href="./css/adm/adm-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/adm/adm-furnitures.css?t=<?=time()?>">

    <title>Products</title>
</head>
<body>
    <?php
        $module = 'Products';
        $subModule = 'Furnitures';
        $searchBar = true;
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <!--<button onclick="alert(pCount)">pCount</button>-->
            <h3 class="heading-text text-medium">Furniture List</h3>


            <!-- Table Filters and Add Button -->
            <div class="row mb-3">
                <div class="col col-4">
                    <select class="form-control furniture-tbl-filter mr-3" name="" id="furniture-status">
                        <option value="all">All</option>
                        <option value="1">Available</option>
                        <option value="0">Unavailable</option>
                    </select>
                </div>
                <div class="col col-4">
                    <select class="form-control furniture-tbl-filter" name="" id="furniture-category">
                        <option value="all">All</option>
                        <?php
                            $result = $conn->query("SELECT * FROM category WHERE archived = 0");

                            while($row = $result->fetch_assoc()){
                                echo '
                                    <option value="'.$row["id"].'">'.ucwords($row["category_name"]).'</option>
                                ';
                            }
                        ?>
                    </select>
                </div>
                <div class="col col-4">
                    <button id="add-furniture-btn" class="btn btn-blue float-right" data-toggle="modal" data-target="#addProductModal" onclick="addProductPrice('addProduct')">
                        <i class="fa-solid fa-plus"></i>&emsp;Add
                    </button>
                </div>
            </div>

            
            <div class="table-div shadow md-hide">
                <table class="table table-border md-hide" id="furnitures-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="2">PRODUCT</th>
                            <th>CATEGORY</th>
                            <th>PRICE</th>
                            <th>STATUS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            <div class="table-div-mobile hide md-block">
                <div id="furniture-table-mobile" class="hide md-block">
                    
                </div>
            </div>
            <nav class="pagination-div d-flex justify-content-center mt-3" id="furniture-table-pagination" aria-label="...">
                <ul class="pagination">
                    
                </ul>
            </nav>
        </div>
    </div>

    <!-- View Product Modal -->
    <div class="modal fade" id="viewProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Furniture Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col col-8 d-flex flex-column justify-content-center">
                        <h5 id="viewProduct-productName" class="text-medium">Product Name</h5>

                    </div>
                    <div class="col col-4">
                        <img id="viewProduct-productImg" class="productImgModal float-right" src="./img/products/1.png" alt="Product Image">
                    </div>
                </div>
                <p class="text-justify" id="viewProduct-productDescription"></p>
                <hr>
                <span class="text-secondary">Variation</span>
                <ul id="viewProduct-priceList" class="ml-2">
                    
                </ul>
                <hr>
                <span class="text-secondary">Materials Used</span>
                <ul id="viewProduct-materialList" class="ml-2">

                </ul>
                <hr>
                <span class="text-muted">Date Added:</span>&emsp;<span id="viewProduct-dateCreated"></span></span>&emsp;<span id="viewProduct-timeCreated"></span><br>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>



    <!-- Add Product Modal -->
    <form class="modal-form" method="post" action="actions/productActions.php" enctype="multipart/form-data">
        <div class="modal fade ressetable-modal" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add New Furniture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <img id="addProduct-productImg" class="productImgModal float-right" src="./img/products/product_default.svg" alt="">
                    <div class="w-50">
                        <label for="addProduct-imageFile">Furniture Image<span class="text-danger">*</span></label>
                        <input type="file" name="imageFile" id="addProduct-imageFile" accept="image/*"  onchange="showImg(this, 'addProduct-productImg')" required>
                    </div><br>
                    
                    <hr>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="addProduct-productName">Name<span class="text-danger">*</span></label>
                            <input type="text" name="productName" id="addProduct-productName" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="addProduct-productCategory">Category<span class="text-danger">*</span></label>
                            <select name="category" id="addProduct-productCategory" class="form-control" onchange="changeCategorySpecs('addProduct')" required>
                                <option value selected hidden>Select Category</option>
                                <?php
                                    $result = $conn->query("SELECT * FROM category WHERE archived = 0");

                                    while($row = $result->fetch_assoc()){
                                        echo '
                                            <option value="'.$row["id"].'">'.ucwords($row["category_name"]).'</option>
                                        ';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="row mb-3">
                        <div class="col">
                            <label for="addProduct-productionDuration">Production Duration<span class="text-danger">*</span></label>
                            <input type="number" name="productionDuration" id="addProduct-productionDuration" class="form-control" placeholder="No. of days" min="1" required>
                        </div>
                        <div class="col"></div>
                    </div>

                    <label for="addProduct-description">Product Description</label>
                    <textarea class="form-control" name="description" id="addProduct-description"></textarea>
                    <hr>
                    
                    <input type="hidden" name="priceCount" class="priceCount" id="addProduct-priceCount">
                    <span>Variation</span> <span class="text-danger">*</span>&emsp;<a href="#" role="button" onclick="addProductPrice('addProduct')">[Add]</a>
                    <div class="priceList mt-2" id="addProduct-priceList">
                        
                    </div>

                    <hr>

                    <span id="addProduct-materialListHeader">Product Materials (0)</span><a type="button" class="btn btn-white text-primary" data-toggle="collapse" data-target="#addProduct-materialList" onclick=""><i class="fa-solid fa-chevron-down"></i></a>
                    <div class="collapse materialsCollapse pl-3 py-2 ml-3" id="addProduct-materialList">
                        <?php
                            $result = $conn->query("
                                SELECT id, material_name, status FROM materials WHERE archived = 0 ORDER BY material_name ASC
                            ");

                            while($row = $result->fetch_assoc()){
                                $statusBadge = '';
                                if($row["status"] == 2){
                                    $statusBadge = '<span class="badge badge-secondary">Low Stock</span>';
                                }
                                elseif($row["status"] == 3){
                                    $statusBadge = '<span class="badge badge-dark">Out of Stock</span>';
                                }

                                echo '
                                    <input class="form-check-input materialCheckbox" type="checkbox" name="material[]" value="'.$row["id"].'" onclick="countCheckedMaterial(\'addProduct\')">
                                    <label class="form-check-label" for="autoSizingCheck">
                                        '.$row["material_name"].'&emsp;'.$statusBadge.'
                                    </label>
                                    <br>
                                ';
                            }
                        ?>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-blue" name="action" value="add_product">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Edit Product Modal -->
    <form class="modal-form" method="post" action="actions/productActions.php" enctype="multipart/form-data">
        <div class="modal fade ressetable-modal" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit Furniture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <img id="editProduct-productImg" class="productImgModal float-right" src="./img/products/product_default.svg" alt="">
                    <div class="w-50">
                        <label for="editProduct-imageFile">Furniture Image</label>
                        <input type="file" name="imageFile" id="editProduct-imageFile" accept="image/*"  onchange="showImg(this, 'editProduct-productImg')">
                    </div><br>
                    
                    <hr>

                    <input type="hidden" name="productId" id="editProduct-productId">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="editProduct-productName">Product Name<span class="text-danger">*</span></label>
                            <input type="text" name="productName" id="editProduct-productName" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="editProduct-productCategory">Category<span class="text-danger">*</span></label>
                            <select name="category" id="editProduct-productCategory" class="form-control" onchange="changeCategorySpecs('editProduct')" required>
                                <option value selected hidden>Select Category</option>
                                <?php
                                    $result = $conn->query("SELECT * FROM category WHERE archived = 0");

                                    while($row = $result->fetch_assoc()){
                                        echo '
                                            <option value="'.$row["id"].'">'.ucwords($row["category_name"]).'</option>
                                        ';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col">
                            <label for="editProduct-productionDuration">Production Duration<span class="text-danger">*</span></label>
                            <input type="number" name="productionDuration" id="editProduct-productionDuration" class="form-control" placeholder="No. of days" min="1" required>
                        </div>
                        <div class="col"></div>
                    </div>


                    <label for="editProduct-description">Product Description</label>
                    <textarea class="form-control" name="description" id="editProduct-description"></textarea>

                    <hr>
                    
                    <input type="hidden" name="priceCount" class="priceCount" id="editProduct-priceCount">
                    <span>Product Prices</span><span class="text-danger">*</span>&emsp;<a href="#" role="button" onclick="addProductPrice('editProduct')">[Add]</a>
                    <div class="mt-2" class="priceList" id="editProduct-priceList">
                        
                    </div>

                    <hr>

                    <span id="editProduct-materialListHeader">Product Materials (0)</span><a type="button" class="btn btn-white text-primary" data-toggle="collapse" data-target="#editProduct-materialList" onclick=""><i class="fa-solid fa-chevron-down"></i></a>
                    <div class="collapse materialsCollapse border-left pl-3 py-2 ml-3" id="editProduct-materialList">
                        <?php
                            $result = $conn->query("
                                SELECT id, material_name, status FROM materials WHERE archived = 0 ORDER BY material_name ASC
                            ");

                            while($row = $result->fetch_assoc()){
                                $statusBadge = '';
                                if($row["status"] == 2){
                                    $statusBadge = '<span class="badge badge-secondary">Low Stock</span>';
                                }
                                elseif($row["status"] == 3){
                                    $statusBadge = '<span class="badge badge-dark">Out of Stock</span>';
                                }

                                echo '
                                    <input class="form-check-input materialCheckbox" type="checkbox" name="material[]" value="'.$row["id"].'" onclick="countCheckedMaterial(\'editProduct\')>
                                    <label class="form-check-label" for="autoSizingCheck">
                                        '.$row["material_name"].'&emsp;'.$statusBadge.'
                                    </label>
                                    <br>
                                ';
                            }
                        ?>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_product">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Set Materials Stock Modal -->
    <form class="modal-form" method="post" action="actions/productActions.php">
        <div class="modal fade" id="setSaleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Set Product Sale %</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="setSale-productNameText" class="text-medium">Product Name</h5>
                    <hr>
                    
                    <input type="hidden" id="setSale-productId" name="productId">

                    <label for="">Sale Percentage</label>
                    <div class="input-group mb-3">
                    <input type="number" class="form-control" id="setSale-sale" name="sale" min="0" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">%</span>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="set_sale">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Archive Product Modal -->
    <form class="modal-form" method="post" action="actions/productActions.php">
        <div class="modal fade ressetable-modal" id="archiveProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive Furniture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="productId" id="archiveProduct-productId">
                    
                    <p class="text-center">
                        Are you sure you want to archive<br>
                        <b id="archiveProduct-productNameText"></b>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" name="action" value="archive_product">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    


    


    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-furnitures.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html>