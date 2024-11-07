<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(1));


    $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();

    $userInfo = $conn->query("SELECT * FROM users WHERE id = ".$_SESSION["user"])->fetch_assoc();


    $salesGraphType = $_POST["salesGraph-type"];
    $salesGraphMonth = $_POST["salesGraph-month"];
    $salesGraphYear = $_POST["salesGraph-year"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable A4 Page</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap');

    *, .text-regular {
        font-family: "Work Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 400;
        font-variation-settings:
            "wdth" 100;
            
    }


    .text-light {
        font-family: "Work Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 300;
        font-variation-settings:
            "wdth" 100;
    }

    .text-medium {
        font-family: "Work Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        font-variation-settings:
            "wdth" 100;
    }

    @page {
        size: A4;
        margin: 10.16mm;
    }
    @media print {
        html, body {
            width: 210mm;
            height: 297mm;        
        }
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }

    body {
        margin: 0;
        padding: 0;
        font-family: "Work Sans", sans-serif;
    }

    .page {
        margin: auto;
        padding: 30px;
        width: 210mm;
        /*height: 297mm;
        border: 1px solid lightgray;*/
    }


    .brand-name{
        font-style: italic; 
        font-weight: bold;
        color: #79341e;
        text-align: center;
    }

    .brand-details{
        text-align: center;
        display: block;
    }

    .brand-div{
        margin-bottom: 40px;
    }

    .issued-div{
        text-align: right; 
        margin-top: 30px;
    }


    h1, h2, h3, h4, h5, h6, p{
        padding: 0;
        margin: 0;
    }

    table{
        width: 100%;
        border: 1px solid lightgray;
        padding: 10px 0px;
        border-collapse: collapse;
    }


    table th, table td{
        padding: 10px 5px;
        border-top: 1px solid lightgray;
    }

    table th{
        font-optical-sizing: auto;
        font-weight: 500;
        font-variation-settings:
            "wdth" 100;
    }
    
    
    

    .text-muted{
        color: #6c757d;
    }

    .text-left{
        text-align: left;
    }

    .text-center{
        text-align: center;
    }

    .text-right{
        text-align: right;
    }

    .reports-header{
        margin-top: 2rem;
    }

    .btn{
        margin: 10px;
        padding: 10px 20px; 
        border: none; 
        border-radius: 5px; 
        background-color: #0d6efd; 
        color: white;
        cursor: pointer;
    }
    
</style>

<body>
    <button id="printBtn" class="btn text-medium hide-print" style="position: absolute; right: 0; top: 30; ">Print</button>
    <div class="hide-print" style="height: 100px"></div>

    <!-- Sales Table -->
    <div class="page">

        <div class="brand-div">
            <h2 class="brand-name"><?=strtoupper($config["business_name"])?></h2>
            <small class="brand-details"><?=ucwords($config["store_address"])?></small>
            <small class="brand-details"><?=ucwords($config["contact_no"])?></small>
        </div>

        <hr>

        <?php
            $salesHeaderDate = "(".date("F", strtotime("2000-$salesGraphMonth-01"))." ".$salesGraphYear.")";
            if($salesGraphType === 'monthly'){
                $salesHeaderDate = "(".$salesGraphYear.")";
            }
            elseif($salesGraphType === 'yearly'){
                $salesHeaderDate = "";
            }
        ?>
        <h3 class="reports-header text-medium"><?=ucwords($salesGraphType)?> Report <?=$salesHeaderDate?></h3>

        <br>

        <table id="salesTable">
            <thead>
                    <th></th>
                    <th>DATE</th>
                    <th>TOTAL ORDERS</th>
                    <th>PRODUCTS SOLD</th>
                    <th>TOTAL SALES</th>
                
            </thead>
            <tbody>
                
            </tbody>

        </table>
        
        <div class="issued-div">
            <small class="text-muted">Reports issued by:</small><br>
            <span class="text-medium"><?=ucwords($userInfo["first_name"])?> <?=ucwords($userInfo["last_name"])?></span><br>
            <small class="text-muted"><?=date("F d, Y")?></small><br>
            <small class="text-muted"><?=date("h:i a")?></small>
        </div>

    </div>


    <div class="hide-print" style="height: 100px;"><hr></div>

    <!-- Products Sold Table -->
    <div class="page">

        <div class="brand-div">
            <h2 class="brand-name"><?=strtoupper($config["business_name"])?></h2>
            <small class="brand-details"><?=ucwords($config["store_address"])?></small>
            <small class="brand-details"><?=ucwords($config["contact_no"])?></small>
        </div>

        <hr>

        <?php
            $salesHeaderDate = "(".date("F", strtotime("2000-$salesGraphMonth-01"))." ".$salesGraphYear.")";
            if($salesGraphType === 'monthly'){
                $salesHeaderDate = "(".$salesGraphYear.")";
            }
            elseif($salesGraphType === 'yearly'){
                $salesHeaderDate = "";
            }
        ?>
        <h3 class="reports-header text-medium">Sold Products</h3>

        <br>

        <table id="soldProductsTable">
            <thead>
                <th></th>
                <th>PRODUCT</th>
                <th>QUANTITY</th>
                <th>TOTAL</th>
                <th>DATE</th>
            </thead>
            <tbody>
                
            </tbody>

        </table>
        
        <div class="issued-div">
            <small class="text-muted">Reports issued by:</small><br>
            <span class="text-medium"><?=ucwords($userInfo["first_name"])?> <?=ucwords($userInfo["last_name"])?></span><br>
            <small class="text-muted"><?=date("F d, Y")?></small><br>
            <small class="text-muted"><?=date("h:i a")?></small>
        </div>
        
    </div>

    <div class="hide-print" style="height: 100px;"><hr></div>

    <!-- Category Sales Table -->
    <div class="page">
        
    
        <div class="brand-div">
            <h2 class="brand-name"><?=strtoupper($config["business_name"])?></h2>
            <small class="brand-details"><?=ucwords($config["store_address"])?></small>
            <small class="brand-details"><?=ucwords($config["contact_no"])?></small>
        </div>

        <hr>
        

        <?php
            $salesHeaderDate = "(".date("F", strtotime("2000-$salesGraphMonth-01"))." ".$salesGraphYear.")";
            if($salesGraphType === 'monthly'){
                $salesHeaderDate = "(".$salesGraphYear.")";
            }
            elseif($salesGraphType === 'yearly'){
                $salesHeaderDate = "";
            }
        ?>
        
        <h3 class="reports-header text-medium">Sales Per Categories</h3>

        <br>

        <table id="categorySalesTable">
            <thead>
                    <th></th>
                    <th>CATEGORY</th>
                    <th>TOTAL ORDERS</th>
                    <th>PRODUCTS SOLD</th>
                    <th>TOTAL SALES</th>
                
            </thead>
            <tbody>
                
            </tbody>

        </table>
        
        <div class="issued-div">
            <small class="text-muted">Reports issued by:</small><br>
            <span class="text-medium"><?=ucwords($userInfo["first_name"])?> <?=ucwords($userInfo["last_name"])?></span><br>
            <small class="text-muted"><?=date("F d, Y")?></small><br>
            <small class="text-muted"><?=date("h:i a")?></small>
        </div>
        
    </div>

    <div class="hide-print" style="height: 100px;"><hr></div>

    <!-- Product Sales Table -->
    <div class="page">

        <div class="brand-div">
            <h2 class="brand-name"><?=strtoupper($config["business_name"])?></h2>
            <small class="brand-details"><?=ucwords($config["store_address"])?></small>
            <small class="brand-details"><?=ucwords($config["contact_no"])?></small>
        </div>

        <hr>
        

        <?php
            $salesHeaderDate = "(".date("F", strtotime("2000-$salesGraphMonth-01"))." ".$salesGraphYear.")";
            if($salesGraphType === 'monthly'){
                $salesHeaderDate = "(".$salesGraphYear.")";
            }
            elseif($salesGraphType === 'yearly'){
                $salesHeaderDate = "";
            }
        ?>
        
        <h3 class="reports-header text-medium">Sales Per Products</h3>

        <br>

        <table id="productSalesTable">
            <thead>
                    <th></th>
                    <th>PRODUCT</th>
                    <th>TOTAL ORDERS</th>
                    <th>PRODUCTS SOLD</th>
                    <th>TOTAL SALES</th>
                
            </thead>
            <tbody>
                
            </tbody>

        </table>
        
        <div class="issued-div">
            <small class="text-muted">Reports issued by:</small><br>
            <span class="text-medium"><?=ucwords($userInfo["first_name"])?> <?=ucwords($userInfo["last_name"])?></span><br>
            <small class="text-muted"><?=date("F d, Y")?></small><br>
            <small class="text-muted"><?=date("h:i a")?></small>
        </div>
        
    </div>


    <script src="./js/main.js?t=<?=time()?>"></script>
    <script>

        function generateProductSalesTable(){
            $.ajax({
                url: "fetch/getReports.php",
                type: 'GET',
                data: {
                    request: 'top_products'
                    
                },
                dataType: "json",
                success: function(data){

                    var product = data.map(row => row.product);
                    var totalOrders = data.map(row => row.total_orders);
                    var productsSold = data.map(row => row.products_sold);
                    var totalSales = data.map(row => row.total_sales);
                    
                    
                    for(x = 0; x < totalSales.length; x++){
                        $("#productSalesTable tbody").append(`
                            <tr>
                                <th class="text-center">${(x+1)}</th>
                                <td class="text-center">${product[x]}</td>
                                <td class="text-center">${numcomma(totalOrders[x])}</td>
                                <td class="text-center">${numcomma(productsSold[x])}</td>
                                <td class="text-center">₱ ${numformat(totalSales[x])}</td>
                            </tr>
                        `);
                    }
                    
                }
            });
        }

        function generateCategorySalesTable(){
            $.ajax({
                url: "fetch/getReports.php",
                type: 'GET',
                data: {
                    request: 'top_categories'
                    
                },
                dataType: "json",
                success: function(data){

                    var category = data.map(row => row.category);
                    var totalOrders = data.map(row => row.total_orders);
                    var productsSold = data.map(row => row.products_sold);
                    var totalSales = data.map(row => row.total_sales);
                    
                    
                    for(x = 0; x < totalSales.length; x++){
                        $("#categorySalesTable tbody").append(`
                            <tr>
                                <th class="text-center">${(x+1)}</th>
                                <td class="text-center">${category[x]}</td>
                                <td class="text-center">${numcomma(totalOrders[x])}</td>
                                <td class="text-center">${numcomma(productsSold[x])}</td>
                                <td class="text-center">₱ ${numformat(totalSales[x])}</td>
                            </tr>
                        `);
                    }
                    
                }
            });
        }





        function generateSoldProductsTable(){
            $.ajax({
                url: "fetch/getReports.php",
                data: {
                    request: 'sold_products'
                },
                success: function(data){
                    for(prd of data){
                        $("#soldProductsTable tbody").append(`
                            <tr>
                                <th class="text-center">${prd.num}</th>
                                <td class="text-center">${prd.product_name}</td>
                                <td class="text-center">${prd.quantity}</td>
                                <td class="text-center">₱ ${numformat(prd.total)}</td>
                                <td class="text-center">${prd.date}</td>
                            </tr>    
                        `);   
                    }



                }
            });
        }




        function generateSalesTable(){
            var type = '<?=$salesGraphType?>';
            var month = '<?=$salesGraphMonth?>';
            var year = '<?=$salesGraphYear?>';
            $.ajax({
                url: "fetch/getReports.php",
                type: 'GET',
                data: {
                    request: 'sales_data',
                    sales_type: type,
                    month: month,
                    year: year
                },
                dataType: "json",
                success: function(data){

                    var date = data.map(row => row.date);
                    var totalOrders = data.map(row => row.total_orders);
                    var productsSold = data.map(row => row.products_sold);
                    var totalSales = data.map(row => row.total_sales);
                    
                    
                    for(x = 0; x < totalSales.length; x++){
                        $("#salesTable tbody").append(`
                            <tr>
                                <th class="text-center">${(x+1)}</th>
                                <td class="text-center">${date[x]}</td>
                                <td class="text-center">${numcomma(totalOrders[x])}</td>
                                <td class="text-center">${numcomma(productsSold[x])}</td>
                                <td class="text-center">₱ ${numformat(totalSales[x])}</td>
                            </tr>
                        `);
                    }
                    
                }
            });
        }
        

        $(document).ready(function(){
            generateSalesTable();
            generateSoldProductsTable();
            generateCategorySalesTable();
            generateProductSalesTable();
            
        });


        $("#printBtn").click(function(){
            $(".hide-print").css("display", "none");
            window.print();
            $(".hide-print").css("display", "block");
        });
    </script>
</body>
</html>
