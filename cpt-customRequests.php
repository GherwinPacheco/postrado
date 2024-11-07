<?php
    session_start();
    require('./database/connection.php');
    require('./functions/userValidate.php');
    rememberUser();
    requireLogin();
    allowRole(array(2));

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
        $module = "CustomList";
        $searchBar = true;
        include("layouts/cpt/cpt-navbar.php");
    ?>
    <div class="py-3" id="main-div">
        <div class="container py-3">

            <h3 class="heading-text text-medium">Customized Orders</h3>

            <div class="customNavsDiv md-hide my-3">
                <button id="btn-admin_pending" class="statusBtn btn btn-dblue border m-2" onclick="setStatus('admin_pending')">Approval for Admin</button>
                <button id="btn-pending" class="statusBtn btn btn-white border m-2" onclick="setStatus('pending')">Stand-by for Update</button>
                <button id="btn-updated" class="statusBtn btn btn-white border m-2" onclick="setStatus('updated')">Updated</button>
            </div>

            <select class="form-control w-50 hide md-block my-3" id="status" onchange="setStatus(this.value)">
                <option value="admin_pending" selected>Approval for Admin</option>
                <option value="pending">Standby for Update</option>
                <option value="updated">Updated</option>
            </select>

            <div class="table-div shadow md-hide">
                <table class="table table-border md-hide" id="custom-list">
                    <thead>
                        <tr>
                            <th></th>
                            <th>FURNITURE</th>
                            <th>CUSTOMER</th>
                            <th>WOOD</th>
                            <th>PRICE</th>
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

                            <div class="col col-6 text-muted">WoodStain Color:</div>
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


    <!-- Update Custom Product Modal -->
    <form class="modal-form" action="./actions/customActions.php" method="post"  enctype="multipart/form-data">
        <div class="modal fade ressetable-modal" id="updateCustomModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Update Customized Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="updateCustom-customId" name="customId">
                    <div class="row mb-2">
                        <div class="col col-6 d-flex flex-column border-right">
                            <h5 id="updateCustom-productName" class="text-medium p-0 m-0"></h5>
                            <span class="text-muted d-block mb-4" id="updateCustom-category"></span>

                            <div class="row mb-3">
                                <div class="col">
                                    <small class="text-muted d-block">Quantity</small>
                                    <span class="d-block" id="updateCustom-quantity"></span>
                                </div>
                                <div class="col">
                                    <small class="text-muted d-block">Wood Type</small>
                                    <span class="d-block" id="updateCustom-woodType"></span>
                                </div>
                            </div>


                            <div class="row mb-5">
                                <div class="col">
                                    <small class="text-muted d-block">Varnish</small>
                                    <span class="d-block" id="updateCustom-varnish"></span>
                                </div>
                                <div class="col">
                                    <small class="text-muted d-block">Woodstain Color</small>
                                    <span class="d-block" id="updateCustom-color"></span>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <small class="text-muted d-block">Placed on</small>
                                    <span class="d-block" id="updateCustom-dateCreated"></span>
                                </div>
                                <div class="col">
                                    <small class="text-muted d-block">Customer</small>
                                    <span class="d-block" id="updateCustom-addedBy"></span>
                                </div>
                            </div>



                            

                            
                        </div>
                        <div class="col col-4">

                            <div class="form-group">
                                <label for="updateCustom-price">Price (per quantity)<span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">₱</span>
                                    </div>
                                    <input type="number" class="form-control" id="updateCustom-price" name="price" placeholder="0.00" step="0.01" min="1" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="updateCustom-downPayment">Down Payment<span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">₱</span>
                                    </div>
                                    <input type="number" class="form-control" id="updateCustom-downPayment" name="downPayment" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="updateCustom-completionDate">Deadline<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="updateCustom-completionDate" name="completionDate" min="<?=date("Y-m-d")?>" required>
                            </div>


                        </div>
                    </div>

                    <hr>
                    
                    <div class="row">
                        <div class="col col-12 col-lg-6 border-right">
                            <label for="updateCustom-imageFile">Customized Sketch<span class="text-danger">*</span></label>
                            
                            <div class="border mb-2 p-1 text-muted" id="updateCustom-imageContainer">
                                <p class="text-center m-0 p-0">Upload custom furniture layout</p>
                            </div>
                            <input multiple type="file" class="mb-3" name="imageFile[]" id="updateCustom-imageFile" accept="image/*" onchange="showMultipleImages(this, 'updateCustom-imageContainer')" required>
                            
                        </div>

                        <div class="col col-12 col-lg-6">
                            <span>Product Specs</span><span class="text-danger">*</span>&emsp;<a href="#" role="button" onclick="addCustomSpecs('updateCustom-specsDiv')">[Add]</a>
                            <div class="py-2" id="updateCustom-specsDiv">
                                
                            </div>
                        </div>
                    </div>
                    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="update_custom">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    


    <!-- Order Custom Product Modal -->
    <form class="modal-form" action="./actions/customActions.php" method="post"  enctype="multipart/form-data">
        <div class="modal fade resettable-modal" id="setProductionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add to Production</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="setProduction-customId" name="customId">
                    <div class="row">
                        <div class="col border-right">
                            <div class="row">
                                <div class="col col-8">
                                    <h5 id="setProduction-productName" class="text-medium p-0 m-0">Product Name</h5>
                                    <span class="text-muted d-block mb-4" id="setProduction-category">Category</span>
                                </div>
                                <div class="col col-4">
                                    
                                </div>
                            </div>
                            

                            <div class="row">
                                <div class="col col-6 mb-3">
                                    <small class="text-muted d-block">Varnish</small>
                                    <span class="d-block" id="setProduction-varnish">...</span>
                                </div>
                                <div class="col col-6 mb-3">
                                    <small class="text-muted d-block">Woodstain Color</small>
                                    <span class="d-block" id="setProduction-color">...</span>
                                </div>
                                <div class="col col-6 mb-3">
                                    <small class="text-muted d-block">Wood Type</small>
                                    <span class="d-block" id="setProduction-woodType">...</span>
                                </div>
                            </div>

                        </div>

                        <div class="col">
                            <small class="d-block text-muted">Customer Info</small>
                            <div class="pl-3">
                                <span class="d-block" id="setProduction-customerName">Customer Name</span>
                                <span class="d-block" id="setProduction-address">Customer Address</span>
                                <span class="d-block" id="setProduction-contact">Customer Contact</span>
                            </div>
                            
                        </div>
                    </div>

                    <hr>

                    <small class="text-muted d-block">Order Summation</small>

                    <div class="setProduction-orderSummation p-3">

                        <div class="row mb-4">
                            <div class="col text-medium col-6">Product Price:</div>
                            <div class="col col-6" id="setProduction-productPrice">
                                <span>...</span>
                            </div>

                            <div class="col text-medium col-6">Quantity:</div>
                            <div class="col col-6" id="setProduction-quantity">
                                <span>...</span>
                            </div>

                            <div class="col text-medium col-6">Service Method:</div>
                            <div class="col col-6" id="setProduction-serviceMethod">
                                <span>...</span>
                            </div>

                            <div class="col col-12 mt-4" id="setProduction-additionalsDiv">

                            </div>
                        </div>




                        <div class="row">
                            <div class="col text-medium col-6">Subtotal:</div>
                            <div class="col col-6" >
                                <span id="setProduction-subtotal">...</span>
                            </div>

                            <div class="col text-medium col-6 mb-3">Minimum Down Payment (<?=$config["down_payment"]?>%):</div>
                            <div class="col col-6 mb-3" >
                                <span id="setProduction-minimumDownPayment">...</span>
                            </div>

                            <div class="col text-medium col-6">Down Payment:</div>
                            <div class="col col-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">₱</span>
                                    </div>
                                    <input type="number" class="form-control" id="setProduction-downPayment" name="downPayment" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                            </div>


                            <div class="col text-medium col-6 mt-3">Total Balance:</div>
                            <div class="col col-6 mt-3 text-medium text-primary">
                                <span id="setProduction-totalBalance">...</span>
                            </div>
                        </div>



                        
                    </div>

                    




                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary" name="action" value="add_custom_order">Order</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    



    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/cpt/cpt-customProducts.js?t=<?=time()?>"></script>


    
    
    <?php include("./functions/alert-function.php"); ?>
</body>
</html>