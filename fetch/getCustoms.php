<?php
require("../database/connection.php");

$config = $conn->query("SELECT * FROM config WHERE 1 LIMIT 1")->fetch_assoc();

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
if($request === 'customs_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT * FROM (
            SELECT cp.*, ct.category_name, IFNULL(cl.color_name, '') AS color_name, IFNULL(cl.price, 0) AS color_price, w.wood_name, u.username, u.home_address, u.contact
            FROM custom_products cp
                INNER JOIN users u ON cp.added_by = u.id
                LEFT JOIN color cl ON cp.color_id = cl.id
                LEFT JOIN wood_type w ON cp.wood_id = w.id
                LEFT JOIN category ct ON cp.category_id = ct.id
        ) AS tbl
        WHERE 1
    ";

    //where clause for the status and category
    $status = isset($_GET["status"]) ? fixStr($_GET["status"]) : null;
    $user = isset($_GET["user"]) ? fixStr($_GET["user"]) : null;
    $customId = isset($_GET["custom_id"]) ? fixStr($_GET["custom_id"]) : null;
    $search = isset($_GET["search"]) ? fixStr($_GET["search"]) : null;
    $whereQuery = '';
    $whereQuery .= $status ? "AND request_status = '$status'" : '';
    $whereQuery .= $user ? "AND added_by = '$user'" : '';
    $whereQuery .= $customId ? "AND id = '$customId'" : '';
    $whereQuery .= $search ? "AND product_name LIKE '%$search%'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");

    $varnishPriceConfig = $config["varnish_price"];

    $customsArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){
        $varnishPrice = $row["varnish"] == 1 ? $varnishPriceConfig : 0;


        $specsArray = array();

        $customId = $row["id"];
        $specs = $conn->query("SELECT * FROM custom_product_specs WHERE custom_id = $customId");
        while($sp = $specs->fetch_assoc()){
            array_push($specsArray, array(
                'specs_name' => $sp["specs_name"],
                'specs_value' => $sp["specs_value"],
            ));
        }


        

        $serviceFee = 0;
        if($row["pickup_method"] === 'for deliver'){
            $serviceFee = $config["for_deliver"];
        }
        elseif($row["pickup_method"] === 'for installation'){
            $serviceFee = $config["for_install"];
        }
        elseif($row["pickup_method"] === 'deliver and install'){
            $serviceFee = $config["deliver_install"];
        }

        array_push($customsArray, array(
            'num' => $num,
            'id' => $customId,
            'product_name' => $row["product_name"],
            'category_id' => $row["category_id"],
            'category_name' => $row["category_name"],
            'product_price' => $row["price"],
            'quantity' => $row["quantity"],
            'varnish' => $row["varnish"],
            'varnish_price' => $varnishPrice,
            'color_id' => $row["color_id"],
            'color_name' => $row["color_name"],
            'color_price' => $row["color_price"],
            'wood_id' => $row["wood_id"],
            'wood_name' => $row["wood_name"],
            'description' => $row["description"],
            'down_payment' => $row["down_payment"],
            'down_percent' => $config["down_payment"],
            'completion_date' => $row["completion_date"],
            'pickup_method' => $row["pickup_method"],
            'service_fee' => $serviceFee,
            'request_status' => $row["request_status"],
            'cancel_details' => $row["cancel_details"],
            'date_created' => date("Y-m-d", strtotime($row["date_created"])),
            'time_created' => date("h:i a", strtotime($row["date_created"])),
            'added_by' => $row["added_by"],
            'username' => $row["username"],
            'home_address' => $row["home_address"],
            'contact' => $row["contact"],
            'image_count' => $row["image_count"],
            'sketch_count' => $row["sketch_count"],
            'specs' => $specsArray
        ));


        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'customs' => $customsArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($customsArray);
    }
}



echo $data;
?>
