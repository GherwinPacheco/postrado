<nav class="navbar navbar-light bg-light shadow-sm" id="adm-navbar">
    <div id="side-btn-div">
        <button class="navbar-toggler border-0 mr-3" id="sidebar-btn" type="button">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
        
        <a class="navbar-brand text-bold" href="./adm-dashboard.php">POSTRADO</a>

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
            <li class="nav-item <?=$module === "Dashboard" ? "active" : ""?>">
                <a class="nav-link" href="./adm-dashboard.php"><i class="fa-solid fa-house"></i>&emsp;Dashboard&emsp;</a>
            </li>


            <li class="nav-item <?=$module === "Products" ? "active" : ""?>">
                <a class="nav-link" data-toggle="collapse" href="#navbar-productsCollapse" role="button"><i class="fa-solid fa-chair"></i>&emsp;Products&emsp;</a>
            </li>
            <div class="collapse ml-5" id="navbar-productsCollapse">
                <li class="nav-item <?=$subModule === "Furnitures" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-furnitures.php">Furniture</a>
                </li>
                <li class="nav-item <?=$subModule === "Materials" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-materials.php">Materials</a>
                </li>
            </div>


            <li class="nav-item <?=$module === "Orders" ? "active" : ""?>">
                <a class="nav-link" data-toggle="collapse" href="#navbar-ordersCollapse" role="button"><i class="fa-solid fa-truck-fast"></i>&emsp;Orders&emsp;</a>
            </li>
            <div class="collapse ml-5" id="navbar-ordersCollapse">
                <li class="nav-item <?=$subModule === "OrderList" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-orderList.php">Order List</a>
                </li>
                <li class="nav-item <?=$subModule === "CustomRequests" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-customRequests.php">Custom Requests</a>
                </li>
            </div>

            <li class="nav-item <?=$module === "Reports" ? "active" : ""?>">
                <a class="nav-link" data-toggle="collapse" href="#navbar-reportsCollapse" role="button"><i class="fa-solid fa-chart-simple"></i>&emsp;Reports&emsp;</a>
            </li>
            <div class="collapse ml-5" id="navbar-reportsCollapse">
                <li class="nav-item <?=$subModule === "InventoryReport" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-inventoryReport.php">Materials Report</a>
                </li>
                <li class="nav-item <?=$subModule === "SalesReport" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-salesReport.php">Sales Report</a>
                </li>
            </div>


            <li class="nav-item <?=$module === "Settings" ? "active" : ""?>">
                <a class="nav-link" data-toggle="collapse" href="#navbar-configCollapse" role="button"><i class="fa-solid fa-gears"></i>&emsp;Configurations&emsp;</a>
            </li>
            <div class="collapse ml-5" id="navbar-configCollapse">
                <li class="nav-item <?=$subModule === "General" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-configGeneral.php">General</a>
                </li>
                <li class="nav-item <?=$subModule === "Accounts" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-configAccounts.php">Accounts</a>
                </li>
                <li class="nav-item <?=$subModule === "MasterData" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-configMasterData.php">Parameters</a>
                </li>
            </div>

            <li class="nav-item <?=$module === "Archive" ? "active" : ""?>">
                <a class="nav-link" data-toggle="collapse" href="#navbar-archivesCollapse" role="button"><i class="fa-solid fa-box-archive"></i>&emsp;Archives&emsp;</a>
            </li>
            <div class="collapse ml-5" id="navbar-archivesCollapse">
                <li class="nav-item <?=$subModule === "ArchivedProducts" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-archivedProducts.php">Furnitures</a>
                </li>
                <li class="nav-item <?=$subModule === "ArchivedMaterials" ? "active" : ""?>">
                    <a class="nav-link" href="./adm-archivedMaterials.php">Materials</a>
                </li>
            </div>

            
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
        <a href="./adm-dashboard.php" class="list-group-item list-group-item-action <?=$module === "Dashboard" ? "active" : ""?>">
            <i class="fa-solid fa-house"></i>Dashboard
        </a>


        <a class="list-group-item list-group-item-action <?=$module === "Products" ? "active" : ""?>" data-toggle="collapse" href="#sidebar-productsCollapse" role="button">
            <i class="fa-solid fa-chair"></i>Products
        </a>
        <div class="collapse" id="sidebar-productsCollapse">
            <a href="./adm-furnitures.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "Furnitures" ? "active-sub" : ""?>">
                Furniture
            </a>
            <a href="./adm-materials.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "Materials" ? "active-sub" : ""?>">
                Materials
            </a>
        </div>

        <a class="list-group-item list-group-item-action <?=$module === "Orders" ? "active" : ""?>" data-toggle="collapse" href="#sidebar-ordersCollapse" role="button">
            <i class="fa-solid fa-cart-shopping"></i>Orders
        </a>
        <div class="collapse" id="sidebar-ordersCollapse">
            <a href="./adm-orderList.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "OrderList" ? "active-sub" : ""?>">
                Order List
            </a>
            <a href="./adm-customRequests.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "CustomRequests" ? "active-sub" : ""?>">
                Custom Requests
            </a>
        </div>

        <a class="list-group-item list-group-item-action <?=$module === "Reports" ? "active" : ""?>" data-toggle="collapse" href="#sidebar-reportsCollapse" role="button">
            <i class="fa-solid fa-chart-simple"></i>Report
        </a>
        <div class="collapse" id="sidebar-reportsCollapse">
            <a href="./adm-inventoryReport.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "InventoryReport" ? "active-sub" : ""?>">
                Materials Report
            </a>
            <a href="./adm-salesReport.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "SalesReport" ? "active-sub" : ""?>">
                Sales Report
            </a>
        </div>


        <a class="list-group-item list-group-item-action <?=$module === "Configurations" ? "active" : ""?>" data-toggle="collapse" href="#sidebar-configCollapse" role="button">
            <i class="fa-solid fa-gears"></i>Settings
        </a>
        <div class="collapse" id="sidebar-configCollapse">
            <a href="./adm-configGeneral.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "General" ? "active-sub" : ""?>">
                General
            </a>
            <a href="./adm-configAccounts.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "Accounts" ? "active-sub" : ""?>">
                Accounts
            </a>
            <a href="./adm-configMasterData.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "MasterData" ? "active-sub" : ""?>">
                Parameters
            </a>
        </div>


        <a class="list-group-item list-group-item-action <?=$module === "Archives" ? "active" : ""?>" data-toggle="collapse" href="#sidebar-archivesCollapse" role="button">
            <i class="fa-solid fa-box-archive"></i>Archive
        </a>
        <div class="collapse" id="sidebar-archivesCollapse">
            <a href="./adm-archivedProducts.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "ArchivedProducts" ? "active-sub" : ""?>">
                Furniture
            </a>
            <a href="./adm-archivedMaterials.php" class="list-group-item list-group-item-action ml-3 <?=$subModule === "ArchivedMaterials" ? "active-sub" : ""?>">
                Materials
            </a>
        </div>

        <a id="sidebar-user" class="list-group-item list-group-item-action dropdown d-inline" style="position: absolute; bottom: 0; padding: 0">
            
        </a>

    </div>
</div>




<?php
    include("./layouts/accountDetails.php");
?>