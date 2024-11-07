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
    <link rel="stylesheet" href="./css/ctr/ctr-landingpage.css?t=<?=time()?>">

    <title>Postrado</title>
</head>
<body>
    <?php
        $module = "Landingpage";
        include('./layouts/ctr/ctr-navbar.php');
    ?>

    <img class="landingpage-bg" src="./img/landingpage-bg1.png" alt="" style="width: 70%; right: 0">
    <img class="landingpage-bg" src="./img/landingpage-bg2.png" alt="" style="bottom: 0;">
    <img class="landingpage-bg" src="./img/landingpage-bg3.png" alt="" style="bottom: -80%; right: 0;">

    <div class="container py-5" id="main-div" style="position: relative">
        
        

        <div class="row py-5">
            <div class="col col-12 col-lg-6 py-5 d-flex flex-column justify-content-center">
                <h4 class="text-dblue">WELCOME TO</h4>
                <h1 class="text-dbrown">POSTRADO</h1>
                <p class="text-justify text-thin text-dblue" style="text-indent: 30px">Specializing in expertly crafted doors, cabinets, and hamba, we take pride in transforming raw wood into durable pieces that enhance your home or business establishment. Each product is meticulously handcrafted by our skilled carpenters, ensuring that every detail meets your highest expectations. Whether you're looking for a custom design or a standard, we're here to bring it to you precision and care.</p>


                <div><a href="./ctr-catalog.php" class="btn btn-dbrown text-medium">Shop Now</a></div>
                
            </div>
            <div class="col col-6 py-5 d-flex justify-content-center">
                <img class=" md-hide" src="./img/postrado_shop_1.png" alt="Postrado Shop" style="width: 100%; border-radius: 5%">
            </div>
        </div>




        <h4 class="landingpage-heading text-center text-medium mb-2">Most Popular</h4>

        <div class="row" id="bestSellingDiv"></div>
        
        <div class="text-center mb-5">
            <a href="./ctr-catalog.php" class="btn btn-dbrown mt-3">Show All Products</a>
        </div>
        

        <div class="row py-5">
            <div class="col col-12 col-lg-6 py-5 d-flex flex-column justify-content-center">
                <h4 class="text-dblue mb-3">About Postrado</h4>
                <small class="text-justify text-thin text-dblue d-block" style="text-indent: 30px">For over 30 years, Postrado Woodworks has been a trusted name in terms of woodworking craftsmanship. With a dedication to quality and attention to detail, we offer a wide range of custom services to bring your vision to life. Whether you're in need of beautifully crafted doors, hamba, or cabinets, we ensure our commitment in delivering products that combine quality with timeless elegance.</small>
                <br>
                <small class="text-justify text-thin text-dblue d-block" style="text-indent: 30px">Open from <b>Monday</b> to <b>Saturday</b>, <b>8 AM</b> to <b>5 PM</b>, ready to assist you with your woodworking needs. Visit us at <b>83V9+5QW, Manila National Rd, Biñan, 4024 Laguna</b>. We are here to ensure your home is furnished with pieces that reflect your unique style and taste. For inquiries, email us at <b>postradowoodworks@gmail.com</b> or call us at <b>09494294811</b>.</small>
                <!--
                <br>
                <small class="text-justify text-thin text-dblue d-block"><b>For inquiries:</b></small>
                <small class="text-justify text-thin text-dblue d-block" style="text-indent: 60px">Email us: <b>Postradowoodworks@gmail.com</b></small>

                <small class="text-justify text-thin text-dblue d-block" style="text-indent: 60px">Call us: <b>09494294811</b></small>
                -->

            </div>
            <div class="col col-5 py-5 d-flex justify-content-center">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class=" md-hide" src="./img/postrado_shop_2.png" alt="Shop Machinery" style="width: 100%; border-radius: 5%">
                        </div>
                        <div class="carousel-item">
                            <img class=" md-hide" src="./img/postrado_shop_3.png" alt="Panel Doors" style="width: 100%; border-radius: 5%">
                        </div>
                        <div class="carousel-item">
                            <img class=" md-hide" src="./img/postrado_shop_4.png" alt="Wood Stocks" style="width: 100%; border-radius: 5%">
                        </div>
                    </div>
                    <!--
                    <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                    -->
                </div>
                
            </div>
        </div>
        
    </div>



    <?php
        include("./layouts/terms-conditions.php");
    ?>

    <script src="js/main.js?t=<?=time()?>"></script>
    <script src="js/ctr/ctr-navbar.js?t=<?=time()?>"></script>
    <script src="js/ctr/ctr-landingpage.js?t=<?=time()?>"></script>

    <?php include("./functions/alert-function.php"); ?>
</body>
</html>