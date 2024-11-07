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

    <title>Parameters</title>
</head>
<body>
    <?php
        $module = 'Configurations';
        $subModule = 'MasterData';
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <h3 class="heading-text text-medium mb-4">Manage Shop Parameters</h3>


            <div class="row">
                <div class="col col-12 col-lg-4 mb-4">
                    
                    <div class="bg-white shadow rounded p-3" style="height: 400px; overflow-y: auto">
                        <div class="header-div pb-3">
                            <span class="text-muted">Furniture Categories</span>
                            <a href="#" class="float-right" data-toggle="modal" data-target="#addCategoryModal" onclick="addCategorySpecs('addCategory-specsDiv')">[Add]</a>
                        </div>


                        <div class="px-3" id="categoriesList"></div>

                    </div>
                    

                    
                </div>

                <div class="col col-12 col-lg-4 mb-4">

                    <div class="bg-white shadow rounded p-3" style="height: 400px; overflow-y: auto">
                        <div class="header-div pb-1">
                            <span class="text-muted">Materials Unit of Measurement</span>
                            <a href="#" class="float-right" data-toggle="modal" data-target="#addUnitModal">[Add]</a>
                        </div>

                        <div class="px-3" id="unitList"></div>
                    </div>
                    
                </div>

                <div class="col col-12 col-lg-4 mb-4">

                    <div class="bg-white shadow rounded p-3" style="height: 400px; overflow-y: auto">
                        <div class="header-div pb-1">
                            <span class="text-muted">WoodStain Color Choices</span>
                            <a href="#" class="float-right" data-toggle="modal" data-target="#addColorModal">[Add]</a>
                        </div>

                        <div class="px-3" id="colorList"></div>
                    </div>
                    
                </div>

                <div class="col col-12 col-lg-4 mb-4">

                    <div class="bg-white shadow rounded p-3" style="height: 400px; overflow-y: auto">
                        <div class="header-div pb-1">
                            <span class="text-muted">Wood Type</span>
                            <a href="#" class="float-right" data-toggle="modal" data-target="#addWoodModal">[Add]</a>
                        </div>

                        <div class="px-3" id="woodList"></div>
                    </div>
                    
                </div>

                <div class="col col-12 col-lg-4 mb-4">

                    <div class="bg-white shadow rounded p-3" style="height: 400px; overflow-y: auto">
                        <div class="header-div pb-1">
                            <span class="text-muted">Cancel Reasons</span>
                            <a href="#" class="float-right" data-toggle="modal" data-target="#addCancelModal">[Add]</a>
                        </div>

                        <div class="px-3" id="cancelReasonList"></div>
                    </div>
                    
                </div>

                <div class="col col-12 col-lg-4 mb-4">

                    <div class="bg-white shadow rounded p-3" style="height: 400px; overflow-y: auto">
                        <div class="header-div pb-1">
                            <span class="text-muted">Decline Reasons</span>
                            <a href="#" class="float-right" data-toggle="modal" data-target="#addDeclineModal">[Add]</a>
                        </div>

                        <div class="px-3" id="declineReasonList"></div>
                    </div>
                    
                </div>
            </div>




        </div>
    </div>


    <!-- Datalist for the specs -->
    <datalist id="specslist"></datalist>





    <!-- Add Category Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group mb-4">
                        <label for="">Category Type</label>
                        <input type="text" class="form-control" id="addCategory-categoryName" name="categoryName" required>
                    </div>
                    
                    <hr>
                    
                    <div class="header-div pb-3">
                        <small class="text-muted">Category Specifications</small>
                        <a href="#" class="float-right" onclick="addCategorySpecs('addCategory-specsDiv')">[Add]</a>
                    </div>

                    <div class="specsDiv" id="addCategory-specsDiv">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_category">Confirm</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    

    <!-- Edit Category Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editCategory-categoryId" name="categoryId">
                    <div class="form-group mb-4">
                        <label for="">Category Name</label>
                        <input type="text" class="form-control" id="editCategory-categoryName" name="categoryName" required>
                    </div>
                    
                    <hr>
                    
                    <div class="header-div pb-3">
                        <small class="text-muted">Category Specifications</small>
                        <a href="#" class="float-right" onclick="addCategorySpecs('editCategory-specsDiv')">[Add]</a>
                    </div>

                    <div class="specsDiv" id="editCategory-specsDiv">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_category">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Archive Category Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="archiveCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="archiveCategory-categoryId" name="categoryId">
                    <span>Are you sure you want to archive the category?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="archive_category">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>









    <!-- Add Unit Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group mb-4">
                        <label for="">Unit Name</label>
                        <input type="text" class="form-control" id="addUnit-unitName" name="unitName" required>
                    </div>
                

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_unit">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Edit Unit Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="editUnitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit Unit of Measurement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editUnit-unitId" name="unitId">
                    <div class="form-group mb-4">
                        <label for="">Unit Name</label>
                        <input type="text" class="form-control" id="editUnit-unitName" name="unitName" required>
                    </div>
                

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_unit">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Archive Unit Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="archiveUnitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="archiveUnit-unitId" name="unitId">
                    <span>Are you sure you want to archive the unit of measurement?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="archive_unit">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>








    <!-- Add Color Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="addColorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add WoodStain Choice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group mb-4">
                        <label for="">WoodStain Variant</label>
                        <input type="text" class="form-control" id="addColor-colorName" name="colorName" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="">Cost</label>
                        

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">₱</span>
                            </div>
                            <input type="number" class="form-control" id="addColor-price" name="price" required>
                        </div>
                    </div>
                

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_color">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Edit Color Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="editColorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit WoodStain</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editColor-colorId" name="colorId">
                    <div class="form-group mb-4">
                        <label for="">Variant Name</label>
                        <input type="text" class="form-control" id="editColor-colorName" name="colorName" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="">Cost</label>
                        

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">₱</span>
                            </div>
                            <input type="number" class="form-control" id="editColor-price" name="price" required>
                        </div>
                    </div>
                

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_color">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Archive Color Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="archiveColorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive WoodStain Choice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="archiveColor-colorId" name="colorId">
                    <span>Are you sure you want to archive the WoodStain Color?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="archive_color">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>








    <!-- Add Wood Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="addWoodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add Wood Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group mb-4">
                        <label for="">Wood Name</label>
                        <input type="text" class="form-control" id="addWood-woodName" name="woodName" required>
                    </div>
                

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_wood">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Edit Wood Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="editWoodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit Wood Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editWood-woodId" name="woodId">
                    <div class="form-group mb-4">
                        <label for="">Wood Name</label>
                        <input type="text" class="form-control" id="editWood-woodName" name="woodName" required>
                    </div>

                    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_wood">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Archive Wood Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="archiveWoodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive Wood Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="archiveWood-woodId" name="woodId">
                    <span>Are you sure you want to archive the Wood Type?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="archive_wood">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>





    <!-- Add Cancel Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="addCancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add Cancel Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group mb-4">
                        <label for="">Cancel Reason</label>
                        <input type="text" class="form-control" id="addCancel-reason" name="reason" required>
                    </div>
                

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_cancel">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Edit Cancel Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="editCancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit Cancel Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editCancel-reasonId" name="reasonId">
                    <div class="form-group mb-4">
                        <label for="">Cancel Reason</label>
                        <input type="text" class="form-control" id="editCancel-reason" name="reason" required>
                    </div>

                    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_cancel">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Archive Cancel Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="archiveCancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive Cancel Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="archiveCancel-reasonId" name="reasonId">
                    <span>Are you sure you want to archive the cancellation reason?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="archive_cancel">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>





    <!-- Add Decline Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="addDeclineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Add Decline Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group mb-4">
                        <label for="">Decline Reason</label>
                        <input type="text" class="form-control" id="addDecline-reason" name="reason" required>
                    </div>
                

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="add_decline">Add</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Edit Decline Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="editDeclineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Edit Decline Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editDecline-reasonId" name="reasonId">
                    <div class="form-group mb-4">
                        <label for="">Decline Reason</label>
                        <input type="text" class="form-control" id="editDecline-reason" name="reason" required>
                    </div>

                    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="edit_decline">Save Changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Archive Cancel Modal -->
    <form class="modal-form" method="post" action="actions/configActions.php">
        <div class="modal fade ressetable-modal" id="archiveDeclineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Archive Decline Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    
                    <input type="hidden" id="archiveDecline-reasonId" name="reasonId">
                    <span>Are you sure you want to archive the decline reason?</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="action" value="archive_decline">Archive</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    
    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-configMasterData.js?t=<?=time()?>"></script>

    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html>