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

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!--Fontawesome-->
    <script src="https://kit.fontawesome.com/2487355853.js" crossorigin="anonymous"></script>

    <!--CSS-->
    <link rel="stylesheet" href="./css/animations.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/styles.css?t=<?=time()?>">
    
    <link rel="stylesheet" href="./css/adm/adm-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/adm/adm-reports.css?t=<?=time()?>">

    <title>Material Reporting</title>
</head>
<body>
    <?php
        $module = 'Reports';
        $subModule = 'InventoryReport';
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <div class="row">
                <div class="col">
                    <h3 class="heading-text text-medium mb-4">Materials Inventory Report</h3>
                </div>
                <div class="col text-right">
                    <a class="btn btn-primary" href="adm-printInventoryReport.php">Print</a>
                </div>
            </div>
            

            <div class="row">
                <div class="col col-12 col-lg-8 mb-2 px-1">
                    <div class="row md-hide">
                        <div class="col p-0 px-1 pl-3">
                            <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                                <h5 class="text-success p-0 m-0"><i class="fa-solid fa-money-bills"></i></h5>
                                <span class="overview-inventoryTotal text-medium text-success d-block"></span>
                                <span class="text-medium d-block">Material Cost</span>
                            </div>
                        </div>
                        <div class="col px-1">
                            <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                                <h5 class="text-primary p-0 m-0"><i class="fa-solid fa-dolly"></i></h5>
                                <span class="overview-inStocks text-medium text-info d-block"></span>
                                <span class="text-medium d-block">In Stocks</span>
                            </div>
                        </div>
                        <div class="col px-1">
                            <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                                <h5 class="text-secondary p-0 m-0"><i class="fa-solid fa-box-open"></i></h5>
                                <span class="overview-lowStocks text-medium text-dark d-block"></span>
                                <span class="text-medium d-block">Low Stocks</span>
                            </div>
                        </div>
                        <div class="col px-1 pr-3">
                            <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                                <h5 class="text-danger p-0 m-0"><i class="fa-brands fa-dropbox"></i></h5>
                                <span class="overview-outOfStocks text-medium text-dark d-block"></span>
                                <span class="text-medium d-block">Out of Stocks</span>
                            </div>
                        </div>
                    </div>


                    <div class="hide md-block bg-white shadow rounded my-1 p-3">
                        <div class="row">
                            <div class="col col-6">
                                <small class="text-muted text-medium">Inventory Value:</small>
                            </div>
                            <div class="col col-6">
                                <small class="overview-inventoryTotal text-success text-medium"></small>
                            </div>

                            <div class="col col-6">
                                <small class="text-muted text-medium">In Stocks:</small>
                            </div>
                            <div class="col col-6">
                                <small class="overview-inStocks text-primary text-medium"></small>
                            </div>

                            <div class="col col-6">
                                <small class="text-muted text-medium">Low Stocks:</small>
                            </div>
                            <div class="col col-6">
                                <small class="overview-lowStocks text-secondary text-medium"></small>
                            </div>
                            
                            <div class="col col-6">
                                <small class="text-muted text-medium">Out of Stocks:</small>
                            </div>
                            <div class="col col-6">
                                <small class="overview-outOfStocks text-danger text-medium"></small>
                            </div>

                        </div>
                    </div>
                    
                    <div class="bg-white shadow rounded p-3 mt-2" style="height: 700px; overflow-y: auto">
                        <!-- <h5 class="text-medium mb-3 d-block">List of Materials</h5> -->

                        <div id="inStock-container">

                        </div>
                        <div id="inStock-pagination" class="mb-5">
                            <ul class="pagination float-right">
                                
                            </ul>
                        </div>

                        <div id="lowStock-container">
                            
                        </div>
                        <div id="lowStock-pagination" class="mb-5">
                            <ul class="pagination float-right">
                                
                            </ul>
                        </div>

                        <div id="outOfStock-container">
                            
                        </div>
                        <div id="outOfStock-pagination" class="mb-5">
                            <ul class="pagination float-right">
                                
                            </ul>
                        </div>

                        <!-- <table class="table" id="inStock-table">
                            <thead>
                                <th>#</th>
                                <th>MATERIAL</th>
                                <th>QUANTITY</th>
                                <th class="md-hide">COST</th>
                                <th>STATUS</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>



                        <nav class="pagination-div d-flex justify-content-center mt-3" id="materials-pagination" aria-label="...">
                            <ul class="pagination">
                                
                            </ul>
                        </nav> -->
                        
                    </div>

                    


                </div>
                <div class="col col-12 col-lg-4 mb-2 px-1">
                    <div class="bg-white shadow rounded p-3" style="height: 100%">
                        <h5 class="text-medium d-block mb-4">Material Usage</h5>

                        <div id="materialsUsage-list" style="height: 750px; overflow-y: auto;">
                            
                            
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

    

    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-inventoryReport.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html> 