<?php
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

//get all of unarchived products table JSON Format
if($request === 'production_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT * FROM (
            SELECT oi.*, cl.color_name, ord.date_created, ord.completion_date AS deadline, ord.date_approved, ord.order_status, us.username
            FROM order_items oi
                INNER JOIN orders ord ON oi.order_id = ord.id
                LEFT JOIN color cl ON oi.color_id = cl.id
                INNER JOIN users us ON ord.added_by = us.id
        ) AS tbl
        WHERE order_status = 'preparing'
    ";
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $search = isset($_GET["search"]) ? fixStr($_GET["search"]) : null;
    $type = isset($_GET["type"]) ? fixStr($_GET["type"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $whereQuery .= $search ? "AND product_name LIKE '%$search%'" : '';
    $whereQuery .= $type ? "AND type LIKE '%$type%'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        ORDER BY date_approved DESC, order_id DESC
        $limitQuery
        $offsetQuery
    ");

    $productionData = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){
        $productName = $row["product_name"].' '.($row["variant_name"] ? '('.$row["variant_name"].')' : '');
        $category = '';
        $description = '';
        $varnish = $row["varnish_price"] > 0 ? 'Varnished' : 'No Varnish';
        $color = $row["color_price"] > 0 ? $row["color_name"] : 'None';
        $woodType = '';
        $imageCount = 0;
        $sketchCount = 0;
        $productSpecs = array();

        $productId = $row["product_id"];
        $priceId = $row["price_id"];

        if($row["type"] === 'normal'){
            $product = $conn->query("
                SELECT prd.*, ctg.category_name
                FROM products prd
                    INNER JOIN category ctg ON prd.category = ctg.id
                WHERE prd.id = $productId")->fetch_assoc();
            
            $category = $product["category_name"];
            $description = $product["description"];
            $specsResult = $conn->query("
                SELECT ps.*, sp.specs_name 
                FROM price_specs ps
                    INNER JOIN specs sp ON ps.specs_id = sp.id
                WHERE ps.price_id = $priceId
            ");

            while($sp = $specsResult->fetch_assoc()){
                array_push($productSpecs, array(
                    'specs_name' => $sp["specs_name"],
                    'value' => $sp["value"]
                ));
            }


        }
        elseif($row["type"] === 'custom'){
            $product = $conn->query("
                SELECT cp.*, ctg.category_name, w.wood_name
                FROM custom_products cp
                    INNER JOIN category ctg ON cp.category_id = ctg.id
                    INNER JOIN wood_type w ON cp.wood_id = w.id
                WHERE cp.id = $productId")->fetch_assoc();
            
            $category = $product["category_name"];
            $description = $product["description"];
            $woodType = $product["wood_name"];
            $imageCount = $product["image_count"];
            $sketchCount = $product["sketch_count"];

            $specsResult = $conn->query("
                SELECT *
                FROM custom_product_specs
                WHERE custom_id = $productId
            ");

            while($sp = $specsResult->fetch_assoc()){
                

                array_push($productSpecs, array(
                    'specs_name' => $sp["specs_name"],
                    'value' => $sp["specs_value"]
                ));
            }



        }




        array_push($productionData, array(
            'num' => $num,
            'id' => $row["id"],
            'order_id' => $row["order_id"],
            'type' => $row["type"],
            'product_id' => $row["product_id"],
            'product_name' => $productName,
            'category' => $category,
            'description' => $description,
            'quantity' => $row["quantity"],
            'varnish' => $varnish,
            'color' => $color,
            'wood' => $woodType,
            'date_created' => date("F d, Y", strtotime($row["date_created"])),
            'deadline' => date("F d, Y", strtotime($row["deadline"])),
            'customer' => $row["username"],
            'image_count' => $imageCount,
            'sketch_count' => $sketchCount,
            'date_approved' => $row["date_approved"],
            'specs' => $productSpecs
        ));

        
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'productions' => $productionData,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($productionData);
    }
}



echo $data;
?>
