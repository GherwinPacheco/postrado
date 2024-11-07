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

//get all of unarchived materials table JSON Format
if($request === 'materials_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
            FROM (
                SELECT 
                    m.*, un.unit_name, u.username
                FROM materials m
                    INNER JOIN unit_of_measurement un ON m.unit = un.id
                    INNER JOIN users u ON m.added_by = u.id
            ) as tbl
        WHERE 1
    ";

    //where clause for the status and search
    $search = (isset($_GET["search"]) and $_GET["search"] !== '') ? fixStr($_GET["search"]) : null;
    $status = (isset($_GET["status"]) and $_GET["status"] !== 'all') ? fixStr($_GET["status"]) : null;
    $archived = (isset($_GET["archived"])) ? $_GET["archived"] : null;

    $whereQuery = '';
    $whereQuery .= $search ? "AND material_name LIKE '%$search%'" : '';
    $whereQuery .= $status ? "AND status = '$status'" : '';
    $whereQuery .= $archived != null ? "AND archived = '$archived'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");

    


    $materialsData = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){

        //get the materials and store as json
        array_push($materialsData, array(
            'num' => $num,
            'id' => $row['id'],
            'material_name' => $row['material_name'],
            'quantity' => $row['quantity'],
            'minimum_qty' => $row['minimum_qty'],
            'unit' => $row['unit'],
            'unit_name' => $row['unit_name'],
            'cost' => $row['cost'],
            'status' => $row['status'],
            'date_created' => date("Y-m-d", strtotime($row['date_created'])),
            'time_created' => date("h:i a", strtotime($row['date_created'])),
            'date_archived' => $row["date_archived"] ? date("Y-m-d", strtotime($row['date_archived'])) : null,
            'time_archived' => $row["date_archived"] ? date("h:i a", strtotime($row['date_archived'])) : null,
            'added_by' => $row['added_by'],
            'username' => $row['username']
        ));
        $num++;

    }

    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'materials' => $materialsData,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($materialsData);
    }

}

if($request === 'material_details'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
            FROM (
                SELECT 
                    m.*, un.unit_name, u.username
                FROM materials m
                    INNER JOIN unit_of_measurement un ON m.unit = un.id
                    INNER JOIN users u ON m.added_by = u.id
            ) as tbl
        WHERE 1
    ";

    //where clause for the status and category
    $materialId = isset($_GET["material_id"]) ? fixStr($_GET["material_id"]) : null;
    $whereQuery = '';
    $whereQuery .= "AND id = '$materialId'";
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $materialsData = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){

        //get the materials and store as json
        $materialsData = array(
            'num' => $num,
            'id' => $row['id'],
            'material_name' => $row['material_name'],
            'quantity' => $row['quantity'],
            'minimum_qty' => $row['minimum_qty'],
            'unit' => $row['unit'],
            'unit_name' => $row['unit_name'],
            'cost' => $row['cost'],
            'status' => $row['status'],
            'date_created' => date("Y-m-d", strtotime($row['date_created'])),
            'time_created' => date("h:i a", strtotime($row['date_created'])),
            'date_archived' => $row["date_archived"] ? date("Y-m-d", strtotime($row['date_archived'])) : null,
            'time_archived' => $row["date_archived"] ? date("h:i a", strtotime($row['date_archived'])) : null,
            'added_by' => $row['added_by'],
            'username' => $row['username']
        );
        $num++;

    }

    //stores the total data, total pages, and the data of materials to data variable
    $data = json_encode($materialsData);
}



echo $data;
?>
