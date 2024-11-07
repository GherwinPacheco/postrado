<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();

    $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();

    //get products data
    $productId = '0';
    if(isset($_GET["p"])){
        $productId = $_GET["p"];
    }
    $product = $conn->query("
        SELECT p.*, c.category_name
        FROM products p
            INNER JOIN category c ON p.category = c.id
        WHERE p.id = '$productId'
    ")->fetch_assoc();
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
    
    <link rel="stylesheet" href="./css/ctr/ctr-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/ctr/ctr-productView.css?t=<?=time()?>">
    <title>About Product</title>
</head>
<body>
    <?php
        $module = "Catalog";
        include('./layouts/ctr/ctr-navbar.php');
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3 mb-5">
            <?php
                if(isset($product["id"])){
            ?>
            <button class="btn btn-blue" onclick="history.back()">
                <i class="fa-solid fa-chevron-left"></i>&emsp;Back
            </button>

            <div class="row mt-5">
                <div class="col col-12 col-lg-6" style="position: relative;">
                    <!--Show Unavailable Mark if Unavailable-->
                    <?=$product["status"] == 0 ? '
                        <div class="bg-dark d-flex align-items-center" style="width: 94%; height: 100%; opacity: 0.5; position: absolute;">
                            <h3 class="text-white text-medium text-center w-100" style="opacity: 1">Unavailable</h3>
                        </div>
                    ' : ''?>
                    
                    <img id="productImg" class="" src="./img/products/<?=$product["id"]?>.png?t=<?=time()?>" alt="" style="width: 100%">

                    <small class="text-muted font-italic"><b>Note:</b> Images shown are for reference only, products may have slight difference due to production.</small>
                </div>
                
                <div class="col col-12 col-lg-6 pt-5" style="position: relative">
                    <?php 
                        if(isset($_SESSION["user"]) and $product["status"] == 1){ 
                            echo '
                                <a class="add-cart-btn text-dblue text-medium text-center pr-3" role="button" data-toggle="modal" data-target="#addToCartModal" style="position: absolute; right: 0">
                                    <i class="fa-solid fa-cart-plus"></i><br>
                                    Add to Cart
                                </a>
                            ';
                        }
                        elseif(!isset($_SESSION["user"]) and $product["status"] == 1){
                            echo '
                                <a class="text-dblue text-medium text-center pr-3" href="./login.php" style="position: absolute; right: 0">
                                    <i class="fa-solid fa-cart-plus"></i><br>
                                    Add to Cart
                                </a>
                            ';
                        }

                    ?>
                    
                    

                    <div class="productDetailsDiv">
                        <h3 id="productName" class="text-medium"><?=$product["product_name"]?></h3>

                        
                        <span id="price" class="text-secondary"></span>&emsp;
                        <del id="price2" class="text-secondary"></del>&emsp;
                        <span id="salePercent" class="text-success font-italic"><?=$product["sale"] ? $product["sale"]."% OFF" : ""?></span>
                    </div>

                    <hr>

                    <div id="variantsDiv">
                        <?php
                            $firstPrice = '';
                            $pricesResult = $conn->query("SELECT * FROM prices WHERE product_id = $productId");
                            $first = true;
                            if($pricesResult->num_rows > 1){
                                echo '<h6 class="text-medium">Variants</h6>';

                                while($row = $pricesResult->fetch_assoc()){
                                    $firstPrice = $first ? $row["id"] : $firstPrice;
                                    
                                    echo '<button id="variantBtn'.$row["id"].'" class="variantBtn btn btn-white border m-2" onclick="setVariantDetails('.$row["id"].')">'.$row["price_name"].'</button>';
                                    $first = false;
                                }

                                echo '<hr>';
                                
                            }
                            else{
                                $firstPrice = $pricesResult->fetch_assoc()["id"];
                            }
                        ?>
                    </div>
                    
                    
                    
                    <p id="description" class="text-secondary"><?=$product["description"]?></p>

                    <hr>

                    <div id="productSpecsDiv">
                        <h6 class="text-medium">Product Specifications</h6>
                        <ul>
                            
                        </ul>
                    </div>
                    

                </div>
            </div>

            
            

            <!-- Add to Modal -->
            <form action="#" method="post" onsubmit="addToCart()">
                <div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add to Cart</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="addToCart-productId" value="<?=$productId?>">
                            <input type="hidden" id="addToCart-priceId">
                            <input type="hidden" id="addToCart-colorId" value="0">
                            
                            <input type="hidden" id="addToCart-price">

                            <div class="row mb-2">
                                <div class="col col-8 d-flex flex-column justify-content-center">
                                    <h5 id="addToCart-productName" class="text-medium"><?=$product["product_name"]?></h5>
                                    <span id="addToCart-priceText" class="text-secondary"></span>
                                </div>
                                <div class="col col-4">
                                    <img id="addToCart-productImg" class="productImgModal float-right" src="./img/products/<?=$product["id"]?>.png?t=<?=time()?>" alt="">
                                </div>
                            </div>

                            <hr>

                            <div id="variantsDiv">
                                <?php
                                    $pricesResult = $conn->query("SELECT * FROM prices WHERE product_id = $productId");
                                    if($pricesResult->num_rows > 1){
                                        echo '<h6 class="text-medium">Variants</h6>';

                                        //output variant buttons for the modal
                                        //output variants that are not on cart
                                        while($row = $pricesResult->fetch_assoc()){
                                            
                                            echo '<button type="button" id="addToCart-variantBtn'.$row["id"].'" class="variantBtn btn btn-white border m-2" onclick="setVariantDetails('.$row["id"].')">'.$row["price_name"].'</button>';
                                            
                                            
                                        }

                                        echo '<hr>';
                                    }
                                ?>
                            </div>

                            
                            <div class="quantityDiv">
                                <h6 class="text-medium mb-2">Quantity</h6>

                                <div class="input-group mb-3" style="width:35%">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-white text-secondary" onclick="minusQuantity()">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="number" id="addToCart-quantity" class="form-control text-center text-medium mx-2" min="1" value="1" oninput="updateTotal()" required>
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-white text-secondary" onclick="addQuantity()">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>

                            <div class="row">
                                <div class="col varnishDiv">
                                    <?php
                                        $varnishPrice = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc()["varnish_price"];
                                    ?>
                                    <h6 class="text-medium d-inline-block mb-2">Varnish</h6>&nbsp;<small id="varnishAddText" class="text-muted">...</small>
                                    <select id="addToCart-varnish" class="form-control" onchange="updateTotal()" required>
                                        <option value selected hidden>Select Option</option>
                                        <option value="<?=$varnishPrice?>">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col colorDiv">
                                    <h6 class="text-medium d-inline-block mb-2">WoodStain Color</h6>&nbsp;<small id="colorAddText" class="text-muted">...</small>
                                    <select id="addToCart-color" class="form-control" onchange="updateTotal()" required>
                                        <option value selected hidden>Select Option</option>
                                        <option value="0">None</option>
                                        <?php
                                            $colors = $conn->query("SELECT * FROM color WHERE archived = 0");

                                            while($row = $colors->fetch_assoc()){
                                                $id = $row["id"];
                                                $colorName = $row["color_name"];
                                                $colorPrice = $row["price"];
                                                echo "<option value='{\"id\": \"$id\", \"price\": \"$colorPrice\"}'>$colorName</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                
                            </div>

                            
                            <hr>
                            
                            <h5 class="text-medium d-inline-block mb-2 text-secondary">Total:&emsp;</h5><h5 class="d-inline-block text-medium text-primary" id="addToCart-totalText"></h5>
                            

                            
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </div>
                        </div>
                    </div>
                </div>
            </form>

            


            <?php } else {?>

                <!-- If the url has no product parameter or if the product is not found -->
                <div class="d-flex flex-column justify-content-center align-items-center" style="height: 80vh">
                    <img src="./img/product_not_found.svg?t=<?=time()?>" alt="" width="200">
                    <br>
                    <h4 class="text-medium">Product Not Found</h4>
                    <br>
                    <a href="./ctr-catalog.php" class="btn btn-blue">Back to Catalog</a>
                </div>
                
            <?php } ?>
        </div>
    </div>

    

    <?php
        include("./layouts/terms-conditions.php");
    ?>

    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-navbar.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-productView.js?t=<?=time()?>"></script>
    <script type='text/javascript'>
        setVariantDetails(<?=(isset($firstPrice) and $firstPrice !== "") ? $firstPrice : 0?>);
    </script>
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>