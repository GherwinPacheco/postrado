<nav class="navbar navbar-light bg-light shadow md-hide" id="ctr-navbar">
    
<div class="row w-100">
    <div class="col col-2 d-flex align-items-center">
        <a class="navbar-brand text-bold" style="font-size: 25px; margin-left: 20%" href="./ctr-landingpage.php">POSTRADO</a>
    </div>

    <div class="col col-5 d-flex align-items-center">
        <?php
            if(isset($searchBar) and $searchBar == true){
                $searchVal = isset($_GET["search"]) ? ($_GET["search"]) : '';
                echo '
                    <input type="text" class="form-control d-inline-block" placeholder="Search" id="search" value="'.$searchVal.'">
                ';
            }
        ?>
        
    </div>
   
    <div class="col col-5 d-flex">
        

        <?php
            $customOnclick = isset($_SESSION["user"]) ? '
                 data-toggle="modal" data-target="#addCustomModal"
            ' : '
                href="./login.php"
            ';
        ?>
        <a role="button" class="btn btn-white text-dark mr-3" <?=$customOnclick?>>
            <div class="d-inline-block px-3" style="position: relative">
                <i class="fa-solid fa-circle-plus"></i>
            </div>
            <br>
            Custom
        </a>


        <div class="dropdown">
            <a class="btn dropdown mr-3" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                <div class="d-inline-block px-3" style="position: relative">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <br>
                Order
            </a>

            <div class="dropdown-menu">
                <a class="dropdown-item" href="./ctr-orders.php">My Orders</a>
                <a class="dropdown-item" href="./ctr-customRequests.php">My Custom Requests</a>
            </div>
        </div>
        
        <div class="dropdown">
            <button class="btn btn-white text-dark mr-2 dropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-inline-block px-3" style="position: relative">
                    <span class="cartCount"></span>
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <br>
                Cart
            </button>
            <div class="dropdown-menu dropdown-menu-right shadow-lg pt-0" style="width: 500px; border-radius: 10px" aria-labelledby="dropdownMenuButton">
                <a id="viewAllCartBtn" class="float-right p-3 hide" href="./ctr-cart.php">View All</a>
                <h5 class="text-medium p-3 pl-4 border-bottom">Cart</h5> 
                <div id="cartListNavbar" class="px-3 mb-3">
                    <!--Cart list goes here-->
                </div>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-white text-dark mr-2 dropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-inline-block px-3" style="position: relative">
                    <span class="notifCount"></span>
                    <i class="fa-solid fa-bell"></i>
                </div>
                <br>
                Notification
            </button>
            <div class="dropdown-menu dropdown-menu-right shadow-lg pt-0" style="width: 500px; border-radius: 10px" aria-labelledby="dropdownMenuButton">
                <h5 class="text-medium p-3 pl-4 border-bottom">Notifications</h5>
                <div id="notifList" style="max-height: 500px; overflow-y: auto">
                    <div id="notifListNavbar" class="px-3 mb-3">
                        <!--Old Notifs will go here-->
                    </div>
                </div>
                
            </div>
        </div>

        <div class="d-flex align-items-center" id="nav-user"></div>
    </div>
</div>
    
    
    
</nav>
<div class="md-hide" style="height: 60px"></div>


<?php include("./layouts/alert-message.php"); ?>


<nav class="bottom-nav bg-white border-top w-100 shadow-lg hide md-block px-1" style="position: fixed; bottom: 0; z-index: 1000;">
    <div class="row">
        <div class="col text-center py-3">
            <a href="./ctr-landingpage.php" class="text-dblue">
                <i class="fa-solid fa-house"></i><br>
                Home
            </a>
        </div>
        <div class="col text-center py-3">
            <div class="btn-group dropup">
                <a role="button" data-toggle="dropdown" class="text-dblue dropdown">
                    <div class="d-inline-block px-3" style="position: relative">
                    <!--<span class="navBtnNum orderCount d-inline-block rounded-circle bg-danger text-white">0</span>-->
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    Order
                </a>
                <div class="dropdown-menu">
                    <!-- Dropdown menu links -->
                    <a class="dropdown-item" href="./ctr-orders.php">My Orders</a>
                    <a class="dropdown-item" href="./ctr-customRequests.php">My Custom Requests</a>
                </div>
            </div>
        </div>
        <div class="col text-center py-3">
            <a role="button" class="text-dblue" <?=$customOnclick?>>
                <div class="d-inline-block px-3" style="position: relative">
                    <i class="fa-solid fa-circle-plus"></i>
                </div>
                Custom
            </a>
        </div>
        <div class="col text-center py-3">
            <a href="./ctr-cart.php" class="text-dblue">
                <div class="d-inline-block px-3" style="position: relative">
                    <span class="cartCount"></span>
                    <i class="fa-solid fa-cart-shopping"></i>
                </div><br>
                Cart
            </a>
        </div>
        <div class="col text-center py-3">
            <div id="bottomnav-user"></div>
        </div>
    </div>
</nav>




