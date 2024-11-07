<?php
session_start();
require("../database/connection.php");

$action = isset($_POST["action"]) ? $_POST["action"] : null;


$user = $_SESSION["user"];
$date = date("Y-m-d H:i:s");
$message = "Message";
$res = "success";
$location = '';
$redirect = true;

try{
    if($action === 'add_cart'){
        $redirect = false;

        $productId = $_POST["product_id"];
        $priceId = $_POST["price_id"];
        $quantity = $_POST["quantity"];
        $colorId = $_POST["color_id"];
        $varnish = $_POST["varnish"];

        $result = $conn->query("SELECT id FROM cart WHERE product_id = '$productId' AND price_id = '$priceId' AND color_id = '$colorId' AND varnish = '$varnish' AND added_by = '$user' LIMIT 1");
        $inCart = $result->num_rows > 0;
        if($inCart){
            $id = $result->fetch_assoc()["id"];
            $conn->query("
                UPDATE cart
                SET quantity = quantity + $quantity
                WHERE id = $id
            ");
        }
        else{
            $conn->query("
                INSERT INTO `cart`(
                    `product_id`, `price_id`, `quantity`, 
                    `color_id`, `varnish`, `date_created`, `added_by`
                ) VALUES (
                    '$productId','$priceId','$quantity',
                    '$colorId','$varnish','$date','$user'
                )
            ");
        }
        

        echo json_encode(array(
            'message' => 'Product has been added to cart',
            'res' => 'success'
        ));
    }


    if($action === 'update_cart'){
        $redirect = false;

        $cartId = $_POST["cart_id"];
        $productId = $_POST["product_id"];
        $priceId = $_POST["price_id"];
        $quantity = $_POST["quantity"];
        $colorId = $_POST["color_id"];
        $varnish = $_POST["varnish"];


        $result = $conn->query("
            SELECT * FROM cart 
            WHERE 
                product_id = '$productId' AND 
                price_id = '$priceId' AND 
                color_id = '$colorId' AND 
                varnish = '$varnish' AND 
                added_by = '$user' AND 
                id != '$cartId'
        ");

        $message = '';
        $res = '';

        $inCart = $result->num_rows > 0;
        if($inCart){
            $newQty = $quantity;
            while($row = $result->fetch_assoc()){
                $newQty += $row["quantity"];
            }

            //update cart details and add the quantity of identical cart items
            $conn->query("
                UPDATE cart
                SET 
                    product_id = '$productId', price_id = '$priceId', 
                    quantity = '$newQty', color_id = '$colorId', varnish = '$varnish'
                WHERE id = $cartId
            ");

            //remove all identical carts
            $conn->query("
                DELETE FROM cart
                WHERE 
                    product_id = '$productId' AND 
                    price_id = '$priceId' AND 
                    color_id = '$colorId' AND 
                    varnish = '$varnish' AND 
                    added_by = '$user' AND 
                    id != '$cartId'
            ");

            $message = 'Product merged to another cart item with same properties';
            $res = 'warning';
        }
        else{

            //update the cart details
            $conn->query("
                UPDATE cart
                SET 
                    product_id = '$productId', price_id = '$priceId', 
                    quantity = '$quantity', color_id = '$colorId', varnish = '$varnish'
                WHERE id = $cartId
            ");
        }


        echo json_encode(array(
            'message' => $message,
            'res' => $res
        ));
    }

    if($action === 'delete_cart'){
        $redirect = false;

        $cartId = json_decode($_POST["cart_id"]);

        if(is_array($cartId)){
            $ct = "";
            $count = 1;
            foreach($cartId as $id){
                $ct .= $id;
                if($count < count($cartId)){
                    $ct .= ",";
                }
                $count++;
            }
            $cartId = $ct;
        }


        $result = $conn->query("
            DELETE FROM cart 
            WHERE 
                id IN($cartId)
        ");


        echo json_encode(array(
            'message' => '',
            'res' => ''
        ));
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