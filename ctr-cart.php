<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();

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
    
    <link rel="stylesheet" href="./css/ctr/ctr-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/ctr/ctr-cart.css?t=<?=time()?>">

    <title>My Cart</title>
</head>
<body>
    <?php
        $module = "Catalog";
        include('./layouts/ctr/ctr-navbar.php');
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">
            
            <button class="btn btn-blue mb-5" onclick="history.back()">
                <i class="fa-solid fa-chevron-left"></i>&emsp;Back
            </button>
            
            <h3 class="text-medium">My Cart</h3>
            <form action="./ctr-orderForm.php" method="post">

                <div class="collapse md-hide" id="cartTotal">
                    <div class="card card-body border-0 pl-2">
                        <div class="row">
                            <div class="col col-12 d-flex justify-content-end align-items-center p-0 pb-3">
                                <h5 class="text-medium m-0 ml-3" id="totalOrder">0.00</h5>
                            </div>
                            <div class="col col-6 d-flex align-items-center">
                                <input type="checkbox" id="selectAll">
                                &emsp;
                                <label for="selectAll" class="m-0">Select All</label>
                            </div>

                            <div class="col col-6 d-flex justify-content-end align-items-center p-0">
                                <button type="button" class="btn btn-white text-danger mr-4" onclick="deleteCart()">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                                <button type="submit" class="btn btn-primary text-medium">
                                    <i class="fa-solid fa-truck-fast"></i>&emsp;Checkout
                                </button>
                                
                            </div>

                        </div>
                    </div>
                </div>


                <table class="table md-hide" id="cartList">
                    <thead>
                        <th colspan="2">PRODUCTS</th>
                        <th>QUANTITY</th>
                        <th>ADDITIONALS</th>
                        <th>TOTAL</th>
                        <th></th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </form>

            <form action="./ctr-orderForm.php" method="post">
                <div class="collapse hide md-block" id="cartTotalMobile">
                    <div class="card card-body border-0 pl-2">
                        <div class="row">
                            <div class="col col-12">
                                <span class="text-medium m-0 ml-3 float-right" id="totalOrderMobile">0.00</span>
                            </div>
                            <div class="col col-6 d-flex align-items-center">
                                <input type="checkbox" id="selectAllMobile">
                                &emsp;
                                <label for="selectAll" class="m-0">Select All</label>
                            </div>

                            <div class="col col-6 d-flex justify-content-end align-items-center p-0">
                                <button type="button" class="btn btn-white text-danger mr-4" onclick="deleteCart()">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                                <button type="submit" class="btn btn-primary text-medium">
                                    <i class="fa-solid fa-truck-fast"></i>&emsp;Checkout
                                </button>
                                
                            </div>

                        </div>
                    </div>
                </div>
                <div id="cartListMobile" class="hide md-block mt-4 mb-5">
                    
                </div>
                
            </form>

            


        </div>
    </div>

    

    <?php
        include("./layouts/terms-conditions.php");
    ?>

    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-navbar.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-cart.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>