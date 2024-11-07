<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(1));

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
    
    <link rel="stylesheet" href="./css/adm/adm-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/adm/adm-config.css?t=<?=time()?>">

    <title>Accounts</title>
</head>
<body>
    <?php
        $module = 'Configurations';
        $subModule = 'Accounts';
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <div class="row mb-4">
                <div class="col">
                    <!--<button onclick="alert(pCount)">pCount</button>-->
                    <h3 class="heading-text text-medium">Manage Accounts</h3>
                </div>
                <div class="col text-right">
                    <button id="save-btn" class="btn btn-primary" data-toggle="modal" data-target="#addAccountModal">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>
            </div>


            <div class="table-div shadow md-hide">
                <table class="table table-border" id="accounts-list">
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="2">USERNAME</th>
                            <th>FULL NAME</th>
                            <th>EMAIL</th>
                            <th>ACCOUNT TYPE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div id="accounts-list-mobile" class="hide md-block">
                
            </div>
            
            <nav class="pagination-div d-flex justify-content-center mt-3" id="accounts-list-pagination" aria-label="...">
                <ul class="pagination">
                    
                </ul>
            </nav>




        </div>
    </div>





    <!-- Add Account Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php" enctype="multipart/form-data">
        <div class="modal fade ressetable-modal" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="mode" value="config_create">
                    
                    <img id="addUser-userImg" class="userImgModal float-right rounded-circle" src="./img/profiles/default_user.png" alt="">
                    <div class="w-50">
                        <label for="addUser-imageFile">Profile Image</label>
                        <input type="file" name="imageFile" id="addUser-imageFile" accept="image/*"  onchange="showImg(this, 'addUser-userImg')">
                    </div><br>
                    
                    <hr>


                    <div class="row mb-3">
                        <div class="col">
                            <label for="addUser-role">Role <span class="text-danger">*</span></label>
                            <select class="form-control" name="role" id="addUser-role">
                                <option value selected hidden>Account Type</option>
                                <option value="1">Administrator</option>
                                <option value="2">Carpenter</option>
                            </select>
                        </div>
                        <div class="col"></div>
                    </div>
                        
                    

                    <div class="row mb-3">
                        <div class="col">
                            <label for="addUser-username">Username<span class="text-danger">*</span></label>
                            <input type="text" name="username" id="addUser-username" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="addUser-email">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" id="addUser-email" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Full Name<span class="text-danger">*</span></label>
                        <div class="d-flex mb-3">
                            <input type="text" name="firstname" id="addUser-firstname" class="form-control" placeholder="First Name" required>
                            <input type="text" name="lastname" id="addUser-lastname" class="form-control" placeholder="Last Name" required>
                            <select class="form-control" name="suffix" id="addUser-suffix" style="width: 100px">
                                <option value="">Suffix</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="addUser-address">Address<span class="text-danger">*</span></label>
                            <input type="text" name="address" id="addUser-address" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="addUser-contact">Contact #<span class="text-danger">*</span></label>
                            <input class="form-control" type="tel" id="addUser-contact" name="contact" placeholder="" pattern="09\d{9}"  required>
                        </div>
                    </div>

                    
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_account">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Change Role Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="changeRoleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Change Account Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <input type="hidden" id="changeRole-userId" name="userId">

                    <label for="">Account Type</label>
                    <select class="form-control" name="role" id="changeRole-role">
                        <option value="1">Administrator</option>
                        <option value="2">Carpenter</option>
                        <option value="3">Customer</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="change_role">Change</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Disable Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="deactivateAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Deactivate Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="deactivateAccount-userId" name="userId">
                    <span>Are you sure you want to deactivate the account?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="action" value="deactivate_account">Disable</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Enable Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="activateAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Activate Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="activateAccount-userId" name="userId">
                    <span>Are you sure you want to activate the account?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="action" value="activate_account">Confirm</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-configAccounts.js?t=<?=time()?>"></script>

    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html>