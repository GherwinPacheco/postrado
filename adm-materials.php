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
    <link rel="stylesheet" href="./css/adm/adm-materials.css?t=<?=time()?>">

    <title>Production Materials</title>
</head>
<body>
    <?php
        $module = 'Products';
        $subModule = 'Materials';
        $searchBar = true;
        include('./layouts/adm/adm-navbar.php');
    ?>
    
    <div id="main-div">
        
        <div class="container py-5">

            <!--<button onclick="alert(pCount)">pCount</button>-->
            <h3 class="heading-text text-medium">List of Materials</h3>


            <!-- Table Filters and Add Button -->
            <div class="row mb-3">
                <div class="col col-4">
                    <select class="form-control material-tbl-filter mr-3" name="" id="material-status">
                        <option value="all">All</option>
                        <option value="1">In Stock</option>
                        <option value="2">Low Stock</option>
                        <option value="3">Out of Stock</option>
                    </select>
                </div>
                <div class="col col-8">
                    <button id="add-material-btn" class="btn btn-blue float-right" data-toggle="modal" data-target="#addMaterialModal">
                        <i class="fa-solid fa-plus"></i>&emsp;Add
                    </button>
                </div>
            </div>

            
            <div class="table-div shadow md-hide">
                <table class="table table-border md-hide" id="materials-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>MATERIAL</th>
                            <th>QUANTITY</th>
                            <th>COST</th>
                            <th>STATUS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            <div class="table-div-mobile hide md-block">
                <div id="materials-table-mobile" class="hide md-block">
                    
                </div>
            </div>
            <nav class="pagination-div d-flex justify-content-center mt-3" id="materials-table-pagination" aria-label="...">
                <ul class="pagination">
                    
                </ul>
            </nav>
        </div>
    </div>
    
    
    <!-- View Materials Modal -->
    <div class="modal fade" id="viewMaterialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-medium" id="exampleModalLongTitle">Material Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 id="viewMaterial-materialName" class="text-medium">Product Name</h5>
                <br>
                
                <div class="row">
                    <div class="col">
                        <span class="text-muted">Stock Quantity:</span>&emsp;
                        <span id="viewMaterial-quantity"></span>
                    </div>
                    <div class="col">
                        <span class="text-muted">Minimum Quantity:</span>&emsp;
                        <span id="viewMaterial-minimumQty"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <span class="text-muted">Total Cost:</span>&emsp;
                        <span id="viewMaterial-cost"></span>
                    </div>
                    <div class="col">
                        <span class="text-muted">Status:</span>&emsp;
                        <span id="viewMaterial-status"></span>
                    </div>
                </div>

                <hr>

                <span class="text-muted">Date Created:</span>&emsp;<span id="viewMaterial-dateCreated"></span></span>&emsp;<span id="viewMaterial-timeCreated"></span><br>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>


    <!-- Add Material Modal -->
    <form class="modal-form" method="post" action="actions/materialActions.php">
        <div class="modal fade ressetable-modal" id="addMaterialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add New Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col">
                            <label for="">Material Name</label>
                            <input type="text" class="form-control" name="materialName" id="addMaterial-materialName" required>
                        </div>
                        <div class="col">
                            <label for="">Total Cost</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">₱</span>
                                </div>
                                <input type="number" class="form-control" name="cost" id="addMaterial-cost" placeholder="0.00" min="1" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="">Stock Quantity</label>
                            <div class="input-group mb-3">
                            <input type="number" class="form-control" name="quantity" id="addMaterial-quantity" min="0" required>
                                <div class="input-group-append">
                                    <select class="form-control" name="unit" id="addMaterial-unit" required>
                                        <?php
                                            $result = $conn->query("SELECT * FROM unit_of_measurement WHERE archived = 0");
                                            while($row = $result->fetch_assoc()){
                                                echo '
                                                    <option value="'.$row["id"].'">'.$row["unit_name"].'</option>
                                                ';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <label for="">Minimum Quantity</label>
                            <input type="text" class="form-control" name="minimumQty" id="addMaterial-minimumQty" required>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_material">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>




    <!-- Edit Material Modal -->
    <form class="modal-form" method="post" action="actions/materialActions.php">
        <div class="modal fade ressetable-modal" id="editMaterialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col">
                            <label for="">Material Name</label>
                            <input type="text" class="form-control" name="materialName" id="editMaterial-materialName" required>
                        </div>
                        <div class="col">
                            <label for="">Total Cost</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">₱</span>
                                </div>
                                <input type="number" class="form-control" name="cost" id="editMaterial-cost" placeholder="0.00" min="1" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="materialId" id="editMaterial-materialId">
                    <div class="row">
                        <div class="col">
                            <label for="">Stock Quantity</label>
                            <div class="input-group mb-3">
                            <input type="number" class="form-control" name="quantity" id="editMaterial-quantity" min="0" required>
                                <div class="input-group-append">
                                    <select class="form-control" name="unit" id="editMaterial-unit" required>
                                        <?php
                                            $result = $conn->query("SELECT * FROM unit_of_measurement WHERE archived = 0");
                                            while($row = $result->fetch_assoc()){
                                                echo '
                                                    <option value="'.$row["id"].'">'.$row["unit_name"].'</option>
                                                ';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <label for="">Minimum Quantity</label>
                            <input type="text" class="form-control" name="minimumQty" id="editMaterial-minimumQty" required>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_material">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Set Materials Stock Modal -->
    <form class="modal-form" method="post" action="actions/materialActions.php">
        <div class="modal fade" id="setStockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Set Material Stocks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="setStock-materialNameText" class="text-medium">Product Name</h5>
                    <hr>
                    
                    <input type="hidden" id="setStock-materialId" name="materialId">

                    <label for="">Stock Quantity</label>
                    <input type="number" class="form-control" id="setStock-quantity" name="quantity" min="0" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="set_stock">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>



    <!-- Archive Material Modal -->
    <form class="modal-form" method="post" action="actions/materialActions.php">
        <div class="modal fade ressetable-modal" id="archiveMaterialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="materialId" id="archiveMaterial-materialId">
                    
                    <p class="text-center">
                        Are you sure you want to archive<br>
                        <b id="archiveMaterial-materialNameText"></b>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" name="action" value="archive_material">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    


    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-materials.js?t=<?=time()?>"></script>

    <?php include("./functions/alert-function.php"); ?>
</body>
</html>