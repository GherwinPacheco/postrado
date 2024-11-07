<?php
    session_start();
    require("./database/connection.php");

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = fixStr($_POST["username"]);
        $password = $_POST["password"];

        $result = $conn->query("SELECT * FROM users WHERE username = '$username'");

        
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();


            if($user["status"] === 0){
                $_SESSION["message"] = "The account you are trying to login is disabled by the admin";
                $_SESSION["res"] = "danger";
                header("Location: ./login.php");
                exit();
            }

            $id = $user["id"];
            $role = $user["role"];
            $hashPass = $user["password"];
            $passwordCorrect = password_verify($password, $hashPass);

            if($passwordCorrect){
                $_SESSION["user"] = $id;
                
                unset($_COOKIE['remember_cookie']);
                setcookie('remember_cookie', '', time() - 3600, '/');
                
                $cookie_name = "remember_cookie";
                $cookie_value = uniqid();
                setcookie($cookie_name, $cookie_value, time() + (604800 * 30), "/");

                $conn->query("UPDATE users SET remember_cookie = '$cookie_value' WHERE id = $id");


                if($role === "1"){
                    header("Location: ./adm-dashboard.php");
                    exit();
                }
                elseif($role === "2"){
                    header("Location: ./cpt-customRequests.php");
                    exit();
                }
                else{
                    header("Location: ./ctr-landingpage.php");
                    exit();
                }
                
            }
            else{
                $_SESSION["message"] = "You have entered the wrong password";
                $_SESSION["res"] = "danger";
                header("Location: ./login.php");
                exit();
            }
        }
        else{
            $_SESSION["message"] = "You have entered the wrong username or password";
            $_SESSION["res"] = "danger";
            header("Location: ./login.php");
            exit();
        }
    }
?>