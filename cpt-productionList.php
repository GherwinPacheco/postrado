<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(2));

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
    
    <link rel="stylesheet" href="./css/styles.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/adm/adm-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/cpt/cpt-productionList.css?t=<?=time()?>">

    <title>Custom Production</title>
</head>
<body>
    <?php
        $module = "ProductionList";
        $searchBar = true;
        include("layouts/cpt/cpt-navbar.php");
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">

            <h3 class="heading-text text-medium">In Production</h3>

            <div class="customNavsDiv md-hide my-3">
                <button id="btn-normal" class="typeBtn btn btn-dblue border m-2" onclick="setType('normal')">Standard Orders</button>
                <button id="btn-custom" class="typeBtn btn btn-white border m-2" onclick="setType('custom')">Custom Orders</button>
            </div>

            <select class="form-control w-50 hide md-block my-3" id="type" onchange="setType(this.value)">
                <option value="normal" selected>Normal</option>
                <option value="custom">Custom</option>
            </select>

            <div class="table-div shadow md-hide">
                <table class="table table-border md-hide" id="production-list">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ORDER NO.</th>
                            <th colspan="2">FURNITURE</th>
                            <th>CUSTOMER</th>
                            <th>CATEGORY</th>
                            <th>TARGET DATE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            


            <div id="production-list-mobile" class="hide md-block">
                
            </div>
            
            <nav class="pagination-div d-flex justify-content-center mt-3" id="production-list-pagination" aria-label="...">
                <ul class="pagination">
                    
                </ul>
            </nav>

            
        </div>
    </div>
    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/cpt/cpt-productionList.js?t=<?=time()?>"></script>


    
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>