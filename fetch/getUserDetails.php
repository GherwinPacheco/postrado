<?php
    session_start();
    require("../database/connection.php");

    header('Content-Type: application/json');
    if(isset($_SESSION["user"])) {
        $id = $_SESSION["user"];

        $id = fixStr($id);
        
        $user = $conn->query("
            SELECT `id`, `username`, `email`, `first_name`, `last_name`, `suffix`, `home_address`, `contact` FROM `users` WHERE id = $id");

        //returns user data on json format
        while($row = $user->fetch_assoc()){
            echo json_encode($row);
        }
        

    }

    
?>