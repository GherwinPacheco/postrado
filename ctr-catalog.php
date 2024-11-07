<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();

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
    <link rel="stylesheet" href="./css/ctr/ctr-catalog.css?t=<?=time()?>">

    <title>Catalog</title>
</head>
<body>
    <?php
        $module = "Catalog";
        $searchBar = true;
        include('./layouts/ctr/ctr-navbar.php');
    ?>
    <div id="main-div">
        <div class="img-heading">
            <h1 class="text-center text-medium">Catalog</h1>
            <span><a href="./ctr-landingpage.php" class="text-white d-inline-block">Home</a> / Offered Products</span>
        </div>


        
        
        <div class="container p-3 mb-5">

            <select id="category" class="form-control text-medium mx-auto" style="text-align: center; border: 0; font-size: 20px; width: 300px">
                <option value="all">All Products</option>
                    <?php
                        $result = $conn->query("SELECT * FROM category WHERE archived = 0");
                        while($row = $result->fetch_assoc()){
                            echo '
                                <option value="'.$row["id"].'">'.ucwords($row["category_name"]).' Products</option>
                            ';
                        }
                    ?>
            </select>


            <div class="row" id="product-catalog">
                
            </div>
            <div id="btn-div" class="d-flex justify-content-center mb-5">

            </div>
        
            
        </div>
    </div>

    <?php
        include("./layouts/terms-conditions.php");
    ?>


    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-navbar.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-catalog.js?t=<?=time()?>"></script>

    <?php include("./functions/alert-function.php"); ?>
</body>
</html>