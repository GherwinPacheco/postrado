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
    <link rel="stylesheet" href="./css/adm/adm-furnitures.css?t=<?=time()?>">

    <title>Archive</title>
</head>
<body>
    <?php
        $module = 'Archives';
        $subModule = 'ArchivedProducts';
        $searchBar = true;
        include('./layouts/adm/adm-navbar.php');
    ?>
    <div id="main-div">
        <div class="container py-5">

            <!--<button onclick="alert(pCount)">pCount</button>-->
            <h3 class="heading-text text-medium mb-5">Archived Furnitures</h3>


            
            <div class="table-div shadow">
                <table class="table table-border" id="furnitures-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="2">PRODUCT</th>
                            <th>CATEGORY</th>
                            <th class="md-hide">PRICE</th>
                            <th>DATE ARCHIVED</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            
            <nav class="pagination-div d-flex justify-content-center mt-3" id="furniture-table-pagination" aria-label="...">
                <ul class="pagination">
                    
                </ul>
            </nav>
        </div>
    </div>

    <!-- Restore Product Modal -->
    <form class="modal-form" method="post" action="actions/productActions.php">
        <div class="modal fade ressetable-modal" id="restoreProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-medium" id="exampleModalLongTitle">Restore Furniture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="productId" id="restoreProduct-productId">
                    
                    <p class="text-center">
                        Are you sure you want to unarchive selected product?<br>
                        <b id="restoreProduct-productNameText"></b>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="action" value="restore_product">Unarchive</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    


    


    
    <script src="./js/main.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-navbar.js?t=<?=time()?>"></script>
    <script src="./js/adm/adm-archivedProducts.js?t=<?=time()?>"></script>
    
    <?php include("./functions/alert-function.php"); ?>
    
</body>
</html>