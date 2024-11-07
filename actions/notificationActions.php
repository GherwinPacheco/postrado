<?php
session_start();
require("../database/connection.php");

$action = isset($_POST["action"]) ? $_POST["action"] : null;


$user = $_SESSION["user"];
$date = date("Y-m-d H:i:s");
$message = "Message";
$res = "success";
$location = '';
$redirect = true;

try{
    if($action === 'mark_read'){
        $redirect = false;
        $notifId = $_POST["notifId"];
        
        $conn->query("
            UPDATE notifications SET notif_status = 'read' WHERE id = $notifId
        ");
        

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