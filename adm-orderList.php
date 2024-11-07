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
    <link rel="stylesheet" href="./css/adm/adm-orderList.css?t=<?=time()?>">

    <title>Order List</title>
</head>
<body>
    <?php
        $module = 'Orders';
        $subModule = 'OrderList';
        $searchBar = true;
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <!--<button onclick="alert(pCount)">pCount</button>-->
            <h3 class="heading-text text-medium">List of Orders</h3>


            <!-- Table Filters and Add Button -->
            

            <div class="orderNavsDiv md-hide my-3">
                <button id="btn-pending" class="statusBtn btn btn-dblue border m-2" onclick="setStatus('pending')">For Approval</button>
                <button id="btn-preparing" class="statusBtn btn btn-white border m-2" onclick="setStatus('preparing')">In Production</button>
                <button id="btn-ready" class="statusBtn btn btn-white border m-2" onclick="setStatus('ready')">Ready for Pickup/Delivery</button>
                <button id="btn-complete" class="statusBtn btn btn-white border m-2" onclick="setStatus('complete')">Order Completed</button>
                <button id="btn-declined" class="statusBtn btn btn-white border m-2" onclick="setStatus('declined')">Declined</button>
                <button id="btn-cancelled" class="statusBtn btn btn-white border m-2" onclick="setStatus('cancelled')">Cancelled</button>
            </div>

            <select class="form-control w-50 hide md-block my-3" id="status" onchange="setStatus(this.value)">
                <option value="pending" selected>For Approval</option>
                <option value="preparing">In Production</option>
                <option value="ready">Ready for Pickup/Delivery</option>
                <option value="complete">Completed</option>
                <option value="declined">Declined</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <div class="table-div shadow md-hide">
                <table class="table table-border md-hide" id="orders-list">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ORDER NO.</th>
                            <th>CUSTOMER</th>
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
            </div>

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
                <h5 class="text-medium d-inline-block m-0 p-0 mr-3" id="viewDetails-orderId">ORD-5</h5>
                <span class="text-medium text-info d-inline-block mb-2" id="viewDetails-status">Pending for approval</span>
                <br>
                <span class="text-muted" id="viewDetails-deadlineText"></span>:&emsp;<span id="viewDetails-deadline"></span>
                <hr>

                <div class="row">
                    <div class="col col-6">
                        <small class="text-muted d-block">Customer</small>
                        <span id="viewDetails-username"></span>
                    </div>
                    <div class="col col-6">
                        <small class="text-muted d-block">Contact #</small>
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
                        <small class="text-muted d-block">Servive Method</small>
                        <span id="viewDetails-pickupMethod"></span>
                    </div>
                </div>

                <hr>

                <small class="text-muted">Ordered Item(s)</small>
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


    <!-- View Screenshot of Payment -->
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


    <!-- Set Paid Amount Modal -->
    <form class="modal-form" method="post" action="actions/orderActions.php">
        <div class="modal fade ressetable-modal" id="updatePaidModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Set Down Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="orderId" id="updatePaid-orderId">
                    <h5 class="text-medium" id="updatePaid-orderIdText">ORD-123</h5>
                    <hr>
                    <div class="form-group">
                        <label for="">Down Payment</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">₱</span>
                            </div>
                            <input type="number" class="form-control" id="updatePaid-paidAmount" name="paidAmount" placeholder="0.00" min="0" required>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="update_paid_amount">Update</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    


    <!-- Approve Order Modal -->
    <form class="modal-form" method="post" action="actions/orderActions.php">
        <div class="modal fade ressetable-modal" id="approveOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Approve Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="orderId" id="approveOrder-orderId">

                    <p class="p-3 m-0 text-center">Are you sure you want to approve <b class="text-medium" id="approveOrder-orderIdText">ORD-123</b>?</p>
                    <hr>

                    <div class="row">
                        <div class="col col-3">
                            Total:
                        </div>
                        <div class="col col-9 text-right text-medium" id="approveOrder-totalText">
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-8" id="approveOrder-totalText">
                            Minimum Down Payment (<?=$config["down_payment"]?>%)
                        </div>
                        <div class="col col-4 text-right text-medium" id="approveOrder-downPaymentText">
                            
                        </div>
                    </div>

                    <div class="form-group mt-5 mr-3 w-75 d-inline-block">
                        <label for="">Settled Down Payment</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">₱</span>
                            </div>
                            <input type="number" class="form-control" id="approveOrder-paidAmount" name="paidAmount" placeholder="0.00" min="0" required>
                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="approve_order">Approve</button>
                </div>
                </div>
            </div>
        </div>
    </form>




    <!-- Decline Order Modal -->
    <form class="modal-form" method="post" action="actions/orderActions.php">
        <div class="modal fade ressetable-modal" id="declineOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Decline Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="orderId" id="declineOrder-orderId">

                    <p class="p-3 m-0 text-center">Are you sure you want to decline <b class="text-medium" id="declineOrder-orderIdText">ORD-123</b>?</p>
                    <hr>

                    <label for="">Decline Reason<span class="text-danger">*</span></label>
                    <select class="form-control w-50 my-3" name="selectedReason" id="declineOrder-selectedReason">
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
                    
                    <textarea class="form-control" name="cancelDetails" id="declineOrder-cancelDetails"></textarea>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="decline_order">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Set Order as Ready Modal -->
    <form class="modal-form" method="post" action="actions/orderActions.php">
        <div class="modal fade ressetable-modal" id="setOrderReadyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Set as Ready</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="orderId" id="setOrderReady-orderId">
                    <p class="p-3 m-0 text-center">Are you sure you want to set <b class="text-medium" id="setOrderReady-orderIdText">ORD-123</b> as Ready for Pickup/Delivery?</p>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="set_ready">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </form>



    <!-- Set Order as Complete Modal -->
    <form class="modal-form" method="post" action="actions/orderActions.php">
        <div class="modal fade ressetable-modal" id="setOrderCompleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Set as Complete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="orderId" id="setOrderComplete-orderId">

                    <p class="p-3 m-0 text-center">Are you sure you want to approve <b class="text-medium" id="setOrderComplete-orderIdText">ORD-123</b>?</p>
                    <hr>

                    <div class="row">
                        <div class="col col-5">
                            Total:
                        </div>
                        <div class="col col-7 text-right text-medium" id="setOrderComplete-totalText">
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-5">
                            Balance to Pay:
                        </div>
                        <div class="col col-7 text-right text-medium" id="setOrderComplete-balanceText">
                            
                        </div>
                    </div>

                    <div class="form-group mt-5 mr-3 w-75 d-inline-block">
                        <label for="">Settled Down Payment</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">₱</span>
                            </div>
                            <input type="number" class="form-control" id="setOrderComplete-paidAmount" name="paidAmount" readonly>
                        </div>
                    </div>

                    <div class="form-group mt-2 mr-3 w-75" id="setOrderComplete-balanceInput">
                        <label for="">Balance</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">₱</span>
                            </div>
                            <input type="number" class="form-control" id="setOrderComplete-remainingBalance" name="remainingBalance" placeholder="0.00" min="0" required>
                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="set_complete">Confirm</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    

    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-orderList.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html>