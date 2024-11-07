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

    <title>Register Account</title>
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
        
            <div class="p-5 mx-3 shadow bg-white">
                <h4 class="text-dbrown">POSTRADO</h4>

                <p>

                <h5 class="text-medium mb-4">Create Account</h5>
                <form id="createAccount-form" action="./otp-page.php" method="post">
                    
                    <input type="hidden" name="mode" value="login_create">

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control mb-2" required>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group" style="position: relative">
                                <label for="">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control mb-2" required>
                                <small id="email-error" class="text-danger" style="position: absolute; bottom-0"></small>
                            </div>
                        </div>
                    </div>


                    <div class="form-group mb-3">
                        <label for="">Full Name <span class="text-danger">*</span></label>
                        <div class="d-flex mb-3">
                            <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                            <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                            <select class="form-control" name="suffix" style="width: 100px">
                                <option value="">Suffix</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control mb-2" required>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="">Contact <span class="text-danger">*</span></label>
                                <input type="text" name="contact" pattern="09\d{9}" placeholder="09*********" class="form-control mb-2" required>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="form-group" style="position: relative">
                                <label for="">Password <span class="text-danger">*</span></label>
                                <input type="password" id="create-password"
                                    name="password" 
                                    class="form-control passwordForm" 
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                                    title="Must contain at least one number, one uppercase, one lowercase letter, one special character, and at least 8 or more characters" required>
                                    <small id="password-error" class="text-danger" style="position: absolute; bottom-0"></small>
                                    
                            </div>
                            
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" id="create-password-confirm"
                                    name="confirmPassword" 
                                    class="form-control passwordForm" 
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                                    title="Must contain at least one number, one uppercase, one lowercase letter, one special character, and at least 8 or more characters" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <input type="checkbox" class="mb-2" onclick="showPassword('create-password');showPassword('create-password-confirm')">&emsp;Show Password
                    </div>

                    
                    <div class="d-block text-center mb-4">
                        <button id='register-btn' type="submit" class="btn btn-dblue w-50">Register</button>
                        <small class="d-block text-center text-muted my-2">or</small>
                        <a href="login.php" class="btn btn-brown w-50">Back to Login Page</a>
                    </div>
               
                    

                    <small class="text-muted d-block text-center">By creating account, you agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a> of Postrado</small>
                </form>
            </div>
        </div>
    </div>




    <?php
        include("./layouts/terms-conditions.php");
    ?>

    


    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/create-account.js?t=<?=time()?>"></script>


    <?php include("./functions/alert-function.php"); ?>
</body>
</html>