<?php
require("../database/connection.php");
session_start();

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
if($request === 'users_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $loggedInUserQuery = isset($_SESSION["user"]) ? 'id != '.$_SESSION["user"] : '1';
    
    $query = "
        SELECT * FROM users
        WHERE $loggedInUserQuery";

    

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $email = isset($_GET["email"]) ? fixStr($_GET["email"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? " AND id = $id" : '';
    $whereQuery .= $email ? " AND email = '$email'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $usersArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){
        $role = '';
        if($row["role"] == 1){
            $role = 'Admin';
        }
        elseif($row["role"] == 2){
            $role = 'Carpenter';
        }
        elseif($row["role"] == 3){
            $role = 'Customer';
        }

        


        array_push($usersArray, array(
            'num' => $num,
            'id' => $row["id"],
            'username' => ucwords($row["username"]),
            'email' => $row["email"],
            'first_name' => ucwords($row["first_name"]),
            'last_name' => ucwords($row["last_name"]),
            'suffix' => ucwords($row["suffix"]),
            'home_address' => $row["home_address"],
            'contact' => $row["contact"],
            'role' => $row["role"],
            'status' => $row["status"]
        ));

        
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'users' => $usersArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($usersArray);
    }
}

//get all of unarchived products table JSON Format
if($request === 'categories_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *, 
        (SELECT COUNT(id) FROM products WHERE category = category.id AND archived = 0) AS product_count
        FROM category
        WHERE archived = 0
    ";

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $categoryArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){
        $categoryId = $row["id"];

        $categorySpecsArray = array();

        $specsResult = $conn->query("
            SELECT cs.*, sp.specs_name
            FROM category_specs cs
                INNER JOIN specs sp ON cs.specs_id = sp.id
            WHERE category_id = $categoryId
        ");

        while($sp = $specsResult->fetch_assoc()){
            array_push($categorySpecsArray, array(
                'specs_id' => $sp["specs_id"],
                'specs_name' => $sp["specs_name"]
            ));
        }


        array_push($categoryArray, array(
            'num' => $num,
            'id' => $row["id"],
            'category_name' => $row["category_name"],
            'product_count' => $row["product_count"],
            'specs' => $categorySpecsArray
        ));

        
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'categories' => $categoryArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($categoryArray);
    }
}

//get all of unarchived products table JSON Format
if($request === 'specs_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT * FROM specs
        WHERE 1
    ";

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $specsArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){


        array_push($specsArray, array(
            'num' => $num,
            'id' => $row["id"],
            'specs_name' => $row["specs_name"]
        ));

        
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'specs' => $specsArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($specsArray);
    }
}


//get all of unarchived products table JSON Format
if($request === 'units_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *, 
        (SELECT COUNT(id) FROM materials WHERE unit = unit_of_measurement.id AND archived = 0) AS material_count
        FROM unit_of_measurement
        WHERE archived = 0
    ";

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $unitArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){



        array_push($unitArray, array(
            'num' => $num,
            'id' => $row["id"],
            'unit_name' => $row["unit_name"],
            'material_count' => $row["material_count"]
        ));

        
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'categories' => $unitArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($unitArray);
    }
}

//get all of unarchived products table JSON Format
if($request === 'colors_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
        FROM color
        WHERE archived = 0
    ";

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $colorArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){



        array_push($colorArray, array(
            'num' => $num,
            'id' => $row["id"],
            'color_name' => $row["color_name"],
            'price' => $row["price"]
        ));

        
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'categories' => $colorArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($colorArray);
    }
}

//get all woods
if($request === 'wood_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
        FROM wood_type
        WHERE archived = 0
    ";

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $colorArray = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){



        array_push($colorArray, array(
            'num' => $num,
            'id' => $row["id"],
            'wood_name' => $row["wood_name"]
        ));

        
        $num++;
    }


    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'woods' => $colorArray,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($colorArray);
    }
}

//get terms data
if($request === 'terms_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
  
    //run the query
    $result = $conn->query("
        SELECT *
        FROM terms_conditions
        WHERE 1
    ");

    $termsData = array();
    while($row = $result->fetch_assoc()){
        array_push($termsData, array(
            'id' => $row["id"],
            'title' => $row["title"],
            'content' => $row["content"]
        ));
    }


    $data = json_encode($termsData);
}

//get cancel reasons
if($request === 'cancel_reasons'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
  
    $query = "
        SELECT *
        FROM reason_options
        WHERE archived = 0 AND type = 'customer_cancel'
    ";

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query($query);

    $reasonsData = array();
    while($row = $result->fetch_assoc()){
        array_push($reasonsData, array(
            'id' => $row["id"],
            'reason' => $row["reason"]
        ));
    }


    $data = json_encode($reasonsData);
}

//get decline reasons
if($request === 'decline_reasons'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $query = "
        SELECT *
        FROM reason_options
        WHERE archived = 0 AND type = 'admin_decline'
    ";

    //where clause for the status and category
    $id = isset($_GET["id"]) ? fixStr($_GET["id"]) : null;
    $whereQuery = '';
    $whereQuery .= $id ? "AND id = '$id'" : '';
    $query .= $whereQuery;
  
    //run the query
    $result = $conn->query($query);

    $reasonsData = array();
    while($row = $result->fetch_assoc()){
        array_push($reasonsData, array(
            'id' => $row["id"],
            'reason' => $row["reason"]
        ));
    }


    $data = json_encode($reasonsData);
}



echo $data;
?>
