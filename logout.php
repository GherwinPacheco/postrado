<?php
    session_start();
    require('./database/connection.php');
    
    $conn->query("UPDATE users SET remember_cookie = NULL WHERE id = ".$_SESSION["user"]);

    unset($_COOKIE['remember_cookie']);
    setcookie('remember_cookie', '', time() - 3600, '/');

    $_SESSION["user"] = 0;
    session_destroy();

    header("Location: ctr-landingpage.php");
    exit();
?>