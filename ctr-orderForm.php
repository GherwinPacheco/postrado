<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();

    if($_SERVER["REQUEST_METHOD"] !== 'POST' and !isset($_POST["cartId"])){
        header("Location: ctr-cart.php");
        exit();
    }

    //where clause values
    $cartId = (isset($_POST["cartId"]) and $_POST["cartId"] !== '0') ? $_POST["cartId"] : null;
    $user = $_SESSION["user"];
    $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();

    $userInfo = $conn->query("SELECT * FROM users WHERE id = ".$_SESSION["user"])->fetch_assoc();
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
    <link rel="stylesheet" href="./css/ctr/ctr-orderForm.css?t=<?=time()?>">
    <title>Checkout</title>
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
            

            <h3 class="text-medium mb-5">Checkout</h3>

            <form action="./actions/orderActions.php" method="post" enctype="multipart/form-data">
                <div class="row mb-5" id="orderForm">
                    <div class="col-12 col-lg-7">
                        <div class="mx-1" id="cartList">

                        </div>


                    </div>
                    <div class="col-12 col-lg-5 rounded p-3 px-2 border-left">
                        
                        <div class="form-group">
                            <label for="address" class="text-medium">Complete Address<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="address" id="address" value="<?=$userInfo["home_address"]?>" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="contact" class="text-medium">Contact<span class="text-danger">*</span></label>
                                    <input type="text" pattern="^(09|\+639)\d{9}$" class="form-control" id="contact" name="contact" value="<?=$userInfo["contact"]?>" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="address" class="text-medium">Target Date<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="completionDate" id="completionDate" min="<?=date("Y-m-d")?>" required>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted"><b>Reminder:</b> The production might take <b id="productionDuration"></b></small><p>
                        <small class="text-muted"> <b>-</b> Consider to align the estimated production duration in selecting target date</small>


                        
                        
                        <hr>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="contact" class="text-medium">Payment Method<span class="text-danger">*</span></label>
                                    <select class="form-control" name="paymentMethod" id="paymentMethod" required>
                                        <option value="cash">Walk-in</option>
                                        <option value="gcash">GCash</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    
                                    <label for="contact" class="text-medium">Service Method<span class="text-danger">*</span></label>&nbsp;
                                    <small class="text-muted" class="pickupPriceText"></small>
                                    
                                    <input type="hidden" name="pickupMethod" id="pickupMethod" value="for pickup" required>
                                    <select class="form-control" name="pickupOptionPrice" id="pickupOptionPrice" required>
                                        <option value="0">For Pickup</option>
                                        <option value="<?=$config['for_deliver']?>">For Delivery</option>
                                        <option value="<?=$config['for_install']?>">For Installation</option>
                                        <option value="<?=$config['deliver_install']?>">Deliver and Install</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-center py-3" id="paymentInstructionDiv">
                            <a href="<?=$config["location_link"]?>"><i class="fa-solid fa-location-dot"></i></a>
                            <h6 class="text-medium m-0"><?=$config["store_address"]?></h6>
                            <small class="text-muted">Walk-in down payment should be settled to the store</small>
                        </div>


                        <hr>
                        
                        <div class="orderDetailsDiv pb-5" style="position: relative;">
                            <div class="row">
                                <input type="hidden" id="merchandiseTotal" value="0">
                                <div class="col col-8">
                                    <span class="">Order Total:</span>&nbsp;<span class="text-muted" id="item-count"></span>
                                </div>
                                <div class="col col-4 text-right">
                                    <span class="text-medium" id="merchandiseTotalText"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col col-8">
                                    <span class="pickupMethodText"></span>
                                </div>
                                <div class="col col-4 text-right">
                                    <span class="pickupPriceText text-medium"></span>
                                </div>
                            </div>

                            <div class="w-100 mt-3">
                                <div class="row">
                                    <div class="col">
                                        <span>Down Payment:</span>&nbsp;<span class="text-muted"><?=$config["down_payment"]?>%</span>
                                    </div>
                                    <div class="col text-right">
                                        <span class="downPaymentText text-medium"></span>
                                    </div>


                                    <div class="col col-12">
                                        <small class="text-muted"><b>Note:</b></small>
                                        <br>
                                        <small class="text-muted"><b>-</b> Down payment must be paid within working hours to approve the order.</small><p>
                                        <small class="text-muted"><b>-</b> Failing to settle down payment will auto decline the order.</small>
                                    </div>
                                </div>

                                
                            </div>
                            
                        </div>

                        
                        <hr>

                        <div class="d-flex align-items-center justify-content-end">
                            <h5 class="totalText text-medium m-0 mr-3 p-0 text-success"></h5>
                            <button type="submit" class="btn btn-dblue" name="action" value="add_order">Place Order</button>
                        </div>
                        
                    </div>
                </div>
            </form>




        </div>
    </div>

    
    <?php
        include("./layouts/terms-conditions.php");
    ?>


    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-navbar.js?t=<?=time()?>"></script>
    <script>
        $(document).ready(function(){
            var noOfDays = 0;

            $.ajax({
                url: "./fetch/getCart.php",
                type: "GET",
                data: {
                    request: 'carts_data',
                    cart_id: '<?=json_encode($_POST["cartId"])?>',
                    orderBy: 'date_created',
                    orderMethod: 'DESC'
                },
                dataType: "json",
                success: function (data){
                    var merchandiseTotal = 0;
                    for(cart of data){

                        var variant = cart.product_variants.length > 1 ? `
                            <span class="text-muted ">Variant: ${cart.variant_name}</span><br>
                        ` : '';

                        var varnish = cart.varnish === '1' ? `
                            <span class="text-muted d-inline-block">Varnished&emsp;</span>
                        ` : ''; 

                        var color = cart.selected_color !== '0' ? `
                            <span class="text-muted d-inline-block">Woodstain Color: ${cart.color_name}</span>
                        ` : ''; 

                        $("#cartList").append(`
                            <div class="row border-bottom mb-2">
                                <input type="hidden" name="cartId[]" value="${cart.id}"></input>

                                <div class="col col-3 d-flex align-items-center">
                                    <img class="border rounded" src="./img/products/${cart.product_id}.png?t=${time}" alt="" style="width: 80px; heght: 80px">
                                </div>
                                <div class="col col-9 d-flex align-items-center">
                                    <div>
                                        <h6 class="text-medium p-0 m-0 d-inline-block mr-5">${cart.product_name}</h6>
                                        <span class="text-muted">₱ ${numformat(cart.variant_price)}</span><br>
                                        ${variant}
                                        <span class="text-muted">Qty:&emsp;${cart.quantity}</span><br>
                                        ${varnish}
                                        ${color}
                                        
                                    </div>
                                    
                                </div>
                                <div class="col col-12">
                                    <h6 class="text-medium text-right text-primary mt-3">₱ ${numformat(cart.total)}</h6>
                                </div>
                            </div>
                        `);

                        merchandiseTotal = merchandiseTotal + parseFloat(cart.total);

                        $("#productionList").append(`
                            <li><small class="text-muted d-block">${cart.product_name}:&emsp;<b>${formatDays(cart.quantity * cart.production_duration)}</b></small></li>
                        `);

                        noOfDays = noOfDays + (cart.quantity * cart.production_duration);
                    }

                    $("#item-count").html(`(${data.length}) item${data.length > 1 ? 's' : ''}`);
                    $("#merchandiseTotalText").html(`₱ ${numformat(merchandiseTotal)}`);

                    $("#merchandiseTotal").val(merchandiseTotal);

                    updateTotal();



                    
                    $("#productionDuration").html(formatDays(noOfDays));
                }
            });
            
        });


        $("#pickupOptionPrice").change(function(){
            var selectedOption = $(this).prop('selectedIndex') + 1;
            
            var pickupPrice = $(this).val();
            switch(selectedOption){
                case 1:
                    $("#pickupMethod").val('for pickup');
                    $(".pickupPriceText").html('');
                    break;
                case 2:
                    $("#pickupMethod").val('for deliver');
                    $(".pickupPriceText").html('');
                    break;
                case 3:
                    $("#pickupMethod").val('for installation');
                    $(".pickupPriceText").html('');
                    break;
                case 4:
                    $("#pickupMethod").val('deliver and install');
                    $(".pickupPriceText").html('');
                    break;
            }
            
            if(selectedOption !== 1){
                $(".pickupMethodText").html(`Service Fee:`);
                $(".pickupPriceText").html(`₱ ${numformat(pickupPrice)}`);
            }
            else{
                $(".pickupMethodText").html('');
            }

            updateTotal();
        });

        $("#paymentMethod").change(function(){
            if($(this).val() === 'cash'){
                $("#paymentInstructionDiv").html(`
                    <a href="<?=$config["location_link"]?>"><i class="fa-solid fa-location-dot"></i></a>
                    <h6 class="text-medium m-0"><?=$config["store_address"]?></h6>
                    <small class="text-muted">Walk-in down payment should be settled to the store</small>
                `);
            }
            else{
                $("#paymentInstructionDiv").html(`
                    <img class="mb-1" src="./img/gcash.png" style="height: 30px">
                    <h6 class="text-medium m-0 mt-2 text-primary"><?=strtoupper($config["gcash_name"])?></h6>
                    <h6 class=" mb-2"><?=$config["gcash_number"]?></h6>

                    <img class="d-block mx-auto" src="./img/gcash_qr.png?t=${time}" style="width: 80%">
                    <small class="text-muted">Scan the QR code to send down payment</small>

                    <hr>

                    <div class="row text-left">
                        <div class="col">
                            <label for="">Payment Screenshot:</label>
                            <input type="file" name="paymentScreenshot" accept="image/*" onchange="showImg(this, 'paymentImg')"><br>
                            <small class="text-muted" id="paymentNote">Note: Can be done after placing order</small>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <img id="paymentImg" src="./img/payments/default.svg" alt="" style="width: 70px">
                        </div>
                    </div>
                `);
            }
        });




        function updateTotal(){
            var merchandiseTotal = parseFloat($("#merchandiseTotal").val());
            var pickupPrice = parseFloat($("#pickupOptionPrice").val());

            var downPercent = <?=$config["down_payment"]?>

            var total = merchandiseTotal + pickupPrice;
            $(".totalText").html(`₱ ${numformat(total)}`);

            var downPayment = (downPercent / 100) * total;
            $(".downPaymentText").html(`₱ ${numformat(downPayment)}`);
        }



        function formatDays(noOfDays) {
            const daysInYear = 365.25; // Average days per year, accounting for leap years
            const daysInMonth = 30.44; // Average days per month

            const years = Math.floor(noOfDays / daysInYear);
            const remainingDaysAfterYears = noOfDays % daysInYear;

            const months = Math.floor(remainingDaysAfterYears / daysInMonth);
            const remainingDaysAfterMonths = remainingDaysAfterYears % daysInMonth;

            const weeks = Math.floor(remainingDaysAfterMonths / 7);
            const days = Math.floor(remainingDaysAfterMonths % 7);

            let result = "";

            if (years > 0) {
                result += years + " year" + (years > 1 ? "s" : "");
            }

            if (months > 0) {
                if (years > 0) {
                    result += " ";
                }
                result += months + " month" + (months > 1 ? "s" : "");
            }

            if (weeks > 0) {
                if (years > 0 || months > 0) {
                    result += " ";
                }
                result += weeks + " week" + (weeks > 1 ? "s" : "");
            }

            if (days > 0) {
                if (years > 0 || months > 0 || weeks > 0) {
                    result += " and ";
                }
                result += days + " day" + (days > 1 ? "s" : "");
            }

            return result || "0 days"; // Handle the case where noOfDays is 0
        }
    </script>
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>