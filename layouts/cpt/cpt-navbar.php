<nav class="navbar navbar-light bg-light shadow-sm" id="adm-navbar">
    <div id="side-btn-div">
        <button class="navbar-toggler border-0 mr-3" id="sidebar-btn" type="button">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
        
        <a class="navbar-brand text-bold" href="#">POSTRADO</a>

        
    </div>

    <?php 
        if(isset($searchBar) and $searchBar == true){
            $searchVal = isset($_GET["search"]) ? $_GET["search"] : '';
            echo '
                <div class="d-inline">
                    <input type="text" class="form-control" id="search" placeholder="Search" value="'.$searchVal.'">
                </div>
            ';
        }
        
        
    ?>
    
    <button class="navbar-toggler border-0" id="collapse-btn" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav m-auto">
            <li class="nav-item <?=$module === "CustomList" ? "active" : ""?>">
                <a class="nav-link" href="./cpt-customRequests.php"><i class="fa-solid fa-chair"></i>&emsp;Custom Orders&emsp;</a>
            </li>

            <li class="nav-item <?=$module === "ProductionList" ? "active" : ""?>">
                <a class="nav-link" href="./cpt-productionList.php"><i class="fa-solid fa-screwdriver-wrench"></i>&emsp;In Production&emsp;</a>
            </li>

        </ul>
        <br>
        <div class="mr-3" id="nav-user">
            
        </div>
        
    </div>
    
    
</nav>
<div style="height: 60px"></div>

<?php include("./layouts/alert-message.php"); ?>

<div id="sidebar" class="border-right shadow">
    <div class="list-group" style="height: 90%; position: relative">
        <a href="./cpt-customRequests.php" class="list-group-item list-group-item-action <?=$module === "CustomList" ? "active" : ""?>">
            <i class="fa-solid fa-chair"></i>Custom Orders
        </a>

        <a href="./cpt-productionList.php" class="list-group-item list-group-item-action <?=$module === "ProductionList" ? "active" : ""?>">
            <i class="fa-solid fa-screwdriver-wrench"></i>In Production
        </a>


        
        <a id="sidebar-user" class="list-group-item list-group-item-action dropdown d-inline" style="position: absolute; bottom: 0; padding: 0">
            
        </a>

    </div>
</div>



<?php
    include("./layouts/accountDetails.php");
?>