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

    <title>Sales</title>
</head>
<body>
    <?php
        $module = 'Reports';
        $subModule = 'SalesReport';
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <div class="row">
                <div class="col">
                    <h3 class="heading-text text-medium mb-4">Sales Report</h3>
                </div>
                <div class="col text-right">
                    <button class="btn btn-primary" onclick="$('#reports-form').submit();">Print</button>
                </div>
            </div>
            

            
            
            <div class="row md-hide mb-1">
                <div class="col p-0 px-1">
                    <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                        <h5 class="text-success p-0 m-0"><i class="fa-solid fa-money-bills"></i></h5>
                        <span class="overview-totalSales text-medium text-success d-block"></span>
                        <span class="text-medium d-block">Total Sales</span>
                    </div>
                </div>
                <div class="col px-1">
                    <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                        <h5 class="text-info p-0 m-0"><i class="fa-solid fa-cubes"></i></h5>
                        <span class="overview-productsSold text-medium text-info d-block"></span>
                        <span class="text-medium d-block">Products Sold</span>
                    </div>
                </div>
                <div class="col px-1">
                    <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                        <h5 class="text-dark p-0 m-0"><i class="fa-solid fa-truck-fast"></i></h5>
                        <span class="overview-activeOrders text-medium text-dark d-block"></span>
                        <span class="text-medium d-block">Active Orders</span>
                    </div>
                </div>
                <div class="col px-1">
                    <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                        <h5 class="text-primary p-0 m-0"><i class="fa-solid fa-box-open"></i></h5>
                        <span class="overview-completeOrders text-medium text-primary d-block"></span>
                        <span class="text-medium d-block">Completed Orders</span>
                    </div>
                </div>
                <div class="col px-1">
                    <div class="overviewContainer bg-white shadow rounded my-1 py-4 text-center">
                        <h5 class="text-danger p-0 m-0"><i class="fa-solid fa-calendar-xmark"></i></h5>
                        <span class="overview-cancelledOrders text-medium text-danger d-block"></span>
                        <span class="text-medium d-block">Cancelled Orders</span>
                    </div>
                </div>
            </div>

            <div class="hide md-block bg-white shadow rounded my-1 p-3">
                <div class="row">
                    <div class="col col-6">
                        <small class="text-muted text-medium">Total Sales:</small>
                    </div>
                    <div class="col col-6">
                        <small class="overview-totalSales text-success text-medium"></small>
                    </div>

                    <div class="col col-6">
                        <small class="text-muted text-medium">Products Sold:</small>
                    </div>
                    <div class="col col-6">
                        <small class="overview-productsSold text-info text-medium"></small>
                    </div>

                    <div class="col col-6">
                        <small class="text-muted text-medium">Active Orders:</small>
                    </div>
                    <div class="col col-6">
                        <small class="overview-activeOrders text-dark text-medium"></small>
                    </div>
                    
                    <div class="col col-6">
                        <small class="text-muted text-medium">Completed Orders:</small>
                    </div>
                    <div class="col col-6">
                        <small class="overview-completeOrders text-primary text-medium"></small>
                    </div>

                    <div class="col col-6">
                        <small class="text-muted text-medium">Cancelled Orders:</small>
                    </div>
                    <div class="col col-6">
                        <small class="overview-cancelledOrders text-danger text-medium"></small>
                    </div>
                </div>
            </div>
            


            <div class="row">

                <!--Sales Chart -->
                <div class="col col-12 col-lg-8 mb-2 px-1">
                    <div class="bg-white shadow rounded p-3">
                        <div class="row mb-2">
                            <div class="col col-7">
                                <h5 class="text-medium mb-3 d-inline" id="salesGraph-header"></h5>
                            </div>
                            <div class="col col-5 text-right">
                                <button type="button"class="btn btn-white text-secondary" data-toggle="modal" data-target="#salesGraphSettings">
                                    <i class="fa-solid fa-gear"></i>
                                </button>
                            </div>
                            
                        </div>


                        <canvas id="salesChart" style="width:100%;"></canvas>
                        
                    </div>
                </div>
                <div class="col col-12 col-lg-4 mb-2 px-1">
                    <div class="bg-white shadow rounded p-3" style="height: 100%">
                        <h5 class="text-medium d-block">Best Selling Categories</h5>

                        <canvas id="categoryChart" class="mr-3" style="width:100%;"></canvas>
                        
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col col-12 col-lg-8 mb-2 px-1">
                    <div class="bg-white shadow rounded p-3" style="height: 100%">
                        <h5 class="text-medium mb-3 d-block">Sold Products</h5>

                        <table class="table md-hide" id="productSales-table">
                            <thead>
                                <th>#</th>
                                <th>PRODUCT</th>
                                <th>QUANTITY</th>
                                <th>TOTAL</th>
                                <th>DATE</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                        <div id="productSales-table-mobile" class="hide md-block">
                            
                        </div>

                        
                        <nav class="pagination-div d-flex justify-content-center mt-3" id="productSales-pagination" aria-label="...">
                            <ul class="pagination">
                                
                            </ul>
                        </nav>

                    </div>
                </div>
                <div class="col col-lg-4 mb-2 px-1">
                    <div class="bg-white shadow rounded p-3" style="height: 100%">
                        <h5 class="text-medium mb-4 d-block">Top Selling Products</h5>
                        

                        <div class="row py-2">
                            <div class="col col-1 text-center text-medium">#</div>
                            <div class="col col-6 text-center text-medium">PRODUCT</div>
                            <div class="col col-5 text-center text-medium">TOTAL SALES</div>
                        </div>
                        
                        <div id="topProducts-list">

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    


    <!--Sales Graph Modal -->
    <form id="reports-form" action="adm-printSalesReport.php" method="post">
        <div class="modal fade" id="salesGraphSettings" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Configure Sales Chart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col">
                        <label for="">Type</label>
                        <select class="form-control p-0" name="salesGraph-type" id="salesGraph-type">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Annually</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="">Month</label>
                        <select class="form-control p-0" name="salesGraph-month" id="salesGraph-month">
                            <?php
                                for($x = 12; $x >= 1; $x--){
                                    $selected = $x == date('m') ? 'selected' : '';
                                    echo '
                                        <option value="'.date('m', strtotime("2024-$x-01")).'" '.$selected.'>'.date('F', strtotime("2024-$x-01")).'</option>
                                    ';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="">Year</label>
                        <select class="form-control p-0" name="salesGraph-year" id="salesGraph-year">
                            <?php
                                $firstYear = $conn->query("SELECT (MIN(YEAR(date_completed)) - 2) AS min_year FROM orders WHERE order_status = 'complete'")->fetch_assoc()["min_year"];
                                for($x = date("Y"); $x >= $firstYear; $x--){
                                    $selected = $x == date('Y') ? 'selected' : '';
                                    echo '
                                        <option value="'.date('Y', strtotime("$x-01-01")).'" '.$selected.'>'.date('Y', strtotime("$x-01-01")).'</option>
                                    ';
                                }
                            ?>
                        </select>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="generateSalesChart()">Update</button>
            </div>
            </div>
        </div>
        </div>
    </form>
    

    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-salesReport.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html>