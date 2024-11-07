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
    if($action === 'add_custom'){
        $categoryId = $_POST["category"];
        $varnish = $_POST["varnish"] > 0 ? 1 : 0;
        $varnishPrice = $_POST["varnish"];
        $color = json_decode($_POST["color"]);
        $colorId = $color->id;
        $colorPrice = $color->price;

        $serviceOption = json_decode($_POST["pickupMethod"]);
        $pickupMethod = $serviceOption->mode;
        $pickupPrice = $serviceOption->fee;

        $woodId = $_POST["wood"];
        $quantity = $_POST["quantity"];
        $description = fixStr($_POST["description"]);
        $imageCount = count($_FILES["imageFile"]["name"]);

        $conn->query("
            INSERT INTO `custom_products`(
                `category_id`, `quantity`, `varnish`,
                `color_id`, `wood_id`, `pickup_method`, `description`, 
                `date_created`, `added_by`, `image_count`) 
            VALUES (
                '$categoryId','$quantity','$varnish',
                '$colorId','$woodId','$pickupMethod','$description',
                '$date','$user','$imageCount'
            )
        ");
        
        $newData = $conn->query("
            SELECT cp.*, ct.category_name 
            FROM custom_products cp
                INNER JOIN category ct ON cp.category_id = ct.id
            WHERE 1 ORDER BY id DESC LIMIT 1
        ")->fetch_assoc();

        $customId = $newData["id"];
        $categoryName = $newData["category_name"];

        $conn->query("UPDATE custom_products SET product_name = 'Custom_$categoryName#$customId' WHERE id = $customId");

        if(isset($_FILES["imageFile"])){
            $uploaddir = '../img/custom/';
            $file = $_FILES["imageFile"];
            for($x = 0; $x < $imageCount; $x++){
                $tmpFileName = $file['tmp_name'][$x];
                
                $uploadfile = $uploaddir . $customId.'_'.($x+1).'.png';
        
                move_uploaded_file($tmpFileName, $uploadfile);
            }
        
        }
        

        //add to notifications
        $receiverId = $newData["added_by"];
        $conn->query("
            INSERT INTO notifications(
                custom_id, custom_name, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$customId','Custom_$categoryName#$customId','custom_pending','Your request for custom order has been submitted and is waiting for admin\'s approval.',
                'unread','$date','$receiverId'
            )
        ");

        //send to customer email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];

        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Details',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>Custom Order Details</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your custom order request of Custom_'.$categoryName.'#'.$customId.' has been submitted and is waiting for approval</p>

                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your custom order of Custom_'.$categoryName.'#'.$customId.' has been submitted and is waiting for approval'
            ));


        $message = 'Custom request has been sent';
        $res = 'success';
        $location = 'ctr-landingpage.php';
    }

    if($action === 'admin_approve'){
        $customId = $_POST["customId"];

        $conn->query("UPDATE custom_products SET request_status = 'pending' WHERE id = $customId");

        //add to notifications
        $newData = $conn->query("SELECT cp.*, ctg.category_name FROM custom_products cp INNER JOIN category ctg ON cp.category_id = ctg.id WHERE cp.id = $customId")->fetch_assoc();
        $receiverId = $newData["added_by"];
        $categoryName = $newData["category_name"];
        $conn->query("
            INSERT INTO notifications(
                custom_id, custom_name, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$customId','Custom_$categoryName#$customId','custom_approved','Your request for custom order has been approved by the admin.',
                'unread','$date','$receiverId'
            )
        ");


        //send to customer email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];

        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Details',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>Custom Order Details</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your custom order request of Custom_'.$categoryName.'#'.$customId.' has been approved by the admin</p>

                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your custom order request of Custom_'.$categoryName.'#'.$customId.' has been approved by the admin'
            ));


        $message = 'The custom request has been approved successfully';
        $res = 'success';
        $location = 'adm-customRequests.php';
    }

    if($action === 'admin_decline'){
        $customId = $_POST["customId"];
        $selectedReason = fixStr($_POST["selectedReason"]);
        $cancelDetails = fixStr($_POST["cancelDetails"]);

        if($selectedReason !== 'others'){
            $cancelDetails = $selectedReason.'. '.$cancelDetails;
            $cancelDetails = fixStr($cancelDetails);
        }
        
        

        $conn->query("UPDATE custom_products SET request_status = 'declined', cancel_details = '$cancelDetails' WHERE id = $customId");


        //add to notifications
        $newData = $conn->query("SELECT cp.*, ctg.category_name FROM custom_products cp INNER JOIN category ctg ON cp.category_id = ctg.id WHERE cp.id = $customId")->fetch_assoc();
        $receiverId = $newData["added_by"];
        $categoryName = $newData["category_name"];
        $conn->query("
            INSERT INTO notifications(
                custom_id, custom_name, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$customId','Custom_$categoryName#$customId','custom_declined','Your request for custom order has been declined by the admin.',
                'unread','$date','$receiverId'
            )
        ");

        


        //send to customer email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];

        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Details',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>Custom Order Details</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your custom order request of Custom_'.$categoryName.'#'.$customId.' has been declined by the admin</p>
                        <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                            <p>'.$cancelDetails.'</p>
                        </div>

                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your custom order request of Custom_'.$categoryName.'#'.$customId.' has been approved by the admin'
            ));


        $message = 'The custom request has been declined successfully';
        $res = 'success';
        $location = 'adm-customRequests.php';
    }

    if($action === 'update_custom'){
        $customId = $_POST["customId"];
        $productPrice = $_POST["price"];
        $downPayment = $_POST["downPayment"];
        $completionDate = $_POST["completionDate"];
        
        $specsName = $_POST["specsName"];
        $specsValue = $_POST["specsValue"];

        $conn->query("
            UPDATE custom_products 
            SET price = $productPrice, 
                down_payment = $downPayment, 
                completion_date = '$completionDate',
                request_status = 'updated'
            WHERE id = $customId");


        $conn->query("DELETE FROM custom_product_specs WHERE custom_id = $customId");
        $specsText = '';
        for($x=0; $x<count($specsName); $x++){
            $name = $specsName[$x];
            $value = $specsValue[$x];
            $conn->query("
                INSERT INTO `custom_product_specs`(
                    `custom_id`, `specs_name`, `specs_value`
                ) VALUES (
                    '$customId','$name','$value'
                )
            ");
            $specsText .= '
                <li>'.$specsName[$x].':&emsp;'.$specsValue[$x].'</li>
            ';
        }

        
        if (isset($_FILES["imageFile"]) && !empty($_FILES["imageFile"]["name"][0])) {
            $imageCount = count($_FILES["imageFile"]["name"]);
            
            // Update sketch_count in the database
            $conn->query("UPDATE custom_products SET sketch_count = $imageCount WHERE id = $customId");
        
            $uploaddir = '../img/custom/';
            $file = $_FILES["imageFile"];
        
            for ($x = 0; $x < $imageCount; $x++) {
                $tmpFileName = $file['tmp_name'][$x];
                
                $uploadfile = $uploaddir . $customId.'_sketch_'.($x + 1).'.png';
                
                // Move the uploaded file to the desired directory
                move_uploaded_file($tmpFileName, $uploadfile);
            }
        }

        //add to notifications
        $newData = $conn->query("SELECT cp.*, ctg.category_name FROM custom_products cp INNER JOIN category ctg ON cp.category_id = ctg.id WHERE cp.id = $customId")->fetch_assoc();
        $receiverId = $newData["added_by"];
        $categoryName = $newData["category_name"];
        $conn->query("
            INSERT INTO notifications(
                custom_id, custom_name, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$customId','Custom_$categoryName#$customId','custom_updated','Your custom order\'s details has updated by the carpenter.',
                'unread','$date','$receiverId'
            )
        ");


        //send to customer email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];

        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Details',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>Custom Order Details</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>The details of your Custom_'.$categoryName.'#'.$customId.' order has been updated by the carpenter</p>
                        <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                            <p><b>Price:</b>&emsp;'.number_format($productPrice, 2).'</p>
                            <p><b>Down Payment:</b>&emsp;'.number_format($downPayment, 2).'</p>
                            <p><b>Deadline:</b>&emsp;'.$completionDate.'</p>
                            <hr>
                            <p><b>Specifications:</b></p>
                            <ul>
                                '.$specsText.'
                            </ul>
                        </div>

                        
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your custom order request of Custom_'.$categoryName.'#'.$customId.' has been approved by the admin'
            ));


        $message = 'Furniture Details has been updated successfully';
        $res = 'success';
        $location = 'cpt-customRequests.php';
    }

    if($action === 'add_custom_order'){
        $customId = $_POST["customId"];
        $downPayment = $_POST["downPayment"];

        $config = $conn->query("SELECT * FROM config WHERE 1")->fetch_assoc();
        
        $result = $conn->query("
            SELECT cp.*, ct.category_name, cl.color_name, cl.price AS color_price, u.username 
                FROM custom_products cp
                    INNER JOIN users u ON cp.added_by = u.id
                    LEFT JOIN color cl ON cp.color_id = cl.id
                    LEFT JOIN category ct ON cp.category_id = ct.id
            WHERE cp.id = $customId
        ")->fetch_assoc();

        $productName = $result["product_name"];
        $quantity = $result["quantity"];
        $productPrice = $result["price"];
        $varnishPrice = $result["varnish"] == 1 ? $config["varnish_price"] : 0;
        $colorId = $result["color_id"];
        $colorPrice = $result["color_id"] != 0 ? $result["color_price"] : 0;
        $productTotal = ($productPrice + $varnishPrice + $colorPrice) * $quantity;
        $customerId = $result["added_by"];

        

        $serviceFee = 0;
        if($result["pickup_method"] === 'for deliver'){
            $serviceFee = $config["for_deliver"];
        }
        elseif($result["pickup_method"] === 'for installation'){
            $serviceFee = $config["for_install"];
        }
        elseif($result["pickup_method"] === 'deliver and install'){
            $serviceFee = $config["deliver_install"];
        }




        $userInfo = $conn->query("SELECT * FROM users WHERE id = $customerId")->fetch_assoc();

        $address = $userInfo["home_address"];
        $contact = $userInfo["contact"];
        $completionDate = $result["completion_date"];
        $paymentMethod = 'cash';
        $pickupMethod = $result["pickup_method"];
        $dateAdded = $result["date_created"];
        $orderTotal = ($productTotal + $serviceFee);
        

        $conn->query("
            INSERT INTO orders(
                home_address, contact, completion_date,
                payment_method, pickup_method, service_fee, 
                total, paid_amount, order_status, date_created, added_by, date_approved
            ) VALUES (
                '$address','$contact', '$completionDate',
                '$paymentMethod','$pickupMethod','$serviceFee',
                '$orderTotal','$downPayment','preparing','$dateAdded','$customerId','$date'
            )
        ");

        $orderId = $conn->query("SELECT id FROM orders WHERE date_created = '$dateAdded' AND added_by = '$customerId' ORDER BY date_created DESC LIMIT 1")->fetch_assoc()["id"];
        
        $conn->query("
            INSERT INTO order_items(
                order_id, type, product_id, product_name, quantity, 
                variant_price, varnish_price, 
                color_id, color_price, total
            ) VALUES (
                '$orderId','custom','$customId','$productName','$quantity',
                '$productPrice','$varnishPrice',
                '$colorId','$colorPrice','$productTotal'
            )
        ");



        $conn->query("UPDATE custom_products SET request_status = 'ordered' WHERE id = $customId");
        

        //add to notifications
        $newData = $conn->query("SELECT cp.*, ctg.category_name FROM custom_products cp INNER JOIN category ctg ON cp.category_id = ctg.id WHERE cp.id = $customId")->fetch_assoc();
        $receiverId = $newData["added_by"];
        $categoryName = $newData["category_name"];
        $conn->query("
            INSERT INTO notifications(
                order_id, custom_id, custom_name, order_status, message, 
                notif_status, date_created, receiver_id
            ) VALUES (
                '$orderId','$customId','Custom_$categoryName#$customId','preparing','Your custom order Custom_$categoryName#$customId has been added as ORD-$orderId and is now being prepared.',
                'unread','$date','$receiverId'
            )
        ");


        //send to customer email
        $customerDetails = $conn->query("SELECT * FROM users WHERE id = $receiverId")->fetch_assoc();
        $customerEmail = $customerDetails["email"];
        $customerName = $customerDetails["first_name"];

        sendMail($mail, 
            array(
                'email' => $customerEmail,
                'name' => $customerName,
                'subject' => 'Order Details',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>Custom Order Details</h2>
                        <p>Dear '.$customerName.',</p>
                        <p>Your Custom_'.$categoryName.'#'.$customId.' order in now being prepared</p>
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_address"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>
                ',
                'altBody' => 'Your custom order request of Custom_'.$categoryName.'#'.$customId.' has been approved by the admin'
            ));

        
        $message = 'Order has been added successfully';
        $res = 'success';
        $location = 'cpt-customRequests.php';
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