<?php
    session_start();
    require('./database/connection.php');

    $location = '';
    $action = '';
    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        $username = isset($_POST["username"]) ? $_POST["username"] : '';
        $email = isset($_POST["email"]) ? $_POST["email"] : '';
        $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : '';
        $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : '';
        $suffix = isset($_POST["suffix"]) ? $_POST["suffix"] : '';
        $address = isset($_POST["address"]) ? $_POST["address"] : '';
        $contact = isset($_POST["contact"]) ? $_POST["contact"] : '';
        $password = isset($_POST["password"]) ? $_POST["password"] : '';
        $mode = isset($_POST["mode"]) ? $_POST["mode"] : '';

        $location = $mode === 'login_create' ? 'actions/configActions.php' : 'forget-password.php';
        $action = $mode === 'login_create' ? 'add_account' : 'forgot_password';

        if($mode === 'forget_pass'){
            $emailExist = $conn->query("SELECT email FROM users WHERE email = '$email'")->num_rows > 0;

            if(!$emailExist){
                $_SESSION["message"] = 'The email you have entered is not registered';
                $_SESSION["res"] = 'danger';

                header("Location: login.php");
                exit();
            }
        }



    }
    else{
        $_SESSION["message"] = 'You do not have permission to view this page';
        $_SESSION["res"] = 'danger';

        $location = $_SERVER["HTTP_REFERER"];
        header("Location: $location");
        exit();
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


    <!--Fontawesome-->
    <script src="https://kit.fontawesome.com/2487355853.js" crossorigin="anonymous"></script>

    <!--CSS-->
    <link rel="stylesheet" href="./css/animations.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/styles.css?t=<?=time()?>">
    
    <link rel="stylesheet" href="./css/ctr/ctr-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/ctr/ctr-catalog.css?t=<?=time()?>">

    <title>OTP</title>
</head>
<style>
    body{
        background-image: url('./img/login-page-bg.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center; 
    }
</style>
<body>
    
    <div id="main-div" style="position: relative">
        <br>
        <br>
        <?php include("./layouts/alert-message.php"); ?>
        <div class="container d-flex align-items-center justify-content-center flex-column" style="height: 100vh">
        
            <div class="p-5 mx-3 shadow bg-white text-center">
                <img class="mb-3" src="./img/otp.png" alt="" style="width: 100px; height: 100px">
                <br>
                <p>A verification code is sent to <br><span class="text-medium"><?=$email?></span> to verify that its your email</p>
                <input type="number" class="form-control text-center w-50 m-auto" id="otp" required>

                <div class="row mt-3">
                    <div class="col text-left">
                        <button class="btn btn-success" id="resendOtp" onclick="startTimer()">Resend Otp</button>
                        <br>
                        <small class="text-muted" id="timerDisplay"></small>
                    </div>
                    <div class="col text-right">
                        <button class="btn btn-primary" onclick="submitOtp()">Submit</button>
                    </div>
                </div>
            </div>
            

        </div>
    </div>

    <form id="dataForm" action="<?=$location?>" method="post">
        <?php
            if($mode === 'login_create'){
                echo '
                    <input type="hidden" name="username" value="'.$username.'">
                    <input type="hidden" id="email" name="email" value="'.$email.'">
                    <input type="hidden" name="firstname" value="'.$firstname.'">
                    <input type="hidden" name="lastname" value="'.$lastname.'">
                    <input type="hidden" name="suffix" value="'.$suffix.'">
                    <input type="hidden" name="address" value="'.$address.'">
                    <input type="hidden" name="contact" value="'.$contact.'">
                    <input type="hidden" name="password" value="'.$password.'">
                    <input type="hidden" name="mode" value="'.$mode.'">
                    <input type="hidden" name="action" value="'.$action.'">
                ';
            }
            else{
                echo '
                    <input type="hidden" id="email" name="email" value="'.$email.'">
                ';
            }
        ?>
    </form>

    


    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/otp-page.js?t=<?=time()?>"></script>



    <?php include("./functions/alert-function.php"); ?>
</body>
</html>