<!-- Add Custom Product Request Modal -->
<form class="modal-form" method="post" action="./actions/customActions.php" enctype="multipart/form-data">
    <div class="modal fade ressetable-modal" id="addCustomModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Customized Furniture Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <label for="addCustom-imageFile">Reference Design<span class="text-danger">*</span></label>
                
                <div class="border mb-2 p-3 text-muted" id="addCustom-imageContainer">
                    <p class="text-center m-0 p-0">Upload Image</p>
                </div>
                <input multiple type="file" class="mb-3" name="imageFile[]" id="addCustom-imageFile" accept="image/*"  onchange="showMultipleImages(this, 'addCustom-imageContainer')" required>
                
                

                <div class="row mb-3">
                    <div class="col">
                        <label for="addCustom-category">Category<span class="text-danger">*</span></label>
                        <select name="category" id="addCustom-category" class="form-control" required>
                            <option value selected hidden>Select Category</option>
                            <?php
                                $result = $conn->query("SELECT * FROM category WHERE archived = 0");

                                while($row = $result->fetch_assoc()){
                                    echo '
                                        <option value="'.$row["id"].'">'.ucwords($row["category_name"]).'</option>
                                    ';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="addCustom-category">Wood Type<span class="text-danger">*</span></label>
                        <select name="wood" id="addCustom-wood" class="form-control" required>
                            <option value selected hidden>Select Wood Type</option>
                            <?php
                                $result = $conn->query("SELECT * FROM wood_type WHERE archived = 0");

                                while($row = $result->fetch_assoc()){
                                    echo '
                                        <option value="'.$row["id"].'">'.ucwords($row["wood_name"]).'</option>
                                    ';
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col">
                        <label for="addCustom-category">Quantity<span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control" name="quantity" id="addCustom-quantity">
                    </div>

                    <div class="col">
                        <label for="addCustom-pickupMethod">Service Method&emsp;<b id="pickupPriceText" class="text-medium">(₱ 0.00)</b><span class="text-danger">*</span></label>
                        <select name="pickupMethod" id="addCustom-pickupMethod" class="form-control mb-3" onchange="customPriceText(this, 'fee', 'pickupPriceText')" required>
                            <option value='{"mode": "for pickup", "fee": "0"}'>For Pickup</option>
                            <option value='{"mode": "for deliver", "fee": "<?=$config["for_deliver"]?>"}'>For Deliver</option>
                            <option value='{"mode": "for installation", "fee": "<?=$config["for_install"]?>"}'>For Install</option>
                            <option value='{"mode": "deliver and install", "fee": "<?=$config["deliver_install"]?>"}'>For Deliver and Install</option>
                        </select>
                    </div>
                </div>

                <small class="d-block border-bottom text-muted mb-3">Additionals</small>

                <div class="row mb-3" id="additionalsDiv">
                    <div class="col col-5">
                        <label for="addCustom-varnish">Varnish&emsp;<b class="text-medium">(₱ <?=number_format($config["varnish_price"], 2)?>)</b><span class="text-danger">*</span></label>

                        <div class="pl-3 border-left">
                            <div class="custom-control custom-radio mr-3">
                                <input type="radio" name="varnish" value="0" id="addCustom-noVarnish" class="custom-control-input" checked>
                                <label class="custom-control-label" for="addCustom-noVarnish">No</label>
                            </div>

                            <div class="custom-control custom-radio">
                                <input type="radio" name="varnish" value="<?=$config["varnish_price"]?>" id="addCustom-varnish" class="custom-control-input">
                                <label class="custom-control-label" for="addCustom-varnish">Yes</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col col-7">
                        <label for="addCustom-color">WoodStain Color&emsp;<b id="colorPriceText" class="text-medium">(₱ 0.00)</b><span class="text-danger">*</span></label>
                        <select name="color" id="addCustom-color" class="form-control mb-3" onchange="customPriceText(this, 'price', 'colorPriceText')" required>
                            <option value='{"id": "0", "price": "0"}' selected>None</option>
                            <?php
                                $result = $conn->query("SELECT * FROM color WHERE archived = 0");

                                while($row = $result->fetch_assoc()){
                                    echo '
                                        <option value=\'{"id": "'.$row["id"].'", "price": "'.$row["price"].'"}\'>'.ucwords($row["color_name"]).'</option>
                                    ';
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <label for="addCustom-description">Description</label>
                <textarea class="form-control" name="description" id="addCustom-description" maxlength="255" style="height: 200px"></textarea>
                <small class="text-muted">Additional details about custom order can be added here</small>
                
                <div class="mt-4">
                    <small class="d-block text-muted text-justify">
                        <b>Note:</b> For custom furniture, the carpenter will send you specific pricing after reviewing your request and getting approval from the Admin.
                    </small>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-blue" name="action" value="add_custom">Order</button>
            </div>
            </div>
        </div>
    </div>
</form>


<?php
    if(isset($_SESSION["user"])){
        echo '
            <input type="hidden" id="userId" value="'.$_SESSION["user"].'">
        ';
        
    }


    include("./layouts/accountDetails.php");
?>



