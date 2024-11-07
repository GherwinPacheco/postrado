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
    
    <link rel="stylesheet" href="./css/styles.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/adm/adm-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/cpt/cpt-customProducts.css?t=<?=time()?>">

    <title>Custom Order</title>
</head>
<body>
    <?php
        $module = "Orders";
        $subModule = "CustomRequests";
        $searchBar = true;
        include("layouts/adm/adm-navbar.php");
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">

            <h3 class="heading-text text-medium">List of Custom Requests</h3>

            <div class="customNavsDiv md-hide my-3">
                <button id="btn-admin_pending" class="statusBtn btn btn-dblue border m-2" onclick="setStatus('admin_pending')">Approval for Admin</button>
                <button id="btn-pending" class="statusBtn btn btn-white border m-2" onclick="setStatus('pending')">Approved</button>
                <button id="btn-declined" class="statusBtn btn btn-white border m-2" onclick="setStatus('declined')">Declined</button>
            </div>

            <select class="form-control w-50 hide md-block my-3" id="status" onchange="setStatus(this.value)">
                <option value="admin_pending" selected>Approval for Admin</option>
                <option value="pending">Approved</option>
                <option value="declined">Declined</option>
            </select>

            <div class="table-div shadow md-hide">
                <table class="table table-border md-hide" id="custom-list">
                    <thead>
                        <tr>
                            <th></th>
                            <th>FURNITURE</th>
                            <th>CUSTOMER</th>
                            <th>WOOD</th>
                            <th class="price-col">PRICE</th>
                            <th>VARNISH</th>
                            <th>WOODSTAIN COLOR</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            


            <div id="custom-list-mobile" class="hide md-block">
                
            </div>
            
            <nav class="pagination-div d-flex justify-content-center mt-3" id="custom-list-pagination" aria-label="...">
                <ul class="pagination">
                    
                </ul>
            </nav>

            
        </div>
    </div>

    


    <!-- View Product Modal -->
    <div class="modal fade" id="viewCustomModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Custom Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3" id="viewCustom-carouselDiv">

                </div>
                


                <!--h5 id="viewCustom-productName" class="text-medium">Product Name</h5>
                <span id="viewCustom-category" class="text-secondary">Category</span><!-->

                <hr>

                <small class="text-muted">Description</small>
                <p class="text-justify" id="viewCustom-description"></p>

                <div class="row mb-4">
                    <div class="col">
                        <small class="text-muted">Quantity</small>
                        <br>
                        <span id="viewCustom-quantity"></span>
                    </div>
                    <div class="col">
                        <small class="text-muted">Wood Type</small>
                        <br>
                        <span id="viewCustom-woodType"></span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col">
                        <small class="text-muted">Varnish</small>
                        <br>
                        <span id="viewCustom-varnish"></span>
                    </div>
                    <div class="col">
                        <small class="text-muted">WoodStain Color</small>
                        <br>
                        <span id="viewCustom-color"></span>
                        <br>
                        <span id="viewCustom-colorPrice"></span>
                    </div>
                </div>

                <hr>

                <span class="text-muted">Order Placed on:</span>&emsp;<span id="viewCustom-dateCreated"></span>&emsp;<span id="viewCustom-timeCreated"></span><br>

                <span class="text-muted">Ordered By:</span>&emsp;<span id="viewCustom-addedBy"></span>

                <hr>

                <div id="viewCustom-cancelDetailsDiv">
                    <small class="text-muted">Decline Reason</small>
                    <p class="text-justify" id="viewCustom-cancelDetails"></p>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Approve Custom Modal -->
    <form class="modal-form" method="post" action="actions/customActions.php">
        <div class="modal fade ressetable-modal" id="approveCustomModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Approve Custom Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="customId" id="approveCustom-customId">
                    
                    <p class="text-center">
                        Are you sure you want to approve<br>
                        <b id="approveCustom-productNameText"></b>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="admin_approve">Approve</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Decline Custom Modal -->
    <form class="modal-form" method="post" action="actions/customActions.php">
        <div class="modal fade ressetable-modal" id="declineCustomModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Decline Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="customId" id="declineCustom-customId">

                    <p class="p-3 m-0 text-center">Are you sure you want to decline <b class="text-medium" id="declineOrder-orderIdText">ORD-123</b>?</p>
                    <hr>

                    <label for="">Decline Reason<span class="text-danger">*</span></label>
                    <select class="form-control w-50 my-3" name="selectedReason" id="declineCustom-selectedReason">
                        <?php
                            $result = $conn->query("SELECT * FROM reason_options WHERE archived = 0 AND type = 'admin_decline'");
                            while($row = $result->fetch_assoc()){
                                echo '
                                    <option value="'.$row["reason"].'">'.$row["reason"].'</option>
                                ';
                            }
                        ?>
                        <option value="others">Others</option>
                    </select>
                    
                    <textarea class="form-control" name="cancelDetails" id="declineCustom-cancelDetails"></textarea>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="admin_decline">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    



    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-customRequests.js?t=<?=time()?>"></script>


    
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>