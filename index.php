<?php
    session_start();
    require('./database/connection.php');
    if(isset($_COOKIE["remember_cookie"]) and $_COOKIE["remember_cookie"] !== ""){
        $remember_cookie = $_COOKIE["remember_cookie"];
        $result = $conn->query("SELECT * FROM users WHERE remember_cookie = '$remember_cookie'");
        if($result->num_rows > 0){
            $userData = $result->fetch_assoc();
            $_SESSION["user"] = $userData["id"];
            $role = $userData["role"];
            if($role == 1){
                header("Location: adm-furnitures.php");
                exit();
            }
            elseif($role == 2){
                header("Location: cpt-customRequests.php");
                exit();
            }
            elseif($role == 3){
                header("Location: ctr-landingpage.php");
                exit();
            }
        }
        else{
            header("Location: ctr-landingpage.php");
            exit();
        }
        
        
        
        
    }
    else{
        header("Location: ctr-landingpage.php");
        exit();
    }
    
?>