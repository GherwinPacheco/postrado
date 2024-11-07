<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(2));

    $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();

    $id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $type = $conn->query("SELECT type FROM order_items WHERE id = $id")->fetch_assoc()["type"];

    $dataExist = $conn->query("
        SELECT oi.id 
        FROM order_items oi
            INNER JOIN orders ord ON oi.order_id = ord.id
        WHERE 
            oi.id = $id
            AND ord.order_status = 'preparing'
    ")->num_rows > 0;
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
        include("layouts/cpt/cpt-navbar.php");
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">

            <button class="btn btn-blue mb-5" onclick="window.location.href = 'cpt-productionList.php'">
                <i class="fa-solid fa-chevron-left"></i>&emsp;Back
            </button>
            

            <?php if($dataExist){ 
                if($type === 'normal'){
            ?>

                <div class="row" style="height: 80vh">
                    <div class="col col-12 col-lg-6 border-right mb-5">
                        <h3 id="productName" class="text-medium"></h3>
                        <span class="d-block text-muted" id="category" class="text-medium"></span>
                        <hr>
                        <h5 class="text-medium mb-3">Details</h5>

                        <div class="row pl-4">
                            <div class="col col-4 text-muted">Customer:</div>
                            <div class="col col-8" id="customer"></div>

                            <div class="col col-4 text-muted">Deadline:</div>
                            <div class="col col-8" id="deadline"></div>

                            <div class="col col-4 text-muted">Quantity:</div>
                            <div class="col col-8" id="quantity"></div>

                            <div class="col col-4 text-muted">Varnish:</div>
                            <div class="col col-8" id="varnish"></div>

                            <div class="col col-4 text-muted">Woodstain Color:</div>
                            <div class="col col-8" id="color"></div>

                            <div class="col col-12 text-muted mt-3">Description:</div>
                            <div class="col col-12" id="description" style="text-indent: 30px"></div>
                        </div>
     
                        <hr>

                        <h5 class="text-medium mb-3">Product Specifications</h5>
                        <div class="row pl-4" id="specsDiv">
                            
                        </div>



                    </div>
                    <div class="col col-12 col-lg-6">
                        <div class="imagesContainer" id="imageDiv"></div>
                    </div>
                </div>


            <?php } 
                elseif($type === 'custom') {
            ?>
                <div class="row mb-4 border-bottom pb-2">
                    <div class="col col-12 col-lg-6 border-right mb-3">
                        <h3 id="productName" class="text-medium"></h3>
                        <span class="d-block text-muted" id="category"></span>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <span class="d-block  text-medium">Description</span>
                        <span class="d-block text-muted" id="description" style="text-indent: 30px"></span>
                    </div>
                </div>

                <div class="row mb-4 border-bottom pb-2">
                    <div class="col col-12 col-lg-6 mb-3">
                        <div class="row">
                            <div class="col col-6 text-muted">Quantity:</div>
                            <div class="col col-6" id="quantity"></div>

                            <div class="col col-6 text-muted">Varnish:</div>
                            <div class="col col-6" id="varnish"></div>

                            <div class="col col-6 text-muted">Woodstain Color:</div>
                            <div class="col col-6" id="color"></div>

                            <div class="col col-6 text-muted">Wood Type:</div>
                            <div class="col col-6" id="wood"></div>
                            
                            <div class="col col-6 text-muted">Customer:</div>
                            <div class="col col-6" id="customer"></div>

                            <div class="col col-6 text-muted">Deadline:</div>
                            <div class="col col-6" id="deadline"></div>
                        </div>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <span class="d-block text-medium">Custom Order Specifications</span>
                        <div class="row mt-2 pl-3" id="specsDiv">
                            
                        </div>
                    </div>
                </div>


                

                <div class="row">
                    <div class="col col-12 col-lg-6">
                        <p class=" text-medium">Carpenter's Sketch</p>
                        <div class="mb-4 p-3" id="carpenterSketch"></div>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <p class=" text-medium">Reference Images</p>
                        <div class="mb-4 p-3" id="referenceImage"></div>
                    </div>
                </div>
                
            <?php }} else {  ?>
                
                <div class="d-flex flex-column justify-content-center align-items-center" style="height: 80vh">
                    <img src="./img/product_not_found.svg?t=<?=time()?>" alt="" width="200">
                    <br>
                    <h4 class="text-medium">No custom for production Found</h4>
                    <br>
                    <a href="./cpt-productionList.php" class="btn btn-blue">Back</a>
                </div>


            <?php } ?>
        </div>
    </div>
    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>

    <script>
        const id = <?=$id?>;
        $(document).ready(function(){
            $.ajax({
                url: "fetch/getProductions.php",
                data: {
                    request: 'production_data',
                    id: id
                },
                success: function(data){
                    for(item of data){
                        $("#productName").html(item.product_name);
                        $("#category").html(item.category);
                        $("#customer").html(item.customer);
                        $("#deadline").html(item.deadline);
                        $("#quantity").html(`×${item.quantity}`);
                        $("#varnish").html(item.varnish);
                        $("#color").html(item.color);
                        $("#wood").html(item.wood);
                        $("#description").html(item.description);

                        for(sp of item.specs){
                            if(sp.value){
                                $("#specsDiv").append(`
                                    <div class="col col-6 text-muted">- ${sp.specs_name}:</div>
                                    <div class="col col-6">${sp.value}</div>
                                `);
                            }
                            
                        }


                        if(item.type === 'custom'){
                            $(".imagesContainer").css("background-color", "lightgray");
                            for(x = 1; x <= item.sketch_count; x++){
                                $("#carpenterSketch").append(`
                                    <img class="w-100 mb-4 border shadow p-2 rounded-lg" src="./img/custom/${item.product_id}_sketch_${x}.png?t=${time}">
                                `);
                            }

                            for(x = 1; x <= item.image_count; x++){
                                $("#referenceImage").append(`
                                    <img class="w-100 mb-4 border shadow p-2 rounded-lg" src="./img/custom/${item.product_id}_${x}.png?t=${time}">
                                `);
                            }
                        }
                        else{
                            $("#imageDiv").html(`
                                <img class="w-100 mb-2 border shadow p-2 rounded-lg" src="./img/products/${item.product_id}.png?t=${time}">
                            `);
                        }
                    }
                }
            });
        });
    </script>
    
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>