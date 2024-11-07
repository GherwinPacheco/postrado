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

    


$data = null;

//get all the data of carts (can be filtered out by user)
if($request === 'sales_overview'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $result = $conn->query("
        SELECT
            SUM(paid_amount) AS total_sales,
            SUM(quantity) AS products_sold
        FROM (
            SELECT oi.order_id AS order_id, SUM(oi.quantity) AS quantity, ord.paid_amount AS paid_amount
                FROM order_items oi
                    INNER JOIN orders ord ON oi.order_id = ord.id
                WHERE ord.paid_amount = ord.total

            GROUP BY oi.order_id
        ) AS tbl;
    ")->fetch_assoc();
    
    $activeOrders = $conn->query("SELECT COUNT(id) AS order_count FROM orders WHERE order_status = 'pending' OR order_status = 'preparing' OR order_status = 'ready'")->fetch_assoc()["order_count"];
    $completedOrders = $conn->query("SELECT COUNT(id) AS order_count FROM orders WHERE order_status = 'complete'")->fetch_assoc()["order_count"];
    $cancelledOrders = $conn->query("SELECT COUNT(id) AS order_count FROM orders WHERE order_status = 'cancelled'")->fetch_assoc()["order_count"];
    $declinedOrders = $conn->query("SELECT COUNT(id) AS order_count FROM orders WHERE order_status = 'declined'")->fetch_assoc()["order_count"];
    

    $data = json_encode(array(
        'total_sales' => $result["total_sales"],
        'products_sold' => $result["products_sold"],
        'active_orders' => $activeOrders,
        'completed_orders' => $completedOrders,
        'cancelled_orders' => ($declinedOrders + $cancelledOrders)
        
    ));
}

if($request === 'sales_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $type = $_GET["sales_type"];
    $month = $_GET["month"];
    $year = $_GET["year"];

    $whereQuery = '';
    $dateColumn = '';
    
    $salesData = array();
    if($type === 'daily'){
        if($month == date("m") and $year == date("Y")){
            $endDate = date('d');
        }
        else{
            $endDate = date('t', strtotime("$year-$month-1"));
        }

        for($i = 1; $i <= $endDate; $i++){
            $result = $conn->query("
                SELECT
                    IFNULL(SUM(paid_amount), 0) AS total_sales,
                    IFNULL(SUM(quantity), 0) AS products_sold,
                    IFNULL(COUNT(order_id), 0) AS total_orders
                FROM (
                    SELECT oi.order_id AS order_id, SUM(oi.quantity) AS quantity, ord.paid_amount AS paid_amount
                        FROM order_items oi
                            INNER JOIN orders ord ON oi.order_id = ord.id
                        WHERE 
                            (DATE(ord.date_created) = '$year-$month-$i' OR 
                            DATE(ord.date_completed) = '$year-$month-$i')
                            AND ord.paid_amount = ord.total
                    GROUP BY oi.order_id
                ) AS tbl;
            ")->fetch_assoc();

            array_push($salesData, array(
                'date' => date("M d", strtotime("$year-$month-$i")),
                'total_sales' => $result["total_sales"],
                'products_sold' => $result["products_sold"],
                'total_orders' => $result["total_orders"]
            ));
        }

    }
    elseif($type === 'weekly'){
        $weekBasis = [
            [1, 7],
            [8, 14],
            [15, 21],
            [22, 31]
        ];

        for($i = 0; $i < 4; $i++){
            $weekNo = $i+1;
            $days = $weekBasis[$i];
            $first = $days[0];
            $last = $days[1];

            $result = $conn->query("
                SELECT
                    IFNULL(SUM(paid_amount), 0) AS total_sales,
                    IFNULL(SUM(quantity), 0) AS products_sold,
                    IFNULL(COUNT(order_id), 0) AS total_orders
                FROM (
                    SELECT oi.order_id AS order_id, SUM(oi.quantity) AS quantity, ord.paid_amount AS paid_amount
                        FROM order_items oi
                            INNER JOIN orders ord ON oi.order_id = ord.id
                        WHERE
                            ((DATE(date_created) BETWEEN '$year-$month-$first' AND '$year-$month-$last')
                            OR
                            (DATE(date_completed) BETWEEN '$year-$month-$first' AND '$year-$month-$last'))
                            AND ord.paid_amount = ord.total

                    GROUP BY oi.order_id
                ) AS tbl;
            ")->fetch_assoc();

            array_push($salesData, array(
                'date' => "Week $weekNo",
                'total_sales' => $result["total_sales"],
                'products_sold' => $result["products_sold"],
                'total_orders' => $result["total_orders"]
            ));
        }
    }
    elseif($type === 'monthly'){
        if($year == date("Y")){
            $endDate = date('m');
        }
        else{
            $endDate = 12;
        }

        for($i = 1; $i <= $endDate; $i++){
            $result = $conn->query("
                SELECT
                    IFNULL(SUM(paid_amount), 0) AS total_sales,
                    IFNULL(SUM(quantity), 0) AS products_sold,
                    IFNULL(COUNT(order_id), 0) AS total_orders
                FROM (
                    SELECT oi.order_id AS order_id, SUM(oi.quantity) AS quantity, ord.paid_amount AS paid_amount
                        FROM order_items oi
                            INNER JOIN orders ord ON oi.order_id = ord.id
                        WHERE
                            ((YEAR(date_created) = '$year' AND MONTH(date_created) = '$i')
                            OR
                            (YEAR(date_completed) = '$year' AND MONTH(date_completed) = '$i'))
                            AND ord.paid_amount = ord.total

                    GROUP BY oi.order_id
                ) AS tbl;
            ")->fetch_assoc();

            array_push($salesData, array(
                'date' => date("M Y", strtotime("$year-$i-01")),
                'total_sales' => $result["total_sales"],
                'products_sold' => $result["products_sold"],
                'total_orders' => $result["total_orders"]
            ));
        }

    }
    elseif($type === 'yearly'){
        $firstYear = $conn->query("SELECT (MIN(YEAR(date_completed)) - 2) AS min_year FROM orders WHERE order_status = 'complete'")->fetch_assoc()["min_year"];

        for($i = $firstYear; $i <= date("Y"); $i++){
            $result = $conn->query("
                SELECT
                    IFNULL(SUM(paid_amount), 0) AS total_sales,
                    IFNULL(SUM(quantity), 0) AS products_sold,
                    IFNULL(COUNT(order_id), 0) AS total_orders
                FROM (
                    SELECT oi.order_id AS order_id, SUM(oi.quantity) AS quantity, ord.paid_amount AS paid_amount
                        FROM order_items oi
                            INNER JOIN orders ord ON oi.order_id = ord.id
                        WHERE
                            (YEAR(date_created) = '$i' OR
                            YEAR(date_completed) = '$i')
                            AND ord.paid_amount = ord.total

                    GROUP BY oi.order_id
                ) AS tbl;
            ")->fetch_assoc();

            array_push($salesData, array(
                'date' => date("Y", strtotime("$i-01-01")),
                'total_sales' => $result["total_sales"],
                'products_sold' => $result["products_sold"],
                'total_orders' => $result["total_orders"]
            ));
        }

    }
    $data = json_encode($salesData);
}

if($request === 'top_categories'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $query = "
        SELECT ctg.category_name, COUNT(oi.order_id) AS total_orders, SUM(oi.quantity) AS products_sold, SUM(oi.total) AS total_sales
        FROM order_items oi
            INNER JOIN orders ord ON oi.order_id = ord.id
            INNER JOIN products p ON oi.product_id = p.id
            INNER JOIN category ctg ON p.category = ctg.id
        WHERE
            ord.paid_amount = ord.total
        GROUP BY ctg.category_name
        ORDER BY total_sales DESC
    
    ";
    $result = $conn->query("
        $query
        $limitQuery
    ");

    $categoryData = array();
    while($row = $result->fetch_assoc()){
        array_push($categoryData, array(
            'category' => $row["category_name"],
            'total_sales' => $row["total_sales"],
            'products_sold' => $row["products_sold"],
            'total_orders' => $row["total_orders"]
        ));
    }
    
    
    $data = json_encode($categoryData);
}

if($request === 'top_products'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $query = "
        SELECT oi.product_name, COUNT(oi.order_id) AS total_orders, SUM(oi.quantity) AS products_sold, SUM(oi.total) AS total_sales
        FROM order_items oi
            INNER JOIN orders ord ON oi.order_id = ord.id
        WHERE
            ord.paid_amount = ord.total AND
            oi.type = 'normal'
        GROUP BY oi.product_name
        ORDER BY total_sales DESC
    ";

    $result = $conn->query("
        $query
        $limitQuery
    ");

    $productsData = array();
    while($row = $result->fetch_assoc()){
        array_push($productsData, array(
            'product' => $row["product_name"],
            'total_sales' => $row["total_sales"],
            'products_sold' => $row["products_sold"],
            'total_orders' => $row["total_orders"]
        ));
    }
    
    
    $data = json_encode($productsData);
}

if($request === 'sold_products'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $query = "
        SELECT oi.*, ord.date_completed
        FROM order_items oi 
            INNER JOIN orders ord ON oi.order_id = ord.id
        WHERE 
            ord.paid_amount = ord.total
        ORDER BY ord.date_completed DESC
    ";

    $result = $conn->query("
        $query
        $limitQuery
        $offsetQuery
    ");

    $productsData = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){
        $variantName = $row["variant_name"] != null ? "(".$row["variant_name"].")" : "";
        array_push($productsData, array(
            'num' => $num,
            'product_name' => $row["product_name"]." ".$variantName,
            'quantity' => $row["quantity"],
            'total' => $row["total"],
            'date' => date("M d, Y", strtotime($row["date_completed"]))
        ));
        $num++;
    }
    
    
    if($limit){
        $totalData = $conn->query($query)->num_rows;
        $totalPages = intdiv($totalData, $limit);
        $totalPages = ($totalData % $limit > 0) ? ($totalPages + 1): $totalPages;

        $data = json_encode(array(
            'products' => $productsData,
            'total_data' => $totalData,
            'total_pages' => $totalPages
        ));
    } 
    else{ 
        $data = json_encode($productsData);
    }
}




//inventory reports
if($request === 'inventory_overview'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $result = $conn->query("
        SELECT 
            SUM(cost) AS inventory_total,
            IFNULL((SELECT COUNT(id) FROM materials WHERE archived = 0 AND status = 1), 0) AS in_stock,
            IFNULL((SELECT COUNT(id) FROM materials WHERE archived = 0 AND status = 2), 0) AS low_stock,
            IFNULL((SELECT COUNT(id) FROM materials WHERE archived = 0 AND status = 3), 0) AS out_of_stock
        FROM materials 
        WHERE archived = 0;
    ")->fetch_assoc();
    

    $data = json_encode(array(
        'inventory_total' => $result["inventory_total"],
        'in_stock' => $result["in_stock"],
        'low_stock' => $result["low_stock"],
        'out_of_stock' => $result["out_of_stock"]
        
    ));
}

if($request === 'material_list'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    
    $query = "
        SELECT * FROM materials
        WHERE 
            archived = 0
    ";
    $status = isset($_GET["status"]) ? $_GET["status"] : 'status';
    
    $whereQuery = '';
    $whereQuery .= "AND status = $status";
    $query .= $whereQuery;
    

    $result = $conn->query("
        $query
        $limitQuery
        $offsetQuery
    ");

    $materialsData = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){
        $status = '';
        if($row["status"] == 1){
            $status = 'In Stock';
        }
        elseif($row["status"] == 2){
            $status = 'Low Stock';
        }
        elseif($row["status"] == 3){
            $status = 'Out of Stock';
        }

        array_push($materialsData, array(
            'num' => $num,
            'material_name' => $row["material_name"],
            'quantity' => $row["quantity"],
            'cost' => $row["cost"],
            'status' => $status
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


if($request === 'materials_usage'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $query = "
        SELECT mu.*, m.material_name, uom.unit_name
        FROM material_usage mu
            INNER JOIN materials m ON mu.material_id = m.id
            INNER JOIN unit_of_measurement uom ON m.unit = uom.id
        WHERE 1
        ORDER BY mu.date_created DESC
    ";

    $result = $conn->query("
        $query
        $limitQuery
        $offsetQuery
    ");

    $materialsData = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){

        array_push($materialsData, array(
            'num' => $num,
            'material_name' => $row["material_name"],
            'quantity' => $row["quantity"],
            'unit_name' => $row["unit_name"],
            'mode' => $row["mode"],
            'date' => date("M d, Y", strtotime($row["date_created"]))
        ));
        $num++;
    }
    
    
    if($limit){
        $totalData = $conn->query($query)->num_rows;

        $isLastPage = ($offset + $limit) >= $totalData;

        $isLastPage = 
        $data = json_encode(array(
            'materials' => $materialsData,
            'complete' => $isLastPage
        ));
    } 
    else{ 
        $data = json_encode($materialsData);
    }
}


echo $data;
?>


<?php
/*
SELECT mu.*, m.material_name 
FROM material_usage mu
	INNER JOIN materials m ON mu.material_id = m.id
WHERE 1;
*/
?>