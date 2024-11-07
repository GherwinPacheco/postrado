<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();

    if($_SERVER["REQUEST_METHOD"] !== 'POST' and !isset($_POST["customId"])){
        header("Location: cpt-customRequests.php");
        exit();
    }

    $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();

    //where clause values
    $customId = isset($_POST["customId"]) ? $_POST["customId"] : '';
    $quantity = $_POST["quantity"];




    $result = $conn->query("
        SELECT cp.*, ct.category_name, cl.color_name, cl.price AS color_price, u.username 
            FROM custom_products cp
                INNER JOIN users u ON cp.added_by = u.id
                LEFT JOIN color cl ON cp.color_id = cl.id
                LEFT JOIN category ct ON cp.category_id = ct.id
        WHERE cp.id = $customId
    ")->fetch_assoc();

    $productName = $result["product_name"];
    $categoryName = $result["category_name"];
    $productPrice = $result["price"];
    $varnish = $result["varnish"];
    $varnishPrice = $varnish == 1 ? $config["varnish_price"] : 0;
    $colorId = $result["color_id"];
    $colorName = $colorId != 0 ? $result["color_name"] : '';
    $colorPrice = $colorId != 0 ? $result["color_price"] : 0;
    $imageCount = $result["image_count"];
    $description = $result["description"];

    $productTotal = ($productPrice + $varnishPrice + $colorPrice) * $quantity;

    $total = $productTotal + $config["deliver_install"];
    $downPaymentAmount = ($config["down_payment"] / 100) * $total;
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
    <link rel="stylesheet" href="./css/cpt/cpt-orderForm.css?t=<?=time()?>">
    <title>Custom Checkout</title>
</head>
<body>
    <?php
        $module = "OrderForm";
        include('./layouts/cpt/cpt-navbar.php');
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">

            <button class="btn btn-blue mb-5" onclick="history.back()">
                <i class="fa-solid fa-chevron-left"></i>&emsp;Back
            </button>
            

            <h3 class="text-medium mb-5">Add Custom Order</h3>

            <form action="./actions/customActions.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="customId" value="<?=$customId?>">
                <input type="hidden" name="quantity" value="<?=$quantity?>">

                <div class="row mb-5" id="orderForm">
                    <div class="col-12 col-lg-7 pb-5 mb-3" id="item-div">

                        <div id="productConceptCarousel" class="carousel slide mx-auto w-75" data-ride="carouse">
                            <div class="carousel-inner">
                                <?php
                                    $isFirst = true;
                                    for($i = 1; $i <= $imageCount; $i++){
                                        $path = './img/custom/'.$customId.'_'.$i.'.png?t='.time();
                                        echo '
                                            <div class="carousel-item '.($isFirst ? 'active' : '').'">
                                                <img src="'.$path.'" class="d-block w-100" alt="...">
                                            </div>
                                        ';

                                        $isFirst = false;
                                    }
                                ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>

                        
                        <div class="row">
                            <div class="col col-7">
                                <h5 class="text-medium"><?=$productName?></h5>
                                <span class="text-muted"><?=$categoryName?></span>
                            </div>
                            <div class="col col-4 text-right text-primary">
                                <h5 class="text-medium">₱ <?=number_format($productPrice, 2)?></h5>
                            </div>
                        </div>
                        
                        

                        <hr>

                        <div class="row mb-2">
                            <div class="col">
                                <small class="text-muted">Quantity</small>
                                <p>×<?=$quantity?></p>
                            </div>
                            <?php
                                if($varnish == 1){
                                    echo '
                                        <div class="col">
                                            <small class="text-muted">Varnish</small><br>
                                            <span>Varnished</span><br>
                                            <small class="text-muted">₱ '.number_format($varnishPrice, 2).'</small>
                                        </div>
                                    ';
                                }

                                if($colorId != 0){
                                    echo '
                                        <div class="col">
                                            <small class="text-muted">Woodstain Color</small><br>
                                            <span>'.$colorName.'<span><br>
                                            <small class="text-muted">₱ '.number_format($colorPrice, 2).'</small>
                                        </div>
                                    ';
                                }
                            ?>
                            
                        </div>

                        <small class="text-muted">Additional Information</small>
                        <p style="text-indent: 30px"><?=$description?></p>


                    </div>
                    <div class="col-12 col-lg-5 rounded p-3 px-2 border-left orderDetails">
                        <small class="text-muted d-block mb-2">Customer Info</small>
                        <span class="text-muted d-block ml-3"><i class="fa-solid fa-user"></i>&emsp;Username</span>
                        <span class="text-muted d-block ml-3"><i class="fa-solid fa-location-dot"></i>&emsp;Block 1 Block 2 Philippines St. Planet Earth</span>
                        <span class="text-muted d-block ml-3"><i class="fa-solid fa-phone"></i>&emsp;09099099909</span>

                        <hr>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="contact" class="text-medium">Down Payment Recieved<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="downPayment" name="downPayment" min="0" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="address" class="text-medium">Completion Date<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="completionDate" id="completionDate" min="<?=date("Y-m-d")?>" required>
                                </div>
                            </div>
                        </div>
                        
                        
                        <hr>
                        
                        <div class="orderDetailsDiv pb-5" style="position: relative; height: 150px">
                            <div class="row">
                                <input type="hidden" id="merchandiseTotal" value="<?=$productTotal?>">
                                <div class="col col-8">
                                    <span class="">Custom Furniture Price:</span>&nbsp;<span class="text-muted" id="item-count"></span>
                                </div>
                                <div class="col col-4 text-right">
                                    <span class="text-medium" id="merchandiseTotalText">₱ <?=number_format($productTotal, 2)?></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col col-8">
                                    <span>Delivery + Install:</span>
                                </div>
                                <div class="col col-4 text-right">
                                    <span class="text-medium">₱ <?=number_format($config["deliver_install"], 2)?></span>
                                </div>
                            </div>

                            <div class="w-100" style="position: absolute; bottom: 0">
                                <div class="row">
                                    <div class="col">
                                        <span>Minimum Down Payment:</span>&nbsp;<span class="text-muted"><?=$config["down_payment"]?>%</span>
                                    </div>
                                    <div class="col text-right">
                                        <span class="downPaymentText text-medium">₱ <?=number_format($downPaymentAmount, 2)?></span>
                                    </div>

                                    <div class="col col-12">
                                        <small class="text-muted"> </small><br>
                                         <small class="text-muted"> </small>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                        
                        <hr>

                        <div class="d-flex align-items-center justify-content-end">
                            <h5 class="totalText text-medium m-0 mr-3 p-0 text-success">₱ <?=number_format($total, 2)?></h5>
                            <button type="submit" class="btn btn-dblue" name="action" value="add_custom_order">Checkout</button>
                        </div>
                        
                    </div>
                </div>
            </form>




        </div>
    </div>

    



    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>