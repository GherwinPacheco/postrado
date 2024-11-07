<?php
    //error_reporting(0);
    date_default_timezone_set('Asia/Manila');
    

    function db() {
        static $conn;
        if ($conn===NULL){ 
            $conn = new mysqli("localhost", "root", "", "postrado");
        }
        return $conn;
    }

    function fixStr($str){
        $str = str_replace('"', '\"', $str);
        $str = str_replace("'", "\'", $str);
        $str = str_replace("`", "\`", $str);
        return $str;
    }


    $conn = db();
?>