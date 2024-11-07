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

    <title>General Settings</title>
</head>
<body>
    <?php
        $module = 'Configurations';
        $subModule = 'General';
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <div class="row mb-4">
                <div class="col">
                    <!--<button onclick="alert(pCount)">pCount</button>-->
                    <h3 class="heading-text text-medium">General Settings</h3>
                </div>
                <div class="col text-right">
                    <button id="save-btn" class="btn btn-primary hide" data-toggle="modal" data-target="#confirmationModal">Save Changes</button>
                </div>
            </div>




            <form id="config-form" action="./actions/configActions.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_config">
                <div class="row" style="height: 70vh">

                    <!-- Column for Store Details and Mailing Details -->
                    <div class="col col-12 col-lg-6 border-right" id="store-mailer-details">

                        <small class="d-block text-muted border-bottom pb-1 mb-4">Shop Details</small>

                        <!--
                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="businessName">Store Name</label>
                                <small class="text-muted">The name of the store/business</small>
                            </div>
                            <input type="text" class="form-control" id="businessName" name="businessName" value="<?=$config["business_name"]?>" required>
                        </div>
                        -->

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="storeAddress">Shop Address</label>
                                <small class="text-muted">The address of the physical shop</small>
                            </div>
                            <input type="text" class="form-control" id="storeAddress" name="storeAddress" value="<?=$config["store_address"]?>" required>
                        </div>

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="contactNo">Contact Number</label>
                                <small class="text-muted">The contact number of shop owner</small>
                            </div>
                            <input type="text" class="form-control" id="contactNo" name="contactNo" value="<?=$config["contact_no"]?>" required>
                        </div>

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="storeLocation">Map Location</label>
                                <small class="text-muted">The google maps link of the shop</small>
                            </div>
                            <input type="text" class="form-control" id="storeLocation" name="storeLocation" value="<?=$config["location_link"]?>" required>
                        </div>
                        
                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="gcashName">GCash Name</label>
                                <small class="text-muted">The GCash account name of shop</small>
                            </div>
                            <input type="text" class="form-control" id="gcashName" name="gcashName" value="<?=$config["gcash_name"]?>" required>
                        </div>

                        <div class="input-container mb-2">
                            <div class="input-label">
                                <label class="text-medium m-0" for="gcashNo">GCash Number</label>
                                <small class="text-muted">The GCash number for receiving down payment</small>
                            </div>
                            <input type="text" class="form-control" id="gcashNo" name="gcashNo" value="<?=$config["gcash_number"]?>" required>
                        </div>


                        <div class="input-container mb-3">
                            <div class="input-label">
                                <label class="text-medium m-0" for="gcashQr">GCash QR Code</label>
                                <small class="text-muted">The QR Code for receiving down payment through GCash</small>
                            </div>
                            <div class="w-50 d-flex flex-column align-items-center">
                                <img id="gcashQrImg" src="./img/gcash_qr.png?t=<?=time()?>" alt="" style="width: 80px; height: 80px">
                                <input type="file" class="form-control border-0" id="gcashQr" name="gcashQr" onchange="showImg(this, 'gcashQrImg')" required>
                            </div>
                            
                        </div>

                        <div class="input-container mb-5">
                            <div class="input-label">
                                <label class="text-medium m-0" for="gcashNo">Shop Policy</label>
                                <small class="text-muted">The shop's terms and conditions</small>
                            </div>
                            <button type="button" class="btn btn-dblue" data-toggle="modal" data-target="#updateTermsModal">Edit</button>
                        </div>

                        





                        <small class="d-block text-muted border-bottom pb-1 mb-4">Mailing Details</small>

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="mailerEmail">Shop Mailer</label>
                                <small class="text-muted">The email address that will be used for sending email</small>
                            </div>
                            <input type="email" class="form-control" id="mailerEmail" name="mailerEmail" value="<?=$config["mailer_email"]?>" required>
                        </div>

                        <div class="input-container mb-5">
                            <div class="input-label">
                                <label class="text-medium m-0" for="mailerPass">Mailer Password</label>
                                <small class="text-muted">App password code generated for the mailer email</small>
                            </div>
                            <input type="password" class="form-control" id="mailerPass" name="mailerPass" value="<?=$config["mailer_pass"]?>" required>
                        </div>


                        

                    </div>

                    <div class="col col-12 col-lg-6" id="ordering-properties">
                        <small class="d-block text-muted border-bottom pb-1 mb-4">Ordering Properties</small>

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="varnishPrice">Varnish Price</label>
                                <small class="text-muted">The price of varnish option on furniture</small>
                            </div>

                            <div class="input-group ml-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">₱</span>
                                </div>
                                <input type="number" class="form-control" id="varnishPrice" name="varnishPrice" value="<?=$config["varnish_price"]?>" required>
                            </div>
                        </div>

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="forDeliver">Delivery Price</label>
                                <small class="text-muted">The price <b>for deliver</b> pickup method in ordering forms</small>
                            </div>
                            
                            <div class="input-group ml-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">₱</span>
                                </div>
                                <input type="number" class="form-control" id="forDeliver" name="forDeliver" value="<?=$config["for_deliver"]?>" min="1" required>
                            </div>
                        </div>

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="forInstall">Installation Price</label>
                                <small class="text-muted">The price <b>for installation</b> pickup method in ordering forms</small>
                            </div>
                            
                            <div class="input-group ml-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">₱</span>
                                </div>
                                <input type="number" class="form-control" id="forInstall" name="forInstall" value="<?=$config["for_install"]?>" min="1" required>
                            </div>
                        </div>

                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="deliverInstall">Delivery + Installation Price</label>
                                <small class="text-muted">The price of <b>deliver and install</b> pickup method in checkout</small>
                            </div>
                            
                            <div class="input-group ml-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">₱</span>
                                </div>
                                <input type="number" class="form-control" id="deliverInstall" name="deliverInstall" value="<?=$config["deliver_install"]?>" min="1" required>
                            </div>
                        </div>


                        <div class="input-container mb-4">
                            <div class="input-label">
                                <label class="text-medium m-0" for="downPayment">Minimum Down Payment</label>
                                <small class="text-muted">The percentage of down payment to be settled</small>
                            </div>
                            
                            <div class="input-group ml-3">
                            <input type="number" class="form-control" id="downPayment" name="downPayment" value="<?=$config["down_payment"]?>" min="1" max="100" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">%</span>
                            </div>
                                
                            </div>
                        </div>

                    </div>


                </div>


            </form>

            






        </div>
    </div>






    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-medium" id="exampleModalLongTitle">Update General Settings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-center py-5">
            Are you sure you want to update the settings?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submitBtn">Confirm</button>
        </div>
        </div>
    </div>
    </div>




    <!-- Update Terms Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="updateTermsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Terms and Condition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    

                    <div class="form-group">
                        <label for="termsMessage"></label>
                        <textarea class="form-control" id="termHeading" name="termHeading" rows="5"><?=$config["terms_heading"]?></textarea>
                    </div>

                    <hr>
                        
                    <small class="d-block text-muted mb-2">Terms and Conditions</small>
                    

                    <ol id="termsList">
                        
                    </ol>
                    <div class="d-block text-center">
                        <button type="button" class="btn btn-dblue" onclick="addTerms()">Add New Policy</button>
                    </div>
                    
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="update_terms">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-configGeneral.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html>