<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(3));

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
    <link rel="stylesheet" href="./css/ctr/ctr-navbar.css?t=<?=time()?>">
    <link rel="stylesheet" href="./css/cpt/cpt-customProducts.css?t=<?=time()?>">

    <title>Custom Order</title>
</head>
<body>
    <?php
        $module = "Orders";
        $subModule = "CustomRequests";
        $searchBar = true;
        include("layouts/ctr/ctr-navbar.php");
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">

            <button class="btn btn-blue mb-5" onclick="history.back()">
                <i class="fa-solid fa-chevron-left"></i>&emsp;Back
            </button>

            <h3 class="heading-text text-medium">My Custom Requests</h3>

            <div class="customNavsDiv md-hide my-3">
                <button id="btn-admin_pending" class="statusBtn btn btn-dblue border m-2" onclick="setStatus('admin_pending')">Approval for Admin</button>
                <button id="btn-pending" class="statusBtn btn btn-white border m-2" onclick="setStatus('pending')">Approved</button>
                <button id="btn-updated" class="statusBtn btn btn-white border m-2" onclick="setStatus('updated')">Updated</button>
                <button id="btn-ordered" class="statusBtn btn btn-white border m-2" onclick="setStatus('ordered')">Ordered</button>
                <button id="btn-declined" class="statusBtn btn btn-white border m-2" onclick="setStatus('declined')">Declined</button>
            </div>

            <select class="form-control w-50 hide md-block my-3" id="status" onchange="setStatus(this.value)">
                <option value="admin_pending" selected>Approval for Admin</option>
                <option value="pending">Approved</option>
                <option value="updated">Updated</option>
                <option value="ordered">Ordered</option>
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

                <div class="row mb-4">
                    <div class="col">
                        <small class="text-muted">Service Option</small>
                        <br>
                        <span id="viewCustom-serviceOption"></span>
                    </div>
                    <div class="col"></div>
                    
                </div>
                

                <hr>

                <span class="text-muted">Order Placed on:</span>&emsp;<span id="viewCustom-dateCreated"></span>&emsp;<span id="viewCustom-timeCreated"></span><br>

                <span class="text-muted">Ordered By:</span>&emsp;<span id="viewCustom-addedBy"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>


    <!-- Updated View Product Modal -->
    <div class="modal fade" id="updatedViewCustomModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Custom Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4 border-bottom pb-2">
                    <div class="col col-12 col-lg-6 border-right mb-3">
                        <h4 id="updatedViewCustom-productName" class="text-medium p-0 m-0"></h4>
                        <span class="d-block text-muted" id="updatedViewCustom-category"></span>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <span class="d-block  text-medium">Description</span>
                        <span class="d-block text-muted" id="updatedViewCustom-description" style="text-indent: 30px"></span>
                    </div>
                </div>

                <div class="row mb-4 border-bottom pb-2">
                    <div class="col col-12 col-lg-6 mb-3">
                        <div class="row">
                            <div class="col col-6 text-muted">Quantity:</div>
                            <div class="col col-6" id="updatedViewCustom-quantity"></div>

                            <div class="col col-6 text-muted">Varnish:</div>
                            <div class="col col-6" id="updatedViewCustom-varnish"></div>

                            <div class="col col-6 text-muted">Woodstain Color:</div>
                            <div class="col col-6" id="updatedViewCustom-color"></div>

                            <div class="col col-6 text-muted">Wood Type:</div>
                            <div class="col col-6" id="updatedViewCustom-wood"></div>
                            
                            <div class="col col-6 text-muted">Customer:</div>
                            <div class="col col-6" id="updatedViewCustom-customer"></div>

                            <div class="col col-6 text-muted">Service Option:</div>
                            <div class="col col-6" id="updatedViewCustom-serviceOption"></div>

                            <div class="col col-6 text-muted">Deadline:</div>
                            <div class="col col-6" id="updatedViewCustom-deadline"></div>
                        </div>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <span class="d-block text-medium">Custom Order Specifications</span>
                        <div class="row mt-2 pl-3" id="updatedViewCustom-specsDiv">
                            
                        </div>
                    </div>
                </div>


                

                <div class="row">
                    <div class="col col-12 col-lg-6">
                        <p class=" text-medium">Carpenter's Sketch</p>
                        <div class="imagesContainer mb-4" id="updatedViewCustom-carpenterSketch"></div>
                    </div>
                    <div class="col col-12 col-lg-6">
                        <p class=" text-medium">Reference Images</p>
                        <div class="imagesContainer mb-4" id="updatedViewCustom-referenceImage"></div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Archive Product Modal -->
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


    



    
    <script>
        var statusGET = '<?=isset($_GET["status"]) ? $_GET["status"] : 'admin_pending'?>';
    </script>
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-navbar.js?t=<?=time()?>"></script>
    <script src="./js/ctr/ctr-customRequests.js?t=<?=time()?>"></script>


    
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>