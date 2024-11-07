<?php
    session_start();
    require('./database/connection.php');

    $location = '';
    $action = '';
    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        $email = isset($_POST["email"]) ? $_POST["email"] : '';

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

    <title>Postrados</title>
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
                <img class="mb-3" src="./img/forgot-pass.png" alt="" style="width: 60px; height: 60px">
                <br>
                <p class="mb-5">
                    Setup new password for <br>
                    <span class="text-medium"><?=$email?></span>
                </p>
                
                <form id="forgetPass-form" action="actions/configActions.php" method="post">
                    <input type="hidden" name="email" value="<?=$email?>">

                    <div class="form-cotrol mb-3 text-left">
                        <label for="">New Password</label>
                        <input type="password" 
                            class="form-control" 
                            id="newPassword" 
                            name="newPassword" 
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                            title="Must contain at least one number, one uppercase, one lowercase letter, one special character, and at least 8 or more characters" required>
                    </div>
                    <div class="form-cotrol mb-5 text-left">
                        <label for="">Confirm Password</label>
                        <input type="password" 
                            class="form-control" 
                            id="confirmPassword" 
                            name="confirmPassword" 
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                            title="Must contain at least one number, one uppercase, one lowercase letter, one special character, and at least 8 or more characters" required>
                    </div>

                    <button type="submit" class="btn btn-primary float-right" name="action" value="forget_pass">Change Password</button>
                </form>
            </div>
            

        </div>
    </div>


    <script src="./js/main.js?t=<?=time()?>"></script>
    <script>
        let bypassSubmit = false;

        $("#forgetPass-form").on("submit", function(){
            if (bypassSubmit) {
                return true; // Allow the form to submit if bypassSubmit is true
            }

            event.preventDefault(); // Prevent form submission by default

            var newPass = $("#newPassword").val();
            var confirmPass = $("#confirmPassword").val();
            if(newPass === confirmPass){
                bypassSubmit = true;
            }
            else{
                alertMessage("The password you have entered does not match", 'danger');
            }
        })
    </script>



    <?php include("./functions/alert-function.php"); ?>
</body>
</html>