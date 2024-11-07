<?php
    session_start();
    require("./database/connection.php");
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
    <link rel="stylesheet" href="./css/ctr/ctr-catalog.css?t=<?=time()?>">

    <title>Login</title>
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
        
            <div class="row mx-3 shadow bg-white">
                <div class="col col-12 col-lg-6 p-5">
                    <a href="./ctr-landingpage.php" class="btn btn-blue mb-4">
                        <i class="fa-solid fa-chevron-left"></i>&emsp;Back
                    </a>
                    
                    <h4 class="text-dbrown">POSTRADO</h4>

                    <br>
                    
                    <h4 class="text-medium mb-3">Login</h4>
                    <form action="./login-validate.php" method="post">
                        <label for="">Username</label>
                        <input type="text" name="username" class="form-control mb-2" required>

                        

                        <label for="">Password</label>
                        <input id="login-password" type="password" name="password" class="form-control" required>

                        <input type="checkbox" class="mb-2" onclick="showPassword('login-password')">&emsp;Show Password

                        <a role="button" class="d-block text-blue" data-toggle="modal" data-target="#forgotPasswordModal">Forgot Password?</a>

                        <br>
                        <button type="submit" class="btn btn-dblue form-control">Login</button>
                        <small class="d-block text-center text-muted my-2">or</small>
                        <a href="./create-account.php" class="btn btn-brown form-control text-medium">Create Account</a>
                    </form>

                    <small class="text-muted d-block text-center mt-4">By logging in, you agree <br> to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a> of Postrado</small>
                    
                </div>
                <div class="col col-12 col-lg-6 md-hide" style="overflow: hidden">
                    <img src="./img/login-bg.png" alt="" height="680">
                </div>
            </div>
        </div>
    </div>




    <?php
        include("./layouts/terms-conditions.php");
    ?>

    


    <!-- Forget Password Modal -->
    <form id="forgetPass-form" class="modal-form" method="post" action="otp-page.php">
        <div class="modal fade ressetable-modal" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Forget Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="mode" value="forget_pass">
                    
                    <div class="form-group mb-4">
                        <label for="">Email</label>
                        <input type="email" class="form-control" id="forgetPass-email" name="email" required>
                        <small id="email-error" class="text-danger"></small>
                    </div>

                    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="forgotPass-emailSubmit" class="btn btn-primary">Proceed</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    


    <script src="./js/main.js?t=<?=time()?>"></script>

    <script>


        let bypassSubmit = false;

        $("#forgetPass-form").on("submit", function(){
            
            if (bypassSubmit) {
                return true; // Allow the form to submit if bypassSubmit is true
            }
    
            event.preventDefault(); // Prevent form submission by default

            var email = $("#forgetPass-email").val();
            checkEmail(email)
                .then(hasData => {
                    if( hasData && email !== '' ){
                        newCode(email)
                            .then(function(data) {
                                
                                bypassSubmit = true; // Set the flag to true to bypass preventDefault on the next submit
                                $('#forgetPass-form').submit(); // Trigger form submission
                            })
                            .catch(function(error) {
                                alertMessage('Failed to send OTP to the email', 'danger');
                            });
                    }
                    else{
                        alertMessage("The email you have entered is not registered on the website", 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Handle error here
                });
            
        })
    </script>

    <?php include("./functions/alert-function.php"); ?>
</body>
</html>