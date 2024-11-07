<?php
session_start();
require("../database/connection.php");

$action = isset($_POST["action"]) ? $_POST["action"] : null;


$user = $_SESSION["user"];
$date = date("Y-m-d H:i:s");
$message = "Message";
$res = "success";
$redirect = true;

try{
    if($action === 'update_status'){
        $redirect = false;


        //update material status
        $conn->query("
            UPDATE materials m
            SET status = IF(quantity = 0, 3, IF(quantity <= minimum_qty, 2, 1))
            WHERE 1;
        ");
        echo "Updated materials: " . $conn->affected_rows."\n";

        //update product status
        $conn->query("
            UPDATE products p
            SET status = IF((
                SELECT COUNT(*) 
                FROM product_materials pm
                    INNER JOIN materials m ON pm.material_id = m.id
                WHERE 
                    pm.product_id = p.id AND
                    m.status > 1 AND
                    m.archived = 0
                ) = 0, 1, 0)
            WHERE 1;
        ");
        echo "Updated products: " . $conn->affected_rows."\n";
    }


    if($action === 'add_product'){
        $location = 'adm-furnitures.php';


        $productName = fixStr($_POST["productName"]);
        $category = $_POST["category"];
        $priceCount = $_POST["priceCount"];
        $description = fixStr($_POST["description"]);
        $productionDuration = $_POST["productionDuration"];
    
    
        $priceArray = array();
        for($x = 1; $x <= $priceCount; $x++){
            $priceName = null;
            $price = null;
    
            $specsArray = array();
            if(isset($_POST["priceName$x"]) and isset($_POST["price$x"])){
                $priceName = fixStr($_POST["priceName$x"]);
                $price = $_POST["price$x"];
            
    
                if(isset($_POST["specsId$x"]) and isset($_POST["specsValue$x"])){
                    $id = $_POST["specsId$x"];
                    $value = $_POST["specsValue$x"];
                    $len = count($value);
    
                    for($y = 0; $y < $len; $y++){
                        array_push($specsArray, array(
                            'specs_id' => $id[$y],
                            'value' => fixStr($value[$y])
                        ));
                    }
    
                }
    
                array_push($priceArray, array(
                    'price_name' => $priceName,
                    'price' => $price,
                    'specs' => $specsArray
                ));
    
            }   
    
        }
    
        $materials = $_POST["material"];
    
    
        //insert new product
        $conn->query("
            INSERT INTO products(
                product_name, category, description, production_duration,
                archived, date_created, added_by) 
            VALUES (
                '$productName','$category','$description','$productionDuration','0','$date','$user')
        ");
    
        //get the product id of added product
        $productId = $conn->query("SELECT id FROM products ORDER BY id DESC LIMIT 1")->fetch_assoc()["id"];
        
        //insert the price    
        foreach($priceArray as $p){
            $pName = $p["price_name"];
            $pPrice = $p["price"];
    
            $conn->query("
                INSERT INTO prices (product_id, price, price_name) 
                VALUES ('$productId','$pPrice','$pName')
            ");
    
            $pId = $conn->query("SELECT id FROM prices ORDER BY id DESC LIMIT 1")->fetch_assoc()["id"];
    
            //insert the specs of the price
            foreach($p["specs"] as $s){
                $sId = $s["specs_id"];
                $sValue = $s["value"];
    
                $conn->query("
                    INSERT INTO price_specs(price_id, specs_id, value) 
                    VALUES ('$pId','$sId','$sValue')
                ");
            }
    
        }
    
        //insert the materials of the product
        foreach($materials as $m){
            $conn->query("
                INSERT INTO product_materials (product_id, material_id) 
                VALUES ('$productId', '$m')
            ");
        }
    
    
        //upload product image
        if(isset($_FILES["imageFile"])){
            $uploaddir = '../img/products/';
            $uploadfile = $uploaddir . $productId.'.png';
    
            move_uploaded_file($_FILES['imageFile']['tmp_name'], $uploadfile);
        }



        $message = 'The product has been added successfully';
    }

    if($action === 'edit_product'){
        $location = 'adm-furnitures.php';


        $productId = $_POST["productId"];
        $productName = fixStr($_POST["productName"]);
        $category = $_POST["category"];
        $priceCount = $_POST["priceCount"];
        $description = fixStr($_POST["description"]);
        $productionDuration = $_POST["productionDuration"];
    
    
        $priceArray = array();
        for($x = 1; $x <= $priceCount; $x++){
            $priceName = null;
            $price = null;
    
            $specsArray = array();
            if(isset($_POST["priceName$x"]) and isset($_POST["price$x"])){
                $priceName = fixStr($_POST["priceName$x"]);
                $price = $_POST["price$x"];
            
    
                if(isset($_POST["specsId$x"]) and isset($_POST["specsValue$x"])){
                    $id = $_POST["specsId$x"];
                    $value = $_POST["specsValue$x"];
                    $len = count($value);
    
                    for($y = 0; $y < $len; $y++){
                        array_push($specsArray, array(
                            'specs_id' => $id[$y],
                            'value' => fixStr($value[$y])
                        ));
                    }
    
                }
    
                array_push($priceArray, array(
                    'price_name' => $priceName,
                    'price' => $price,
                    'specs' => $specsArray
                ));
    
            }   
    
        }
    
        $materials = $_POST["material"];
        
        
    
        //update the product
        $conn->query("
            UPDATE products 
            SET 
                product_name='$productName',
                category='$category',
                description='$description',
                production_duration='$productionDuration'
            WHERE 
                id = '$productId'
        ");


        //delete the previous price, price_specs, and product_materials
        $result = $conn->query("SELECT * FROM prices WHERE product_id = '$productId'");
        while($row = $result->fetch_assoc()){
            $priceId = $row["id"];
            $conn->query("DELETE FROM price_specs WHERE price_id = '$priceId'");
        }
        $conn->query("DELETE FROM prices WHERE product_id = '$productId'");
        $conn->query("DELETE FROM product_materials WHERE product_id = '$productId'");


        
        //insert the price    
        foreach($priceArray as $p){
            $pName = $p["price_name"];
            $pPrice = $p["price"];
    
            $conn->query("
                INSERT INTO prices (product_id, price, price_name) 
                VALUES ('$productId','$pPrice','$pName')
            ");
    
            $pId = $conn->query("SELECT id FROM prices ORDER BY id DESC LIMIT 1")->fetch_assoc()["id"];
    
            //insert the specs of the price
            foreach($p["specs"] as $s){
                $sId = $s["specs_id"];
                $sValue = $s["value"];
    
                $conn->query("
                    INSERT INTO price_specs(price_id, specs_id, value) 
                    VALUES ('$pId','$sId','$sValue')
                ");
            }
    
        }
    
        //insert the materials of the product
        foreach($materials as $m){
            $conn->query("
                INSERT INTO product_materials (product_id, material_id) 
                VALUES ('$productId', '$m')
            ");
        }
    
    
        //upload product image
        if(isset($_FILES["imageFile"])){
            $uploaddir = '../img/products/';
            $uploadfile = $uploaddir . $productId.'.png';
    
            move_uploaded_file($_FILES['imageFile']['tmp_name'], $uploadfile);
        }



        $message = 'The product has been edited successfully';
    }

    if($action === 'set_sale'){
        $productId = $_POST["productId"];
        $sale = $_POST["sale"];

        if($sale <= 0){
            $sale = 'NULL';
        }
        $conn->query("
            UPDATE products 
            SET
                sale = $sale
            WHERE id = $productId
        ");
        
    
    
        $message = 'The product sale has been set successfully';
    }

    if($action === 'archive_product'){
        $location = 'adm-furnitures.php';


        $productId = $_POST["productId"];

        $conn->query("
            UPDATE products
            SET archived = 1, date_archived = '$date'
            WHERE id = '$productId'
        ");

        $message = 'The product has been archived successfully';
    }

    if($action === 'restore_product'){
        $location = 'adm-archivedProducts.php';


        $productId = $_POST["productId"];

        $conn->query("
            UPDATE products
            SET archived = 0, date_archived = NULL
            WHERE id = '$productId'
        ");

        $message = 'The product has been restored successfully';
    }



    
    if($redirect){
        $_SESSION["message"] = $message;
        $_SESSION["res"] = $res;

        header("Location: ../$location");
        exit();
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