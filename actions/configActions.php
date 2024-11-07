<?php
session_start();
require("../database/connection.php");
require('../functions/mailer.php');

$action = isset($_POST["action"]) ? $_POST["action"] : null;

$config = $conn->query("SELECT * FROM config WHERE 1 LIMIT 1")->fetch_assoc();


$user = isset($_SESSION["user"]) ? $_SESSION["user"] : '';
$date = date("Y-m-d H:i:s");
$message = "Message";
$res = "success";
$location = '';
$redirect = true;

try{
    if($action === 'update_config'){
        $location = 'adm-configGeneral.php';

        $storeAddress = fixStr($_POST["storeAddress"]);
        $contactNo = fixStr($_POST["contactNo"]);
        $storeLocation = fixStr($_POST["storeLocation"]);
        $gcashName = fixStr($_POST["gcashName"]);
        $gcashNo = fixStr($_POST["gcashNo"]);
        $mailerEmail = fixStr($_POST["mailerEmail"]);
        $mailerPass = fixStr($_POST["mailerPass"]);
        $varnishPrice = fixStr($_POST["varnishPrice"]);
        $forDeliver = fixStr($_POST["forDeliver"]);
        $forInstall = fixStr($_POST["forInstall"]);
        $deliverInstall = fixStr($_POST["deliverInstall"]);
        $downPayment = fixStr($_POST["downPayment"]);

        $conn->query("
            UPDATE config 
            SET 
                varnish_price='$varnishPrice',
                for_deliver='$forDeliver',
                for_install='$forInstall',
                deliver_install='$deliverInstall',
                down_payment='$downPayment',
                store_address='$storeAddress',
                contact_no='$contactNo',
                location_link='$storeLocation',
                gcash_name='$gcashName',
                gcash_number='$gcashNo',
                mailer_email='$mailerEmail',
                mailer_pass='$mailerPass' 
            WHERE 1
        ");


        $uploaddir = '../img/';
        $uploadfile = $uploaddir . 'gcash_qr.png';

        if (isset($_FILES["gcashQr"]) && $_FILES['gcashQr']['error'] == UPLOAD_ERR_OK) {
            move_uploaded_file($_FILES['gcashQr']['tmp_name'], $uploadfile);
        }
        else{}
        
        $message = 'Configurations has been updated successfully';
        $res = 'success';
    }

    if($action === 'update_terms'){
        $location = 'adm-configGeneral.php';

        $termHeading = fixStr($_POST["termHeading"]);
        $termTitle = $_POST["termTitle"];
        $termContent = $_POST["termContent"];


        $conn->query("UPDATE config SET terms_heading = '$termHeading' WHERE 1");
        $conn->query("DELETE FROM terms_conditions WHERE 1");
        for($x=0; $x<count($termTitle); $x++){
            $title = fixStr($termTitle[$x]);
            $content = fixStr($termContent[$x]);

            $conn->query("
                INSERT INTO terms_conditions (title, content)
                VALUES ('$title', '$content')
            ");
        }
        
        
        $message = 'Terms and Conditions has been updated successfully';
        $res = 'success';
    }


    if($action === 'add_account'){
        $location = $_POST["mode"] === 'login_create' ? 'login.php' : 'adm-configAccounts.php';

        $username = fixStr($_POST["username"]);
        $email = fixStr($_POST["email"]);
        $firstname = fixStr($_POST["firstname"]);
        $lastname = fixStr($_POST["lastname"]);
        $suffix = fixStr($_POST["suffix"]);
        $address = fixStr($_POST["address"]);
        $contact = fixStr($_POST["contact"]);
        $role = $_POST["mode"] === 'config_create' ? fixStr($_POST["role"]) : 3;     //set as customer if role is not set

        $emailExist = $conn->query("SELECT * FROM users WHERE email = '$email'")->num_rows > 0;
        
        if($emailExist){
            $message = 'The email you have entered is already in use';
            $res = 'danger';
        }
        else{
            $password = '';
            if($_POST["mode"] === 'login_create'){
                $password = $_POST["password"];
            }
            else{
                $password = generatePassword(8);
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $conn->query("
                INSERT INTO users(
                    username, email, 
                    first_name, last_name, suffix, 
                    home_address, contact, password, 
                    role, date_created
                ) VALUES (
                    '$username','$email',
                    '$firstname','$lastname','$suffix',
                    '$address','$contact','$hashedPassword',
                    '$role','$date')
            ");

            $id = $conn->query("SELECT id FROM users WHERE email = '$email'")->fetch_assoc()["id"];


            $uploaddir = '../img/profiles/';
            $uploadfile = $uploaddir . $id . '.png';

            if (isset($_FILES["imageFile"]) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
                move_uploaded_file($_FILES['imageFile']['tmp_name'], $uploadfile);
            } else {
                $defaultImage = '../img/profiles/default_user.png';  
                copy($defaultImage, $uploadfile); 
            }


            $conn->query("DELETE FROM email_verification WHERE email = '$email'");

            //send email to new admin or carpenter account
            if($role != 3){
                //send email to the email
                sendMail($mail, 
                array(
                    'email' => $email,
                    'name' => $firstname.' '.$lastname,
                    'subject' => 'Account Creation',
                    'body' => '
                        <h1 class="header">POSTRADO</h1>

                        <div class="content">
                            <h2>Account for Postrado</h2>
                            <p>Dear '.$firstname.',</p>
                            <p>Your Account to POSTRADO Woodworks has been successfully created.</p>
                            <div style="padding: 10px;background-color: #f0f0f0;border-radius: 5px;">
                                <p>This is your account credentials (DO NOT SHARE):</p>

                                <p>Username: '.$username.'</p>
                                <p>Password: '.$password.'</p>
                            </div>
                            
                            <p>Reminder: Please change the password into a stronger one, as the password given is only system generated.</p>
                        </div>

                        <div class="footer">
                            <p>POSTRADO Woodworks Shop</p>
                            <p>'.$config["store_address"].'</p>
                            <p>Email: '.$config["mailer_email"].'</p>
                            <p>Phone: '.$config["contact_no"].'</p>
                        </div>
                    ',
                    'altBody' => "Your account to POSTRADO website has been created. Username: $username, Password: $password"
                ));
            }
            
            
            $message = 'Account has been added successfully';
            $res = 'success';
        }

        
    }

    if($action === 'update_account'){
        $location = $_SERVER['HTTP_REFERER'];

        $id = fixStr($_POST["userId"]);
        $username = fixStr($_POST["username"]);
        $email = fixStr($_POST["email"]);
        $firstname = fixStr($_POST["firstname"]);
        $lastname = fixStr($_POST["lastname"]);
        $suffix = fixStr($_POST["suffix"]);
        $address = fixStr($_POST["address"]);
        $contact = fixStr($_POST["contact"]);

        $newPassword = fixStr($_POST["newPassword"]);
        $confirmPassword = fixStr($_POST["confirmPassword"]);

        $password = fixStr($_POST["password"]);



        $emailExist = $conn->query("SELECT * FROM users WHERE email = '$email' AND id != $id")->num_rows > 0;
        
        if($emailExist){
            $message = 'The email you have entered is already in use';
            $res = 'danger';
        }
        else{

            $hashPass = $conn->query("SELECT password FROM users WHERE id = $id")->fetch_assoc()["password"];
            $passwordCorrect = password_verify($password, $hashPass);

            //update the details if password is correct
            if($passwordCorrect){
                $passwordMatch = ($newPassword === $confirmPassword);
                
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                if($passwordMatch){ //if new password and confirm password matched

                    $passwordQuery = $newPassword !== '' ? ", password = '$newPasswordHash'" : '';

                    $conn->query("
                        UPDATE users 
                        SET 
                            username='$username',
                            email='$email',
                            first_name='$firstname',
                            last_name='$lastname',
                            suffix='$suffix',
                            home_address='$address',
                            contact='$contact'
                            $passwordQuery
                        WHERE id = $id
                    ");

                    //upload image if the user uploaded any
                    if (isset($_FILES["imageFile"]) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
                        $uploaddir = '../img/profiles/';
                        $uploadfile = $uploaddir . $id . '.png';
                        move_uploaded_file($_FILES['imageFile']['tmp_name'], $uploadfile);
                    }
                    
                    
                    $message = 'Account details has been updated successfully';
                    $res = 'success';
                }
                else{   //if new password and confirm password does not match
                    $message = 'The new password you have entered does not match';
                    $res = 'danger';
                }

                
            }
            else{
                $message = 'You have entered a wrong password';
                $res = 'danger';
            }



        }        
    }

    if($action === 'change_role'){
        $location = $_SERVER['HTTP_REFERER'];

        $id = fixStr($_POST["userId"]);
        $role = fixStr($_POST["role"]);

        $conn->query("UPDATE users SET role = $role WHERE id = $id");
        
        $message = 'The role has been changed successfully';
        $res = 'success';

    }

    if($action === 'deactivate_account'){
        $location = $_SERVER['HTTP_REFERER'];

        $id = fixStr($_POST["userId"]);

        $conn->query("UPDATE users SET status = 0 WHERE id = $id");
        
        $message = 'The account has been deactivated';
        $res = 'success';

    }

    if($action === 'activate_account'){
        $location = $_SERVER['HTTP_REFERER'];

        $id = fixStr($_POST["userId"]);

        $conn->query("UPDATE users SET status = 1 WHERE id = $id");
        
        $message = 'The account has been activated';
        $res = 'success';

    }

    if($action === 'add_category'){
        $location = $_SERVER['HTTP_REFERER'];

        $categoryName = fixStr($_POST["categoryName"]);
        $specsArray = $_POST["specs"];

        $categoryExist = $conn->query("SELECT * FROM category WHERE category_name = '$categoryName' AND archived = 0")->num_rows > 0;

        if($categoryExist){
            $message = 'The category name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                INSERT INTO `category`(category_name) VALUES ('$categoryName')
            ");

            $categoryId = $conn->query("SELECT id FROM category WHERE category_name = '$categoryName'")->fetch_assoc()["id"];


            for($x=0; $x<count($specsArray); $x++){
                $specsName = $specsArray[$x];
                $specsResult = $conn->query("SELECT * FROM specs WHERE specs_name = '$specsName'");
                $specs = $specsResult->fetch_assoc();

                if($specsResult->num_rows > 0){ //use the existing specs on database
                    $specsId = $specs["id"];
                    $conn->query("
                        INSERT INTO `category_specs` (
                            `category_id`, `specs_id`
                        ) VALUES (
                            '$categoryId', '$specsId'
                        )
                    ");
                }
                else{   //create a new specs and use it for category
                    $conn->query("
                        INSERT INTO `specs`(`specs_name`) VALUES ('$specsName')
                    ");

                    $specsResult = $conn->query("SELECT * FROM specs WHERE specs_name = '$specsName'");
                    $specsId = $conn->query("SELECT * FROM specs WHERE specs_name = '$specsName'")->fetch_assoc()["id"];

                    $conn->query("
                        INSERT INTO `category_specs` (
                            `category_id`, `specs_id`
                        ) VALUES (
                            '$categoryId', '$specsId'
                        )
                    ");

                }
            }
            
            $message = 'The category has been created successfully';
            $res = 'success';
        }
        

    }



    

    if($action === 'edit_category'){
        $location = $_SERVER['HTTP_REFERER'];

        $categoryId = $_POST["categoryId"];
        $categoryName = fixStr($_POST["categoryName"]);
        $specsArray = $_POST["specs"];

        $categoryExist = $conn->query("SELECT * FROM category WHERE category_name = '$categoryName' AND archived = 0 AND id != $categoryId")->num_rows > 0;

        if($categoryExist){
            $message = 'The category name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                UPDATE category SET category_name='$categoryName' WHERE id = '$categoryId'
            ");

            $conn->query("DELETE FROM category_specs WHERE category_id = $categoryId");

            for($x=0; $x<count($specsArray); $x++){
                $specsName = fixStr($specsArray[$x]);
                $specsResult = $conn->query("SELECT * FROM specs WHERE specs_name = '$specsName'");
                $specs = $specsResult->fetch_assoc();

                if($specsResult->num_rows > 0){ //use the existing specs on database
                    $specsId = $specs["id"];
                    $conn->query("
                        INSERT INTO `category_specs` (
                            `category_id`, `specs_id`
                        ) VALUES (
                            '$categoryId', '$specsId'
                        )
                    ");
                }
                else{   //create a new specs and use it for category
                    $conn->query("
                        INSERT INTO `specs`(`specs_name`) VALUES ('$specsName')
                    ");

                    $specsResult = $conn->query("SELECT * FROM specs WHERE specs_name = '$specsName'");
                    $specsId = $conn->query("SELECT * FROM specs WHERE specs_name = '$specsName'")->fetch_assoc()["id"];

                    $conn->query("
                        INSERT INTO `category_specs` (
                            `category_id`, `specs_id`
                        ) VALUES (
                            '$categoryId', '$specsId'
                        )
                    ");

                }
            }
            
            $message = 'The category has been edited successfully';
            $res = 'success';
        }
        

    }

    if($action === 'archive_category'){
        $location = $_SERVER['HTTP_REFERER'];

        $categoryId = $_POST["categoryId"];

        $conn->query("UPDATE category SET archived = 1 WHERE id = $categoryId");
        
        $message = 'The category has been archived successfully';
        $res = 'success';
        
    }

    if($action === 'add_unit'){
        $location = $_SERVER['HTTP_REFERER'];

        $unitName = fixStr($_POST["unitName"]);

        $unitExist = $conn->query("SELECT * FROM unit_of_measurement WHERE unit_name = '$unitName' AND archived = 0")->num_rows > 0;

        if($unitExist){
            $message = 'The unit name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                INSERT INTO `unit_of_measurement`(`unit_name`) VALUES ('$unitName')
            ");
            
            $message = 'The unit has been created successfully';
            $res = 'success';
        }
        

    }

    if($action === 'edit_unit'){
        $location = $_SERVER['HTTP_REFERER'];

        $unitId = $_POST["unitId"];
        $unitName = fixStr($_POST["unitName"]);

        $unitExist = $conn->query("SELECT * FROM unit_of_measurement WHERE unit_name = '$unitName' AND archived = 0 AND id != $unitId")->num_rows > 0;

        if($unitExist){
            $message = 'The unit name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                UPDATE unit_of_measurement SET unit_name = '$unitName' WHERE id = $unitId
            ");
            
            $message = 'The unit has been edited successfully';
            $res = 'success';
        }
        

    }

    if($action === 'archive_unit'){
        $location = $_SERVER['HTTP_REFERER'];

        $unitId = $_POST["unitId"];

        $conn->query("
            UPDATE unit_of_measurement SET archived = 1 WHERE id = $unitId
        ");

        $message = 'The unit has been archived successfully';
        $res = 'success';

    }

    if($action === 'add_color'){
        $location = $_SERVER['HTTP_REFERER'];

        $colorName = fixStr($_POST["colorName"]);
        $price = fixStr($_POST["price"]);

        $colorExist = $conn->query("SELECT * FROM color WHERE color_name = '$colorname' AND archived = 0")->num_rows > 0;

        if($colorExist){
            $message = 'The color name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                INSERT INTO `color`(`color_name`, `price`) VALUES ('$colorName', '$price')
            ");
            
            $message = 'The color has been created successfully';
            $res = 'success';
        }
        

    }

    if($action === 'edit_color'){
        $location = $_SERVER['HTTP_REFERER'];

        $colorId = $_POST["colorId"];
        $colorName = fixStr($_POST["colorName"]);
        $price = fixStr($_POST["price"]);

        $colorExist = $conn->query("SELECT * FROM color WHERE color_name = '$colorname' AND archived = 0 AND id != $colorId")->num_rows > 0;

        if($colorExist){
            $message = 'The color name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                UPDATE color SET color_name = '$colorName', price = '$price' WHERE id = $colorId
            ");
            
            $message = 'The color has been updated successfully';
            $res = 'success';
        }
        

    }

    if($action === 'archive_color'){
        $location = $_SERVER['HTTP_REFERER'];

        $colorId = $_POST["colorId"];
        
        $conn->query("
            UPDATE color SET archived = 1 WHERE id = $colorId
        ");

        $message = 'The color has been archived successfully';
        $res = 'success';

    }

    if($action === 'add_wood'){
        $location = $_SERVER['HTTP_REFERER'];

        $woodName = fixStr($_POST["woodName"]);

        $woodExist = $conn->query("SELECT * FROM wood_type WHERE wood_name = '$woodName' AND archived = 0")->num_rows > 0;

        if($woodExist){
            $message = 'The wood name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                INSERT INTO `wood_type`(`wood_name`) VALUES ('$woodName')
            ");
            
            $message = 'The wood type has been added successfully';
            $res = 'success';
        }
        

    }

    if($action === 'edit_wood'){
        $location = $_SERVER['HTTP_REFERER'];

        $woodId = $_POST["woodId"];
        $woodName = fixStr($_POST["woodName"]);

        $woodExist = $conn->query("SELECT * FROM wood_type WHERE wood_name = '$woodName' AND archived = 0 AND id != $woodId")->num_rows > 0;

        if($woodExist){
            $message = 'The wood name is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                UPDATE wood_type SET wood_name = '$woodName' WHERE id = $woodId
            ");
            
            $message = 'The wood type has been updated successfully';
            $res = 'success';
        }
        

    }

    if($action === 'archive_wood'){
        $location = $_SERVER['HTTP_REFERER'];

        $woodId = $_POST["woodId"];
        
        $conn->query("
            UPDATE wood_type SET archived = 1 WHERE id = $woodId
        ");

        $message = 'The wood type has been archived successfully';
        $res = 'success';

    }

    if($action === 'add_cancel'){
        $location = $_SERVER['HTTP_REFERER'];

        $reason = fixStr($_POST["reason"]);

        $reasonExist = $conn->query("SELECT * FROM reason_options WHERE reason = '$woodName' AND type = 'customer_cancel' AND archived = 0")->num_rows > 0;

        if($reasonExist){
            $message = 'The cancel reason you have entered is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                INSERT INTO `reason_options`(`reason`, `type`) VALUES ('$reason', 'customer_cancel')
            ");
            
            $message = 'The cancel reason has been added successfully';
            $res = 'success';
        }
        

    }

    if($action === 'edit_cancel'){
        $location = $_SERVER['HTTP_REFERER'];

        $reasonId = $_POST["reasonId"];
        $reason = fixStr($_POST["reason"]);

        $reasonExist = $conn->query("SELECT * FROM reason_options WHERE reason = '$reason' AND type = 'customer_cancel' AND archived = 0 AND id != $reasonId")->num_rows > 0;

        if($reasonExist){
            $message = 'The cancel reason is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                UPDATE reason_options SET reason = '$reason' WHERE id = $reasonId
            ");
            
            $message = 'The cancel reason has been updated successfully';
            $res = 'success';
        }
        

    }

    if($action === 'archive_cancel'){
        $location = $_SERVER['HTTP_REFERER'];

        $reasonId = $_POST["reasonId"];
        
        $conn->query("
            UPDATE reason_options SET archived = 1 WHERE id = $reasonId
        ");

        $message = 'The cancel reason has been archived successfully';
        $res = 'success';

    }

    if($action === 'add_decline'){
        $location = $_SERVER['HTTP_REFERER'];

        $reason = fixStr($_POST["reason"]);

        $reasonExist = $conn->query("SELECT * FROM reason_options WHERE reason = '$woodName' AND type = 'admin_decline' AND archived = 0")->num_rows > 0;

        if($reasonExist){
            $message = 'The decline reason you have entered is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                INSERT INTO `reason_options`(`reason`, `type`) VALUES ('$reason', 'admin_decline')
            ");
            
            $message = 'The decline reason has been added successfully';
            $res = 'success';
        }
        

    }

    if($action === 'edit_decline'){
        $location = $_SERVER['HTTP_REFERER'];

        $reasonId = $_POST["reasonId"];
        $reason = fixStr($_POST["reason"]);

        $reasonExist = $conn->query("SELECT * FROM reason_options WHERE reason = '$reason' AND type = 'admin_decline' AND archived = 0 AND id != $reasonId")->num_rows > 0;

        if($reasonExist){
            $message = 'The decline reason is already in use';
            $res = 'danger';
        }
        else{
            $conn->query("
                UPDATE reason_options SET reason = '$reason' WHERE id = $reasonId
            ");
            
            $message = 'The decline reason has been updated successfully';
            $res = 'success';
        }
        

    }

    if($action === 'archive_decline'){
        $location = $_SERVER['HTTP_REFERER'];

        $reasonId = $_POST["reasonId"];
        
        $conn->query("
            UPDATE reason_options SET archived = 1 WHERE id = $reasonId
        ");

        $message = 'The decline reason has been archived successfully';
        $res = 'success';

    }


    if($action === 'new_code'){
        $redirect = false;

        $email = $_POST["email"];

        $code = rand(100000, 999999);

        $conn->query("DELETE FROM email_verification WHERE email = '$email'");
        $conn->query("INSERT INTO email_verification (email, code) VALUES ('$email', '$code')");

        sendMail($mail, 
            array(
                'email' => $email,
                'name' => '',
                'subject' => 'OTP Code',
                'body' => '
                    <h1 class="header">POSTRADO</h1>

                    <div class="content">
                        <h2>Verify Your Email Address</h2>
                        <p>Dear User,</p>
                        <p>Thank you for signing up! To complete your registration, please verify your email address.</p>
                        <p>Your verification code:</p>
                        <div style="background-color: #79341e;font-size: 24px;font-weight: bold;text-align: center;padding: 10px;color: white;border-radius: 10px;">
                            '.$code.'
                        </div>
                        <p>* This code is valid for the next 15 minutes.</p>
                        <p>If you did not sign up for this account, please disregard this email.</p>
                    </div>

                    <div class="footer">
                        <p>POSTRADO Woodworks Shop</p>
                        <p>'.$config["store_adddress"].'</p>
                        <p>Email: '.$config["mailer_email"].'</p>
                        <p>Phone: '.$config["contact_no"].'</p>
                    </div>

                ',
                'altBody' => "The OTP for your Postrado is <b>".$code."</b>"
            ));
        
    }


    if($action === 'check_otp'){
        $redirect = false;

        $email = $_POST["email"];
        $otp = $_POST["otp"];

        $result = $conn->query("SELECT code FROM email_verification WHERE email = '$email'")->fetch_assoc()["code"];
    

        echo $otp === $result;
    }

    if($action === 'forget_pass'){
        $location = 'login.php';

        $email = $_POST["email"];
        $newPassword = fixStr($_POST["newPassword"]);
        $confirmPassword = fixStr($_POST["confirmPassword"]);

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $conn->query("UPDATE users SET password = '$hashedPassword' WHERE email = '$email'");

        $conn->query("DELETE FROM email_verification WHERE email = '$email'");
        
        $message = 'Your password has been updated successfully';
        $res = 'success';
    }
    
    


    
    if($redirect){
        $_SESSION["message"] = $message;
        $_SESSION["res"] = $res;


        if($location !== $_SERVER["HTTP_REFERER"]){
            $location = "../$location";
        }

        header("Location: $location");
        exit();
    }
}
catch(Exception $e){
    if($redirect){
        $_SESSION["message"] = "Something went wrong";
        $_SESSION["res"] = "danger";

        if($location !== $_SERVER["HTTP_REFERER"]){
            $location = "../$location";
        }

        header("Location: ../$location");
        exit();
    }
}


function generatePassword($length = 16) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $charactersLength - 1)];
    }

    return $password;
}



?>