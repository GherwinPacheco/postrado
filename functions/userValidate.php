<?php

    function rememberUser(){
        if(isset($_COOKIE["remember_cookie"]) and $_COOKIE["remember_cookie"] !== ""){
            $conn = db();

            $remember_cookie = $_COOKIE["remember_cookie"];
            $result = $conn->query("SELECT id, role FROM users WHERE remember_cookie = '$remember_cookie'");
            if($result->num_rows > 0){
                $_SESSION["user"] = $result->fetch_assoc()["id"];
            }
            
        }
    }

    function requireLogin(){
        $conn = db();
        if(!isset($_SESSION["user"]) or $_SESSION["user"] === ""){
            header("Location: ./login.php");
            exit();
        }
        else{
            $status = $conn->query("SELECT status FROM users WHERE id = ".$_SESSION["user"])->fetch_assoc()["status"];
            if($status == 0){
                header("Location: ./logout.php");
                exit();
            }
        }
    }

    function allowRole($arr){
        $conn = db();

        $location = '';
        $role = $conn->query("SELECT role FROM users WHERE id = ".$_SESSION["user"])->fetch_assoc()["role"];
        if(!in_array($role, $arr)){
            if($role === '1'){
                $location = './adm-furnitures.php';
            }
            elseif($role === '2'){
                $location = './cpt-customRequests.php';
            }
            elseif($role === '3'){
                $location = './ctr-landingpage.php';
            }

            $_SESSION["message"] = "You do not have permission to access the page";
            $_SESSION["res"] = "danger";
            header("Location: $location");
            exit();
        }
    }

    

    
?>