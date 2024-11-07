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
if($request === 'orders_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT * FROM (
            SELECT o.*, u.username 
            FROM orders o
                INNER JOIN users u ON o.added_by = u.id    
        ) AS tbl
        WHERE 1
    ";

    //where clause for the status and category
    $status = isset($_GET["status"]) ? fixStr($_GET["status"]) : null;
    $user = isset($_GET["user"]) ? fixStr($_GET["user"]) : null;
    $orderId = isset($_GET["order_id"]) ? fixStr($_GET["order_id"]) : null;
    $search = isset($_GET["search"]) ? fixStr($_GET["search"]) : null;
    $whereQuery = '';
    $whereQuery .= $status ? "AND order_status = '$status'" : '';
    $whereQuery .= $user ? "AND added_by = '$user'" : '';
    $whereQuery .= $orderId ? "AND id = '$orderId'" : '';
    $whereQuery .= $search ? "AND CONCAT('ORD-', id) LIKE '%$search%'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $ordersArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){
        $orderItemsArray = array();

        $orderId = $row["id"];
        $orderItems = $conn->query("
            SELECT oi.*, c.color_name
            FROM order_items oi
                LEFT JOIN color c ON oi.color_id = c.id 
            WHERE oi.order_id = $orderId
        ");
        while($item = $orderItems->fetch_assoc()){
            array_push($orderItemsArray, array(
                'id' => $item["id"],
                'order_id' => $item["order_id"],
                'type' => $item["type"],
                'product_id' => $item["product_id"],
                'product_name' => $item["product_name"],
                'quantity' => $item["quantity"],
                'variant_name' => $item["variant_name"],
                'variant_price' => $item["variant_price"],
                'varnish_price' => $item["varnish_price"],
                'color_id' => $item["color_id"],
                'color_name' => $item["color_name"],
                'color_price' => $item["color_price"],
                'item_total' => $item["total"],
            ));
        }

        $downPercent = $conn->query("SELECT down_payment FROM config WHERE 1 LIMIT 1")->fetch_assoc()["down_payment"];
        $downPayment =  ($downPercent / 100) * $row["total"];

        array_push($ordersArray, array(
            'num' => $num,
            'id' => $row["id"],
            'home_address' => ucwords($row["home_address"]),
            'contact' => $row["contact"],
            'completion_date' => $row["completion_date"],
            'payment_method' => ucwords($row["payment_method"]),
            'pickup_method' => ucwords($row["pickup_method"]),
            'service_fee' => $row["service_fee"],
            'total' => $row["total"],
            'down_percent' => $downPercent,
            'down_payment' => $downPayment,
            'paid_amount' => $row["paid_amount"],
            'order_status' => ucwords($row["order_status"]),
            'date_created' => $row["date_created"],
            'added_by' => $row["added_by"],
            'username' => $row["username"],
            'date_completed' => date("Y-m-d", strtotime($row["date_completed"])),
            'cancel_details' => $row["cancel_details"],
            'order_items' => $orderItemsArray
        ));
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'orders' => $ordersArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($ordersArray);
    }
}


echo $data;
?>
