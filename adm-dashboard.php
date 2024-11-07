<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(1));

    $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();
    
    $totalSales = $conn->query("SELECT SUM(total) AS total_sales FROM orders WHERE order_status = 'complete'")->fetch_assoc()["total_sales"];
    $activeOrders = $conn->query("SELECT COUNT(id) AS active_orders FROM orders WHERE order_status = 'pending' OR order_status = 'preparing' OR order_status = 'ready'")->fetch_assoc()["active_orders"];
    $completeOrders = $conn->query("SELECT COUNT(id) AS complete_orders FROM orders WHERE order_status = 'complete' AND DATE(date_completed) = CURDATE()")->fetch_assoc()["complete_orders"];
    $criticalMaterials = $conn->query("SELECT COUNT(id) AS critical_materials FROM materials WHERE status = '2' OR status = '3'")->fetch_assoc()["critical_materials"];

    $salesGraphLabels = array();
    $salesGraphValues = array();
    for($x = 6; $x >= 0; $x--){
        $date = date("Y-m-d", strtotime("-$x days"));

        $total = $conn->query("
            SELECT
                IFNULL(SUM(total), 0) AS total_sales
            FROM orders
            WHERE 
                DATE(date_created) = '$date' OR 
                DATE(date_completed) = '$date'
        ")->fetch_assoc()["total_sales"];

        array_push($salesGraphLabels, date("M d", strtotime($date)));
        array_push($salesGraphValues, $total);
    }
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

    <title>Administrator Dashboard</title>
</head>
<body>
    <?php
        $module = 'Dashboard';
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <h3 class="heading-text text-medium mb-4">Dashboard</h3>


            <div class="row">
                <div class="col col-12 col-lg-7 px-1">

                    <!-- Overview -->
                    <div class="overviewContainer bg-white shadow rounded p-3" style="height: 100%">
                        <h5 class="text-medium mb-3">Shop Overview</h5>

                        <div class="row px-2">
                            <div class="col col-6 col-lg-3 p-0 px-1">
                                <div class="overviewContainer rounded my-1 p-3" style="background-color: #d8fadf">
                                    <div class="rounded-circle bg-success text-white p-2 mb-2" style="width: 37px; height: 37px">
                                        <i class="fa-solid fa-money-bills"></i>
                                    </div>
                                    <br>
                                    <h6 class="text-dark text-medium m-0">₱ <?=number_format($totalSales, 2)?></h6>
                                    <small class="text-thin d-block">Total Sales</small>
                                </div>
                            </div>

                            <div class="col col-6 col-lg-3 p-0 px-1">
                                <div class="overviewContainer rounded my-1 p-3" style="background-color: #fffcaf">
                                    <div class="rounded-circle text-white p-2 mb-2" style="width: 37px; height: 37px; background-color: #FD9A01">
                                        <i class="fa-solid fa-truck-fast"></i>
                                    </div>
                                    <br>
                                    <h6 class="text-dark text-medium m-0"><?=$activeOrders?> Active Orders</h6>

                                </div>
                            </div>

                            <div class="col col-6 col-lg-3 p-0 px-1">
                                <div class="overviewContainer rounded my-1 p-3" style="background-color: #D8EBFA">
                                    <div class="rounded-circle bg-primary text-white p-2 mb-2" style="width: 37px; height: 37px">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <br>
                                    <h6 class="text-dark text-medium m-0"><?=$completeOrders?> Completed Orders</h6>

                                </div>
                            </div>

                            <div class="col col-6 col-lg-3 p-0 px-1">
                                <div class="overviewContainer rounded my-1 p-3" style="background-color: #fad8d8">
                                    <div class="rounded-circle bg-danger text-white p-2 mb-2 text-center" style="width: 37px; height: 37px">
                                        <i class="fa-brands fa-dropbox"></i>
                                    </div>
                                    <br>
                                    <h6 class="text-dark text-medium m-0"><?=$criticalMaterials?> Critical Materials</h6>

                                </div>
                            </div>

                            
                            
                        </div>

                    </div>
                    


                </div>
                <div class="col col-12 col-lg-5 px-1">

                    <!-- Sales Graph -->
                    <div class="overviewContainer bg-white shadow rounded p-3">
                        <h5 class="text-medium mb-3">Sales for the Last 7 Days</h5>
                        <canvas class="mr-3" id="myChart" style="width:100%;max-width:600px;height: 160px"></canvas>
                    </div>
                    
                </div>
            </div>
            

            <div class="row">
                <div class="col col-12 col-lg-7 mb-2 px-1">
                    
                    <!-- Pending Orders -->
                    <div class="bg-white shadow rounded p-3 mt-2" style="height: 100%">
                        <h5 class="text-medium mb-3 d-block">For Approval Orders</h5>

                        <table class="table" id="pendingOrders">
                            <thead>
                                <th>#</th>
                                <th>ORDER NO.</th>
                                <th>CUSTOMER</th>
                                <th>PRICE</th>
                            </thead>
                            <tbody>
                                <?php
                                    $result = $conn->query("
                                        SELECT ord.*, us.username
                                        FROM orders ord
                                            INNER JOIN users us ON ord.added_by = us.id
                                        WHERE ord.order_status = 'pending' 
                                        LIMIT 10
                                    ");
                                    if($result->num_rows > 0){
                                        $num = 1;
                                        while($row = $result->fetch_assoc()){
                                            echo '
                                                <tr>
                                                    <th class="text-center">'.$num.'</th>
                                                    <td class="text-center">ORD-'.$row["id"].'</td>
                                                    <td class="text-center">'.$row["username"].'</td>
                                                    <td class="text-center">₱ '.number_format($row["total"], 2).'</td>
                                                <tr>
                                            ';
                                            $num++;
                                        }
                                    }
                                    else{
                                        echo '
                                            <tr>
                                                <td class="text-center" colspan="4">No Pending Orders</td>
                                            <tr>
                                        ';
                                    }
                                ?>
                            </tbody>
                        </table>

                        
                    </div>
                </div>

                <div class="col col-12 col-lg-5 mb-2 px-1">

                    <div class="bg-white shadow rounded p-3 mt-2" style="height: 100%">
                        <h5 class="text-medium d-block mb-4">Critical Materials</h5>

                        <table class="table" id="criticalMaterials">
                            <thead>
                                <th></th>
                                <th>NAME</th>
                                <th>STATUS</th>
                            </thead>
                            <tbody>
                                <?php
                                    $result = $conn->query("
                                        SELECT * FROM materials WHERE status > 1 ORDER BY status DESC
                                    ");
                                    if($result->num_rows > 0){
                                        $num = 1;
                                        while($row = $result->fetch_assoc()){
                                            $statusBadge = '';
                                            if($row["status"] == 2){
                                                $statusBadge = '<span class="badge badge-secondary">Low Stock</span>';
                                            }
                                            else{
                                                $statusBadge = '<span class="badge badge-dark">Out of Stock</span>';
                                            }
                                            echo '
                                                <tr>
                                                    <th class="text-center">'.$num.'</th>
                                                    <td class="text-center">'.$row["material_name"].'</td>
                                                    <td class="text-center">'.$statusBadge.'</td>
                                                <tr>
                                            ';
                                            $num++;
                                        }
                                    }
                                    else{
                                        echo '
                                            <tr>
                                                <td class="text-center" colspan="3">No Critical Materials</td>
                                            <tr>
                                        ';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </div>

    

    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script>
        const xValues = <?=json_encode($salesGraphLabels)?>;
        const yValues = <?=json_encode($salesGraphValues)?>;

        const myChart = new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
            data: yValues
            }]
        },
        options: {
            responsive: true,
            plugins:{
                legend:{
                    display: false
                }
            }
        }
        });
    </script>
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html> 