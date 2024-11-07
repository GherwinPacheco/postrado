<?php
session_start();
require("../database/connection.php");

$request = isset($_GET["request"]) ? fixStr($_GET["request"]) : null;
$page = isset($_GET["page"]) ? fixStr($_GET["page"]) : 1;

$limit = isset($_GET["limit"]) ? fixStr($_GET["limit"]) : null;

//calculates the offset
$offset = $limit * ($page - 1);

$orderBy = isset($_GET["orderBy"]) ? fixStr($_GET["orderBy"]) : null;
$orderMethod = isset($_GET["orderMethod"]) ? fixStr($_GET["orderMethod"]) : null;

$limitQuery = $limit ? "LIMIT $limit" : '';
$offsetQuery = $limit ? "OFFSET $offset" : '';
$orderByQuery = $orderBy ? "ORDER BY `$orderBy`" : '';
$orderMethodQuery = $orderMethod ? $orderMethod : 'ASC';  //set order method to ASC when order method has no value
$orderMethodQuery = $orderBy ? $orderMethodQuery : '';  //clears order method when there is no orderBy value

    


$data = null;

//get all the data of carts (can be filtered out by user)
if($request === 'carts_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
            FROM (
                SELECT 
                    c.id, c.product_id, p.product_name, p.status, p.production_duration,
                    c.price_id, pc.price_name, pc.price AS product_price,
                    c.quantity, c.color_id, IFNULL(cl.color_name, '') AS color_name, IFNULL(cl.price, 0) AS color_price, 
                    c.varnish, c.date_created, c.added_by, u.username
                FROM cart c
                    LEFT JOIN products p ON c.product_id = p.id
                    LEFT JOIN prices pc ON c.price_id = pc.id
                    LEFT JOIN color cl ON c.color_id = cl.id
                    LEFT JOIN users u ON p.added_by = u.id
            ) as tbl
        WHERE 
            1
    ";

    //where clause values
    $cartId = (isset($_GET["cart_id"]) and $_GET["cart_id"] !== '0') ? $_GET["cart_id"] : null;
    $sessionUser = isset($_SESSION["user"]) ? $_SESSION["user"] : '0';
    $addedBy = isset($_GET["user"]) ? $_GET["user"] : $sessionUser;

    if(is_array(json_decode($cartId))){
        $cartId = json_decode($cartId);
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

    $whereQuery = '';
    $whereQuery .= $cartId ? "AND id IN($cartId)" : "";
    $whereQuery .= "AND added_by = '$addedBy'";
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $cartData = array();
    $num = $offset + 1;
    $varnishPrice = $conn->query("SELECT varnish_price FROM config WHERE 1")->fetch_assoc()["varnish_price"];


    while($row = $result->fetch_assoc()){
        $productId = $row['product_id'];
        $variantName = '';
        $variantPrice = 0;
        $variantsArray = array();
        $variants = $conn->query("SELECT * FROM prices WHERE product_id = '$productId'");
        while($vr = $variants->fetch_assoc()){
            if($row["price_id"] == $vr["id"]){
                $variantName = $vr["price_name"];
                $variantPrice = $vr["price"];
            }

            array_push($variantsArray, array(
                'id' => $vr["id"],
                'price_name' => $vr["price_name"],
                'price' => $vr["price"]
            ));
        }


        $colorsArray = array();
        $colors = $conn->query("SELECT * FROM color WHERE archived = 0");
        $colorName = '';
        $colorPrice = 0;
        while($col = $colors->fetch_assoc()){
            if($row["color_id"] == $col["id"]){
                $colorName = $col["color_name"];
                $colorPrice = $col["price"];
            }

            array_push($colorsArray, array(
                'id' => $col["id"],
                'color_name' => $col["color_name"],
                'price' => $col["price"]
            ));
        }

        $varnishTotal = $row["varnish"] !== "0" ? ($varnishPrice * $row["quantity"]) : 0;
        $colorTotal = ($colorPrice * $row["quantity"]);
        $productTotal = ($variantPrice * $row["quantity"]);

        $total = ($productTotal + $varnishTotal + $colorTotal);

        array_push($cartData, array(
            'id' => $row['id'],
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'product_status' => $row['status'],
            'quantity' => $row['quantity'],
            'production_duration' => $row["production_duration"],
            'product_variants' => $variantsArray,
            'selected_variant' => $row['price_id'],
            'variant_name' => $variantName,
            'variant_price' => $variantPrice,
            'product_total' => $productTotal,
            'colors' => $colorsArray,
            'selected_color' => $row['color_id'],
            'color_name' => $colorName,
            'color_price' => $colorPrice,
            'color_total' => $colorTotal,
            'varnish' => $row['varnish'],
            'varnish_price' => $varnishPrice,
            'varnish_total' => $varnishTotal,
            'total' => $total,
            'date_created' => $row['date_created'],
            'added_by' => $row['added_by'],
            'username' => $row['username'],

        ));
    }
    //stores the carts data
    $data = json_encode($cartData);
}






echo $data;
?>
