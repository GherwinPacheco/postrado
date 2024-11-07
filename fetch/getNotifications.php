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
if($request === 'order_notifs'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
        FROM notifications
        WHERE 1
    ";

    //where clause values
    $notifId = (isset($_GET["notif_id"]) and $_GET["notif_id"] !== '0') ? $_GET["notif_id"] : null;
    $notifStatus = isset($_GET["notif_status"]) ? $_GET["notif_status"] : null;
    $sessionUser = isset($_SESSION["user"]) ? $_SESSION["user"] : '0';
    $receiverId = isset($_GET["user"]) ? $_GET["user"] : $sessionUser;
    


    $whereQuery = '';
    $whereQuery .= $notifId ? "AND id = $notifId" : "";
    $whereQuery .= $notifStatus ? "AND notif_status = '$notifStatus'" : "";
    $whereQuery .= "AND receiver_id = $receiverId";
    $query .= $whereQuery;
    

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $notifData = array();
    $num = $offset + 1;
    


    while($row = $result->fetch_assoc()){
        array_push($notifData, array(
            'id' => $row['id'],
            'order_id' => $row['order_id'],
            'custom_id' => $row['custom_id'],
            'custom_name' => $row['custom_name'],
            'order_status' => $row['order_status'],
            'message' => $row['message'],
            'notif_status' => $row['notif_status'],
            'date_created' => date('Y-m-d', strtotime($row['date_created'])),
            'time_created' => date('h:i a', strtotime($row['date_created']))
        ));
    }
    //stores the carts data
    $data = json_encode($notifData);
    
}




echo $data;
?>
