<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();

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
    <link rel="stylesheet" href="./css/ctr/ctr-orders.css?t=<?=time()?>">

    <title>Order List</title>
</head>
<body>
    <?php
        $module = "Catalog";
        $searchBar = true;
        include('./layouts/ctr/ctr-navbar.php');
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">
            
            <button class="btn btn-blue mb-5" onclick="history.back()">
                <i class="fa-solid fa-chevron-left"></i>&emsp;Back
            </button>
            
            <h3 class="text-medium">My Orders</h3>
            

            <div class="orderNavsDiv md-hide my-3">
                <button id="btn-pending" class="statusBtn btn btn-dblue border m-2" onclick="setStatus('pending')">For Approval</button>
                <button id="btn-preparing" class="statusBtn btn btn-white border m-2" onclick="setStatus('preparing')">In Production</button>
                <button id="btn-ready" class="statusBtn btn btn-white border m-2" onclick="setStatus('ready')">For Pickup/Delivery</button>
                <button id="btn-complete" class="statusBtn btn btn-white border m-2" onclick="setStatus('complete')">Order Completed</button>
                <button id="btn-declined" class="statusBtn btn btn-white border m-2" onclick="setStatus('declined')">Declined</button>
                <button id="btn-cancelled" class="statusBtn btn btn-white border m-2" onclick="setStatus('cancelled')">Cancelled</button>
            </div>

            <select class="form-control w-50 hide md-block my-3" id="status" onchange="setStatus(this.value)">
                <option value="pending">For Approval</option>
                <option value="preparing">In Production</option>
                <option value="ready">Ready for Pickup/Delivery</option>
                <option value="complete">Completed</option>
                <option value="declined">Declined</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <table class="table table-border md-hide" id="orders-list">
                <thead>
                    <tr>
                        <th></th>
                        <th>ORDER NO.</th>
                        <th>PAYMENT</th>
                        <th>SERVICE METHOD</th>
                        <th id="payment-header">DOWN PAYMENT</th>
                        <th id="total-header">TOTAL</th>
                        <th id="deadline-header">TARGET DATE</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <div id="orders-list-mobile" class="hide md-block">
                
            </div>
            
            <nav class="pagination-div d-flex justify-content-center mt-3" id="orders-list-pagination" aria-label="...">
                <ul class="pagination">
                    
                </ul>
            </nav>

            


        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade ressetable-modal" id="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-medium d-inline-block m-0 p-0 mr-3" id="viewDetails-orderId">ORD-123</h5>
                <span class="text-medium text-info d-inline-block mb-2" id="viewDetails-status">Pending</span>
                <br>
                <span class="text-muted" id="viewDetails-deadlineText"></span>:&emsp;<span id="viewDetails-deadline"></span>
                <hr>

                <div class="row">
                    <div class="col col-6">
                        <small class="text-muted d-block">Customer</small>
                        <span id="viewDetails-username"></span>
                    </div>
                    <div class="col col-6">
                        <small class="text-muted d-block">Contact</small>
                        <span id="viewDetails-contact"></span>
                    </div>
                    <div class="col col-12 mt-2">
                        <small class="text-muted d-block">Address</small>
                        <span id="viewDetails-homeAddress"></span>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <div class="col col-6">
                        <small class="text-muted d-block">Payment Method</small>
                        <span id="viewDetails-paymentMethod"></span>
                    </div>
                    <div class="col col-6">
                        <small class="text-muted d-block">Service Method</small>
                        <span id="viewDetails-pickupMethod"></span>
                    </div>
                </div>

                <hr>

                <small class="text-muted">Ordered Items</small>
                <ol id="viewDetails-itemList">

                </ol>

                <hr>

                <div class="row">
                    <div class="col col-6">
                        <small class="text-muted d-block" id="payment-header-modal">Settled Down Payment</small>
                        <span id="viewDetails-paidAmount"></span>
                    </div>
                    <div class="col col-6">
                        <small class="text-muted d-block" id="total-header-mobile"></small>
                        <span id="viewDetails-total"></span>
                    </div>
                </div>

                <hr>

                <small class="text-muted" id="viewDetails-cancelText"></small>
                <br>
                <p id="viewDetails-reason" style="text-indent: 30px"></p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Upload Payment Screenshot -->
    <form class="modal-form" method="post" action="actions/orderActions.php" enctype="multipart/form-data">
        <div class="modal fade ressetable-modal" id="uploadScreenshotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Upload Payment Screenshot</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="orderId" id="uploadScreenshot-orderId">
                    
                    <label for="">Screenshot File</label>
                    <input type="file" id="uploadScreenshot-paymentScreenshot" name="paymentScreenshot" accept="image/*" onchange="showImg(this)">

                    <img class="mt-3" src="" id="uploadScreenshot-screenshotImg" alt="" style="width: 100%">
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="upload_screenshot">Upload</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <!-- View Screenshot Modal -->
    <div class="modal fade ressetable-modal" id="viewScreenshotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">View Payment Screenshot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center text-medium pb-3">
                
                <img src="" id="viewScreenshot-screenshotImg" alt="No payment screenshot has beed uploaded" style="width: 100%">
                
                
            </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <form class="modal-form" method="post" action="actions/orderActions.php">
        <div class="modal fade ressetable-modal" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Cancel Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="orderId" id="cancelOrder-orderId">

                    <p class="p-3 m-0 text-center">Are you sure you want to cancel <b class="text-medium" id="cancelOrder-orderIdText">ORD-123</b>?</p>
                    <hr>

                    <label for="">Cancel Reason<span class="text-danger">*</span></label>
                    <select class="form-control w-50 my-3" name="selectedReason" id="cancelOrder-selectedReason">
                        <?php
                            $result = $conn->query("SELECT * FROM reason_options WHERE archived = 0 AND type = 'customer_cancel'");
                            while($row = $result->fetch_assoc()){
                                echo '
                                    <option value="'.$row["reason"].'">'.$row["reason"].'</option>
                                ';
                            }
                        ?>
                        <option value="others">Others</option>
                    </select>

                    <label for="">Cancelling Reasons <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="cancelDetails" id="cancelOrder-cancelDetails" required></textarea>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="cancel_order">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    

    <?php
        include("./layouts/terms-conditions.php");
    ?>

    
    <script>
        var statusGET = '<?=isset($_GET["status"]) ? $_GET["status"] : 'pending'?>';
    </script>
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-navbar.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-orders.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>