<?php
session_start();
require("../database/connection.php");

$action = isset($_POST["action"]) ? $_POST["action"] : null;


$user = $_SESSION["user"];
$date = date("Y-m-d H:i:s");
$message = "Message";
$res = "success";
$redirect = true;
$location = 'adm-materials.php';

try{
    if($action === 'add_material'){

        $materialName = fixStr($_POST["materialName"]);
        $quantity = $_POST["quantity"];
        $minimumQty = $_POST["minimumQty"];
        $unit = $_POST["unit"];
        $cost = $_POST["cost"];
    
        //insert new material
        $conn->query("
            INSERT INTO materials(
                material_name, quantity, minimum_qty, unit, cost, archived, date_created, added_by) 
            VALUES (
                '$materialName','$quantity','$minimumQty','$unit','$cost','0','$date','$user')
        ");
    
    
        $message = 'The material has been added successfully';
    }

    if($action === 'edit_material'){

        $materialId = $_POST["materialId"];
        $materialName = fixStr($_POST["materialName"]);
        $quantity = $_POST["quantity"];
        $minimumQty = $_POST["minimumQty"];
        $unit = $_POST["unit"];
        $cost = $_POST["cost"];
    
        //update material
        $conn->query("
            UPDATE materials 
            SET
                material_name='$materialName',
                quantity='$quantity',
                minimum_qty='$minimumQty',
                unit='$unit',
                cost='$cost'
            WHERE id = $materialId
        ");
    
    
        $message = 'The material has been edited successfully';
    }

    if($action === 'set_stock'){
        $materialId = $_POST["materialId"];
        $quantity = $_POST["quantity"];

        $oldQuantity = $conn->query("SELECT quantity FROM materials WHERE id = $materialId")->fetch_assoc()["quantity"];

        if($oldQuantity != $quantity){
            $mode = $oldQuantity < $quantity ? 'add' : 'deduct';
            $stockQty = abs($oldQuantity - $quantity);

            //insert material usage
            $conn->query("
                INSERT INTO `material_usage`(
                    `material_id`, `quantity`, 
                    `mode`, `date_created`, 
                    `added_by`
                ) VALUES (
                    '$materialId','$stockQty',
                    '$mode','$date',
                    '$user'
                )
            ");

            //update material
            $conn->query("
                UPDATE materials 
                SET
                    quantity='$quantity'
                WHERE id = $materialId
            ");
        }
    
        
    
    
        $message = 'The material stocks has been updated successfully';
    }
    
    if($action === 'archive_material'){
        
        $materialId = $_POST["materialId"];

        $conn->query("
            UPDATE materials
            SET archived = 1, date_archived = '$date'
            WHERE id = '$materialId'
        ");

        $message = 'The material has been archived successfully';
    }

    if($action === 'restore_material'){
        $location = 'adm-archivedMaterials.php';
        
        $materialId = $_POST["materialId"];

        $conn->query("
            UPDATE materials
            SET archived = 0, date_archived = NULL
            WHERE id = '$materialId'
        ");

        $message = 'The material has been restored successfully';
    }



    
    if($redirect){
        $_SESSION["message"] = $message;
        $_SESSION["res"] = $res;

        header("Location: ../$location");
        exit();
    }
    else{
        echo $message;
    }
    
    
}
catch(Exception $e){
    if($redirect){
        $_SESSION["message"] = "Something went wrong";
        $_SESSION["res"] = "danger";

        header("Location: ../$location");
        exit();
    }
}


?>