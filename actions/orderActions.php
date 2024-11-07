<?php
session_start();
require("../database/connection.php");
require('../functions/mailer.php');
$action = isset($_POST["action"]) ? $_POST["action"] : null;


$user = $_SESSION["user"];
$date = date("Y-m-d H:i:s");
$message = "Message";
$res = "success";
$location = '';
$redirect = true;

try{
    if($action === 'add_order'){
        $cartId = $_POST["cartId"];

        //convert cartId into text
        $cartIdStr = "";
        if(is_array($cartId)){
            $count = 1;
            foreach($cartId as $id){
                $cartIdStr .= $id;
                if($count < count($cartId)){
                    $cartIdStr .= ",";
                }
                $count++;
            }
        }
        

        $result = $conn->query("
            SELECT 
                c.id, c.product_id, p.product_name, p.status, 
                c.price_id, pc.price_name, pc.price AS product_price,
                c.quantity, c.color_id, IFNULL(cl.color_name, '') AS color_name, IFNULL(cl.price, 0) AS color_price, 
                c.varnish, c.date_created, c.added_by, u.username
            FROM cart c
                LEFT JOIN products p ON c.product_id = p.id
                LEFT JOIN prices pc ON c.price_id = pc.id
                LEFT JOIN color cl ON c.color_id = cl.id
                LEFT JOIN users u ON p.added_by = u.id
            WHERE 
                c.id IN ($cartIdStr)
        ");

        $orderTotal = 0;
        $varnish = $conn->query("SELECT varnish_price FROM config WHERE 1")->fetch_assoc()["varnish_price"];
        $productsArray = array();
        while($row = $result->fetch_assoc()){
            $quantity = $row["quantity"];
            $variantPrice = $row["product_price"];
            $varnishPrice = $row["varnish"] === "1" ? $varnish : 0;
            $colorPrice = $row["color_price"];
            

            $productTotal = ($variantPrice + $colorPrice + $varnishPrice) * $quantity;

            array_push($productsArray, array(
                'cart_id' => $row["id"],
                'product_id' => $row["product_id"],
                'product_name' => $row["product_name"],
                'quantity' => $row["quantity"],
                'price_id' => $row["price_id"],
                'variant_name' => $row["price_name"],
                'variant_price' => $variantPrice,
                'varnish_price' => $varnishPrice,
                'color_id' => $row["color_id"],
                'color_price' => $colorPrice,
                'total' => $productTotal,
            ));
            


            $orderTotal += $productTotal;
        }








        $address = $_POST["address"];
        $contact = $_POST["contact"];
        $completionDate = $_POST["completionDate"];
        $paymentMethod = $_POST["paymentMethod"];
        $pickupMethod = $_POST["pickupMethod"];
        $pickupPrice = $_POST["pickupOptionPrice"];
        $orderTotal += $pickupPrice;
        

        $conn->query("
            INSERT INTO orders(
                home_address, contact, completion_date,
                payment_method, pickup_method, service_fee, 
                total, date_created, added_by
            ) VALUES (
                '$address','$contact', '$completionDate',
                '$paymentMethod','$pickupMethod','$pickupPrice',
                '$orderTotal','$date','$user'
            )
        ");

        $orderId = $conn->query("SELECT id FROM orders WHERE date_created = '$date' AND added_by = '$user' ORDER BY date_created DESC LIMIT 1")->fetch_assoc()["id"];
        $values = '';
        foreach($productsArray as $row){
            $cartId = $row["cart_id"];
            $productId = $row["product_id"];
            $productName = $row["product_name"];
            $quantity = $row["quantity"];
            $priceId = $row["price_id"];
            $variantName = $row["variant_name"];
            $variantPrice = $row["variant_price"];
            $varnishPrice = $row["varnish_price"];
            $colorId = $row["color_id"];
            $colorPrice = $row["color_price"];
            $productTotal = $row["total"];
            
            $conn->query("
                INSERT INTO order_items(
                    order_id, product_id, product_name, quantity, price_id, 
                    variant_name, variant_price, varnish_price, 
                    color_id, color_price, total
                ) VALUES (
                    '$orderId','$productId','$productName','$quantity','$priceId',
                    '$variantName','$variantPrice','$varnishPrice',
                    '$colorId','$colorPrice','$productTotal'
                )
            ");

            
            $conn->query("
                DELETE FROM cart WHERE id = '$cartId'
            ");
        }

        if(isset($_FILES["paymentScreenshot"])){
            $uploaddir = '../img/payments/';
            $uploadfile = $uploaddir . $orderId.'.png';
    
            move_uploaded_file($_FILES['paymentScreenshot']['tmp_name'], $uploadfile);
        }
    
        //add to notifications
        $conn->query("
            INSERT INTO notifications(
                order_id, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$orderId','pending','Your order has been submitted and is waiting for approval',
                'unread','$date','$user'
            )
        ");


        //send to customer email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $user")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];

        //send to email
        $orderItems = $conn->query("
            SELECT *
            FROM order_items
            WHERE order_id = $orderId
        ");
        $items = '';
        $subtotal = 0;
        $serviceFee = $conn->query("SELECT service_fee FROM orders WHERE id = $orderId")->fetch_assoc()["service_fee"];
        
        while($row = $orderItems->fetch_assoc()){
            $items .= '
                <p >
                    '.$row["product_name"].' 
                    '.($row["variant_name"] ? '('.$row["variant_name"].')' : '').'
                    &emsp;
                    ×'.$row["quantity"].'
                </p>
            ';
            $subtotal += $row["total"];
        }
        $total = $subtotal + $serviceFee;

        
        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Details',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>ORD-'.$orderId.' Details</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your order has been submitted and is waiting for approval</p>
                        <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                            '.$items.'
                            <hr>
                            <p><b>Subtotal</b>&emsp;₱ '.number_format($subtotal, 2).'</p>
                            <p><b>Service Fee</b>&emsp;₱ '.number_format($serviceFee, 2).'</p>
                            <p><b>Total</b>&emsp;₱ '.number_format($total, 2).'</p>
                        </div>
                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your order has been submitted and is waiting for approval'
            ));


        $message = 'Order has been added successfully';
        $res = 'success';
        $location = 'ctr-catalog.php';
    }

    if($action === 'update_paid_amount'){
        $orderId = $_POST["orderId"];
        $paidAmount = $_POST["paidAmount"];
        
        $conn->query("UPDATE orders SET paid_amount = '$paidAmount' WHERE id = '$orderId'");

        $message = 'Paid amount has been updated';
        $res = 'success';
        $location = 'adm-orderList.php';
    }

    if($action === 'approve_order'){
        $orderId = $_POST["orderId"];
        $paidAmount = $_POST["paidAmount"];
        
        $conn->query("UPDATE orders SET paid_amount = '$paidAmount', order_status = 'preparing', date_approved = '$date' WHERE id = '$orderId'");





        
        //add to notifications
        $receiverId = $conn->query("SELECT added_by FROM orders WHERE id = $orderId")->fetch_assoc()["added_by"];
        $conn->query("
            INSERT INTO notifications(
                order_id, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$orderId','preparing','Your order has been approved and is being prepared now',
                'unread','$date','$receiverId'
            )
        ");


        //send to customers email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];
        
        //send to email
        $orderItems = $conn->query("
            SELECT *
            FROM order_items
                
            WHERE order_id = $orderId
        ");
        $items = '';
        $subtotal = 0;
        $serviceFee = $conn->query("SELECT service_fee FROM orders WHERE id = $orderId")->fetch_assoc()["service_fee"];
        
        while($row = $orderItems->fetch_assoc()){
            $items .= '
                <p>
                    '.$row["product_name"].' 
                    '.($row["variant_name"] ? '('.$row["variant_name"].')' : '').'
                    &emsp;
                    ×'.$row["quantity"].'
                </p>
            ';
            $subtotal += $row["total"];
        }
        $total = $subtotal + $serviceFee;

        
        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Update',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>ORD-'.$orderId.' Status Update</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your order has been approved and is being prepared now</p>
                        <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                            '.$items.'
                            <hr>
                            <p><b>Subtotal</b>&emsp;₱ '.number_format($subtotal, 2).'</p>
                            <p><b>Service Fee</b>&emsp;₱ '.number_format($serviceFee, 2).'</p>
                            <p><b>Total</b>&emsp;₱ '.number_format($total, 2).'</p>
                        </div>
                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your order has been approved and is being prepared now'
            ));
        


        $message = 'The order has been approved';
        $res = 'success';
        $location = 'adm-orderList.php';
    }

    if($action === 'decline_order'){
        $orderId = $_POST["orderId"];
        $selectedReason = fixStr($_POST["selectedReason"]);
        $cancelDetails = fixStr($_POST["cancelDetails"]);

        if($selectedReason !== 'others'){
            $cancelDetails = $selectedReason.'. '.$cancelDetails;
            $cancelDetails = fixStr($cancelDetails);
        }
        
        $conn->query("UPDATE orders SET order_status = 'declined', cancel_details = '$cancelDetails' WHERE id = '$orderId'");


        //add to notifications
        $receiverId = $conn->query("SELECT added_by FROM orders WHERE id = $orderId")->fetch_assoc()["added_by"];
        $conn->query("
            INSERT INTO notifications(
                order_id, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$orderId','declined','Your order has been declined by the admin',
                'unread','$date','$receiverId'
            )
        ");




        //send to customers email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];
        
        $orderItems = $conn->query("
            SELECT *
            FROM order_items
                
            WHERE order_id = $orderId
        ");
        $items = '';
        $subtotal = 0;
        $serviceFee = $conn->query("SELECT service_fee FROM orders WHERE id = $orderId")->fetch_assoc()["service_fee"];
        
        while($row = $orderItems->fetch_assoc()){
            $items .= '
                <p>
                    '.$row["product_name"].' 
                    '.($row["variant_name"] ? '('.$row["variant_name"].')' : '').'
                    &emsp;
                    ×'.$row["quantity"].'
                </p>
            ';
            $subtotal += $row["total"];
        }
        $total = $subtotal + $serviceFee;

        
        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Update',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>ORD-'.$orderId.' Status Update</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your order has been declined by the admin</p>
                        <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                            '.$items.'
                            <hr>
                            <p><b>Subtotal</b>&emsp;₱ '.number_format($subtotal, 2).'</p>
                            <p><b>Service Fee</b>&emsp;₱ '.number_format($serviceFee, 2).'</p>
                            <p><b>Total</b>&emsp;₱ '.number_format($total, 2).'</p>
                        </div>
                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your order has been declined by the admin'
            ));


        $message = 'The order has been declined';
        $res = 'success';
        $location = 'adm-orderList.php';
    }

    if($action === 'set_ready'){
        $orderId = $_POST["orderId"];
        
        $conn->query("UPDATE orders SET order_status = 'ready' WHERE id = '$orderId'");

        //add to notifications
        $receiverId = $conn->query("SELECT added_by FROM orders WHERE id = $orderId")->fetch_assoc()["added_by"];
        $pickupMethod = $conn->query("SELECT pickup_method FROM orders WHERE id = $orderId")->fetch_assoc()["pickup_method"];
        $pickupText = '';
        if($pickupMethod == 'for pickup'){
            $pickupText = 'for pickup';

        } elseif($pickupMethod == 'for deliver'){
            $pickupText = 'for delivery';

        } elseif($pickupMethod === 'for installation'){
            $pickupText = 'to be installed';

        } elseif($pickupMethod === 'deliver and install'){
            $pickupText = 'for delivery and installed';

        }
        $conn->query("
            INSERT INTO notifications(
                order_id, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$orderId','ready','Your order is now ready $pickupText',
                'unread','$date','$receiverId'
            )
        ");



        //send to customers email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];

        $orderItems = $conn->query("
            SELECT *
            FROM order_items
                
            WHERE order_id = $orderId
        ");
        $items = '';
        $subtotal = 0;
        $serviceFee = $conn->query("SELECT service_fee FROM orders WHERE id = $orderId")->fetch_assoc()["service_fee"];
        
        while($row = $orderItems->fetch_assoc()){
            $items .= '
                <p>
                    '.$row["product_name"].' 
                    '.($row["variant_name"] ? '('.$row["variant_name"].')' : '').'
                    &emsp;
                    ×'.$row["quantity"].'
                </p>
            ';
            $subtotal += $row["total"];
        }
        $total = $subtotal + $serviceFee;

        
        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Update',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>ORD-'.$orderId.' Status Update</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your order is now ready '.$pickupText.'</p>
                        <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                            '.$items.'
                            <hr>
                            <p><b>Subtotal</b>&emsp;₱ '.number_format($subtotal, 2).'</p>
                            <p><b>Service Fee</b>&emsp;₱ '.number_format($serviceFee, 2).'</p>
                            <p><b>Total</b>&emsp;₱ '.number_format($total, 2).'</p>
                        </div>
                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your order is now ready '.$pickupText
            ));

        

        $message = 'The order has been set as ready for pickup/delivery';
        $res = 'success';
        $location = 'adm-orderList.php';
    }

    if($action === 'set_complete'){
        $orderId = $_POST["orderId"];
        $remainingBalance = $_POST["remainingBalance"];
        
        $conn->query("UPDATE orders SET paid_amount = (`paid_amount` + $remainingBalance), order_status = 'complete', date_completed = '$date' WHERE id = '$orderId'");

        
        //add to notifications
        $receiverId = $conn->query("SELECT added_by FROM orders WHERE id = $orderId")->fetch_assoc()["added_by"];
        $pickupMethod = $conn->query("SELECT pickup_method FROM orders WHERE id = $orderId")->fetch_assoc()["pickup_method"];
        $pickupText = '';
        if($pickupMethod == 'for pickup'){
            $pickupText = 'completed';

        } elseif($pickupMethod == 'for deliver'){
            $pickupText = 'delivered';

        } elseif($pickupMethod === 'for installation'){
            $pickupText = 'installed';

        } elseif($pickupMethod === 'deliver and install'){
            $pickupText = 'delivered and installed';

        }
        $conn->query("
            INSERT INTO notifications(
                order_id, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$orderId','complete','Your order has been $pickupText',
                'unread','$date','$receiverId'
            )
        ");


        //send to customers email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];
        
        $orderItems = $conn->query("
            SELECT *
            FROM order_items
                
            WHERE order_id = $orderId
        ");
        $items = '';
        $subtotal = 0;
        $serviceFee = $conn->query("SELECT service_fee FROM orders WHERE id = $orderId")->fetch_assoc()["service_fee"];
        
        while($row = $orderItems->fetch_assoc()){
            $items .= '
                <p>
                    '.$row["product_name"].' 
                    '.($row["variant_name"] ? '('.$row["variant_name"].')' : '').'
                    &emsp;
                    ×'.$row["quantity"].'
                </p>
            ';
            $subtotal += $row["total"];
        }
        $total = $subtotal + $serviceFee;

        
        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Update',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>ORD-'.$orderId.' Status Update</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your order has been '.$pickupText.'</p>
                        <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                            '.$items.'
                            <hr>
                            <p><b>Subtotal</b>&emsp;₱ '.number_format($subtotal, 2).'</p>
                            <p><b>Service Fee</b>&emsp;₱ '.number_format($serviceFee, 2).'</p>
                            <p><b>Total</b>&emsp;₱ '.number_format($total, 2).'</p>
                        </div>
                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your order has been '.$pickupText
            ));
        

        $message = 'The order has been set as complete';
        $res = 'success';
        $location = 'adm-orderList.php';
    }

    if($action === 'upload_screenshot'){
        $orderId = $_POST["orderId"];

        if(isset($_FILES["paymentScreenshot"])){
            $uploaddir = '../img/payments/';
            $uploadfile = $uploaddir . $orderId.'.png';
    
            move_uploaded_file($_FILES['paymentScreenshot']['tmp_name'], $uploadfile);
        }

        $message = 'Screenshot has been uploaded';
        $res = 'success';
        $location = 'ctr-orders.php';
    }

    if($action === 'cancel_order'){
        $orderId = $_POST["orderId"];
        $selectedReason = fixStr($_POST["selectedReason"]);
        $cancelDetails = fixStr($_POST["cancelDetails"]);

        if($selectedReason !== 'others'){
            $cancelDetails = $selectedReason.'. '.$cancelDetails;
            $cancelDetails = fixStr($cancelDetails);
        }
        
        $conn->query("UPDATE orders SET order_status = 'cancelled', cancel_details = '$cancelDetails' WHERE id = '$orderId'");

        $message = 'The order has been cancelled';
        $res = 'success';
        $location = 'ctr-orders.php';
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
    echo $e;
}


?>