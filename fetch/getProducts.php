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
if($request === 'products_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
            FROM (
                SELECT 
                    p.*, c.category_name, u.username
                FROM products p
                    INNER JOIN category c ON p.category = c.id
                    INNER JOIN users u ON p.added_by = u.id
            ) as tbl
        WHERE 
            1
    ";

    //where clause for the status and category
    $search = (isset($_GET["search"]) and $_GET["search"] !== '') ? fixStr($_GET["search"]) : null;
    $status = (isset($_GET["status"]) and $_GET["status"] !== 'all') ? fixStr($_GET["status"]) : null;
    $category = (isset($_GET["category"]) and $_GET["category"] !== 'all') ? fixStr($_GET["category"]) : null;
    $archived = (isset($_GET["archived"])) ? $_GET["archived"] : null;

    $whereQuery = '';
    $whereQuery .= $search ? "AND product_name LIKE '%$search%'" : '';
    $whereQuery .= $status !== null ? "AND status = '$status'" : '';
    $whereQuery .= $category ? "AND category = '$category'" : '';
    $whereQuery .= $archived != null ? "AND archived = '$archived'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $productsData = array();
    $num = $offset + 1;
    while($row = $result->fetch_assoc()){

        //get the prices of the product
        $priceArray = array();
        $productId = $row["id"];
        $priceResult = $conn->query("
            SELECT *
            FROM prices
            WHERE 
                product_id = '$productId'
            ORDER BY price ASC
        ");
        while($row2 = $priceResult->fetch_assoc()){

            //get the specs of the product based on the category specs
            $priceSpecsArray = array();
            $priceId = $row2["id"];

            $categoryId = $row["category"];
            $categoryResult = $conn->query("
                SELECT cs.*, s.specs_name
                FROM category_specs cs
                    INNER JOIN specs s ON cs.specs_id = s.id
                WHERE cs.category_id = $categoryId
            ");
            while($row3 = $categoryResult->fetch_assoc()){
                $specsId = $row3["specs_id"];

                //get the value of specs
                $specsValue = $conn->query("
                    SELECT IFNULL((
                        SELECT value FROM price_specs WHERE price_id = $priceId AND specs_id = $specsId
                    ), '') as value
                ")->fetch_assoc()["value"];

                array_push($priceSpecsArray, array(
                    'specs_name' => $row3['specs_name'],
                    'value' => $specsValue
                ));
            }

            $salePrice = $row2["price"] - ($row2["price"] * ($row["sale"] / 100));
            
            array_push($priceArray, array(
                'id' => $row2['id'],
                'price' => $row2['price'],
                'sale_price' => $salePrice,
                'price_name' => $row2['price_name'],
                'price_specs' => $priceSpecsArray
            ));
        }


        //get the materials of the product
        $materialsArray = array();
        $productId = $row["id"];
        $materialsResult = $conn->query("
            SELECT pm.*, m.material_name, m.status
            FROM product_materials pm
                INNER JOIN materials m ON pm.material_id = m.id
            WHERE 
                pm.product_id = '$productId' AND
                m.archived = 0
        ");
        while($row2 = $materialsResult->fetch_assoc()){
            array_push($materialsArray, array(
                'id' => $row2['id'],
                'material_id' => $row2["material_id"],
                'material_name' => $row2['material_name'],
                'status' => $row2["status"]
            ));
        }
        


        //get the products and store as json
        array_push($productsData, array(
            'num' => $num,
            'id' => $row['id'],
            'product_name' => $row['product_name'],
            'category' => $row['category'],
            'category_name' => $row['category_name'],
            'description' => $row['description'],
            'sale' => $row["sale"],
            'status' => $row['status'],
            'production_duration' => $row['production_duration'],
            'date_created' => date("Y-m-d", strtotime($row['date_created'])),
            'time_created' => date("h:i a", strtotime($row['date_created'])),
            'date_archived' => $row["date_archived"] ? date("Y-m-d", strtotime($row['date_archived'])) : null,
            'time_archived' => $row["date_archived"] ? date("h:i a", strtotime($row['date_archived'])) : null,
            'added_by' => $row['added_by'],
            'username' => $row['username'],
            'prices' => $priceArray,
            'materials' => $materialsArray
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

//details of products JSON Format
if($request === 'product_details'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $query = "
        SELECT *
            FROM (
                SELECT 
                    p.*, c.category_name, u.username
                FROM products p
                    INNER JOIN category c ON p.category = c.id
                    INNER JOIN users u ON p.added_by = u.id
            ) as tbl
        WHERE 1
    ";

    //where clause for the status and category
    $productId = isset($_GET["product_id"]) ? fixStr($_GET["product_id"]) : null;
    $whereQuery = '';
    $whereQuery .= "AND id = '$productId'";
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");


    $productsData = array();
    while($row = $result->fetch_assoc()){

        //get the prices of the product
        $priceArray = array();
        $productId = $row["id"];
        $priceResult = $conn->query("
            SELECT *
            FROM prices
            WHERE 
                product_id = '$productId'
            ORDER BY price ASC
        ");
        while($row2 = $priceResult->fetch_assoc()){

            //get the specs of the product based on the category specs
            $priceSpecsArray = array();
            $priceId = $row2["id"];

            $categoryId = $row["category"];
            $categoryResult = $conn->query("
                SELECT cs.*, s.specs_name
                FROM category_specs cs
                    INNER JOIN specs s ON cs.specs_id = s.id
                WHERE cs.category_id = $categoryId
            ");
            while($row3 = $categoryResult->fetch_assoc()){
                $specsId = $row3["specs_id"];

                //get the value of specs
                $specsValue = $conn->query("
                    SELECT IFNULL((
                        SELECT value FROM price_specs WHERE price_id = $priceId AND specs_id = $specsId
                    ), '') as value
                ")->fetch_assoc()["value"];

                array_push($priceSpecsArray, array(
                    'specs_name' => $row3['specs_name'],
                    'value' => $specsValue
                ));
            }

            $salePrice = $row2["price"] - ($row2["price"] * ($row["sale"] / 100));

            array_push($priceArray, array(
                'id' => $row2['id'],
                'price' => $row2['price'],
                'sale_price' => $salePrice,
                'price_name' => $row2['price_name'],
                'price_specs' => $priceSpecsArray
            ));
        }



        //get the materials of the product
        $materialsArray = array();
        $productId = $row["id"];
        $materialsResult = $conn->query("
            SELECT pm.*, m.material_name, m.status
            FROM product_materials pm
                INNER JOIN materials m ON pm.material_id = m.id
            WHERE 
                pm.product_id = '$productId' AND
                m.archived = 0
        ");
        while($row2 = $materialsResult->fetch_assoc()){
            array_push($materialsArray, array(
                'id' => $row2['id'],
                'material_id' => $row2["material_id"],
                'material_name' => $row2['material_name'],
                'status' => $row2["status"]
            ));
        }
        


        //get the products and store as json
        $productsData = array(
            'id' => $row['id'],
            'product_name' => $row['product_name'],
            'category' => $row['category'],
            'category_name' => $row['category_name'],
            'description' => $row['description'],
            'sale' => $row["sale"],
            'status' => $row['status'],
            'production_duration' => $row['production_duration'],
            'date_created' => date("Y-m-d", strtotime($row['date_created'])),
            'time_created' => date("h:i a", strtotime($row['date_created'])),
            'date_archived' => $row["date_archived"] ? date("Y-m-d", strtotime($row['date_archived'])) : null,
            'time_archived' => $row["date_archived"] ? date("h:i a", strtotime($row['date_archived'])) : null,
            'added_by' => $row['added_by'],
            'username' => $row['username'],
            'prices' => $priceArray,
            'materials' => $materialsArray
        );
    }

    //stores the total data, total pages, and the data of products to data variable
    $data = json_encode($productsData);
}


//prices of products in JSON Format
if($request === 'price_details'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');
    
    $priceId = isset($_GET["price_id"]) ? fixStr($_GET["price_id"]) : null;
    $productId = $conn->query("SELECT product_id FROM prices WHERE id = $priceId")->fetch_assoc()["product_id"];
    $product = $conn->query("SELECT category, sale FROM products WHERE id = $productId")->fetch_assoc();
    $categoryId = $product["category"];
    $sale = $product["sale"];
    
    //query for prices
    $price = $conn->query("
        SELECT *
        FROM prices
        WHERE 
            id = '$priceId'
        ORDER BY price ASC
    ")->fetch_assoc();


    $priceArray = array();
    $priceSpecsArray = array();
    
    $categoryResult = $conn->query("
        SELECT cs.*, s.specs_name
        FROM category_specs cs
            INNER JOIN specs s ON cs.specs_id = s.id
        WHERE cs.category_id = $categoryId
    ");
    while($row = $categoryResult->fetch_assoc()){
        $specsId = $row["specs_id"];

        //get the value of specs
        $specsValue = $conn->query("
            SELECT IFNULL((
                SELECT value FROM price_specs WHERE price_id = $priceId AND specs_id = $specsId
            ), '') as value
        ")->fetch_assoc()["value"];

        array_push($priceSpecsArray, array(
            'specs_name' => $row['specs_name'],
            'value' => $specsValue
        ));
    }

    $salePrice = $price["price"] - ($price["price"] * ($sale / 100));

    $priceArray = array(
        'id' => $price['id'],
        'price' => $price['price'],
        'sale_price' => $salePrice,
        'price_name' => $price['price_name'],
        'price_specs' => $priceSpecsArray
    );
    

    //stores the total data, total pages, and the data of products to data variable
    $data = json_encode($priceArray);
}

//function for getting the price specs on add and edit product forms
if($request === 'price_specs_data'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $categoryId = (isset($_GET["category_id"]) and $_GET["category_id"] !== '') ? $_GET["category_id"] : 0;

    $priceSpecsArray = array();
    $productId = (isset($_GET["product_id"]) and $_GET["product_id"] !== '') ? $_GET["product_id"] : 0;
    $priceId = (isset($_GET["price_id"]) and $_GET["price_id"] !== '') ? $_GET["price_id"] : 0;

    $specsResult = $conn->query("
        SELECT cs.*, s.specs_name
        FROM category_specs cs
            INNER JOIN specs s ON cs.specs_id = s.id
        WHERE cs.category_id = $categoryId
    ");

    while($row = $specsResult->fetch_assoc()){
        $specsId = $row["specs_id"];

        //get the value of specs
        $specsValue = $conn->query("
            SELECT IFNULL((
                SELECT value FROM price_specs WHERE price_id = $priceId AND specs_id = $specsId
            ), '') as value
        ")->fetch_assoc()["value"];

        array_push($priceSpecsArray, array(
            'specs_id' => $row["specs_id"], 
            'specs_name' => $row['specs_name'],
            'value' => $specsValue
        ));
    }
    
    $data = json_encode($priceSpecsArray);
    
    
    
}


if($request === "products-catalog"){
    $data = '';    //data will be stored here
    header('Content-Type: application/json');


    $query = "
        SELECT *
            FROM (
                SELECT 
                    p.*, c.category_name, u.username
                FROM products p
                    INNER JOIN category c ON p.category = c.id
                    INNER JOIN users u ON p.added_by = u.id
            ) as tbl
        WHERE 
            archived = 0
    ";

    //where clause for the status and category
    $search = (isset($_GET["search"]) and $_GET["search"] !== '') ? fixStr($_GET["search"]) : null;
    $category = (isset($_GET["category"]) and $_GET["category"] !== 'all') ? fixStr($_GET["category"]) : null;
    
    $whereQuery = '';
    $whereQuery .= $search ? "AND product_name LIKE '%$search%'" : '';
    $whereQuery .= $category ? "AND category = '$category'" : '';
    $query .= $whereQuery;

    //run the query
    $result = $conn->query("
        $query
        $orderByQuery $orderMethodQuery
        $limitQuery
        $offsetQuery
    ");

    $num = $offset + 1;
    $output = '';
    $nextBtn = '';
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){

            //get the prices of the product
            $productId = $row["id"];
            $minPrice = $conn->query("
                SELECT MIN(price) AS min_price
                FROM prices
                WHERE 
                    product_id = '$productId'
                ORDER BY price ASC
            ")->fetch_assoc()["min_price"];



            $saleTag = '';
            $salePrice = '';
            $priceTag = '';
            if($row["status"] and $row["sale"]){
                $saleTag = '<span class="sale-tag font-italic bg-danger text-white py-1 pl-2 pr-2">'.$row["sale"].'% OFF</span>';
                $salePrice = $minPrice - ($minPrice * ($row["sale"] / 100));
                $priceTag = '
                    <span class="card-text text-secondary">₱ '.number_format($salePrice, 2).'</span><br>
                    <del class="card-text text-secondary">₱ '.number_format($minPrice, 2).'</del>
                ';
            }
            else{
                $priceTag = '<span class="card-text text-secondary">₱ '.number_format($minPrice, 2).'</span><br><br>';
            }
            
    
            $output .= '
                <div class="product-catalog col col-6 col-xl-3 p-0" id="product'.$productId.'">
                    
                    <a class="text-dark" href="./ctr-productView.php?p='.$productId.'">
                    <div class="card shadow m-2">
                        '.$saleTag.'
                        <img class="card-img-top p-3 rounded" src="img/products/'.$productId.'.png?t='.time().'" style="aspect-ratio: 1 / 1; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-medium">'.$row["product_name"].'</h5>
                            '.(!$category ? '<span class="card-text text-secondary">'.$row["category_name"].'</span><br>' : '').'
                            '.$priceTag.'
                        </div>


                        '.(!$row["status"] ? '
                            <div class="unavailableDiv bg-dark d-flex align-items-center" style="width: 100%; height: 100%; opacity: 0.5; position: absolute">
                                <h3 class="text-white text-medium text-center w-100" style="opacity: 1">Unavailable</h3>
                            </div>
                        ' : '').'
                    </div>
                    </a>
                </div>
            ';
            
            
            
            $num++;
        }

        //run the query
        $nextContentCount = $conn->query("
            $query
            $orderByQuery $orderMethodQuery
            $limitQuery
            OFFSET ".($offset + $limit)."
        ");

        if($nextContentCount->num_rows > 0){
            $nextBtn = '<button id="next-btn" class="btn btn-brown" onclick="setupProductsList('.($page + 1).')">Show More</button>';
        }


        

    }
    else{
        $output .= '
            <div class="d-flex flex-column justify-content-center align-items-center w-100" style="height: 50vh">
                <img src="./img/product_not_found.svg?t='.time().'" alt="" style="width: 200px">
                <br>
                <h4 class="text-medium">Product Not Found</h4>
            </div>
        ';
        
    }

    $data = json_encode(array(
        'catalog' => $output,
        'nextBtn' => $nextBtn
    ));
}



if($request === 'top_products'){
    $data = array();    //data will be stored here
    header('Content-Type: application/json');

    $query = "
        SELECT 
            oi.product_id,
            prd.product_name, 
            IFNULL(prd.sale, 0) AS sale,
            (SELECT price FROM prices WHERE product_id = oi.product_id LIMIT 1) AS price,
            ctg.category_name,
            SUM(oi.total) AS total_sales
        FROM order_items oi
            INNER JOIN orders ord ON oi.order_id = ord.id
            INNER JOIN products prd ON oi.product_id = prd.id
            INNER JOIN category ctg ON prd.category = ctg.id
        WHERE
            ord.order_status = 'complete' AND
            oi.type = 'normal' AND
            prd.status = 1 AND
            prd.archived = 0
        GROUP BY oi.product_name
        ORDER BY total_sales DESC
    ";

    $result = $conn->query("
        $query
        $limitQuery
    ");

    $productsData = array();
    while($row = $result->fetch_assoc()){

        $salePrice = $row["price"] - ($row["price"] * ($row["sale"] / 100));
        array_push($productsData, array(
            'id' => $row["product_id"],
            'product_name' => $row["product_name"],
            'category' => $row["category_name"],
            'sale' => $row["sale"],
            'price' => $salePrice,
            'original_price' => (float)$row["price"]
            
            
        ));
    }
    
    
    $data = json_encode($productsData);
}

echo $data;
?>
