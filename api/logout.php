<?php
session_start();

if(!function_exists('hash_equals')){
    function hash_equals($str1, $str2){
        if(strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--){
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}

$queryStrToken = isset($_GET['token']) ? $_GET['token'] : '';

if(hash_equals($_SESSION['user_token'], $queryStrToken)){
    session_destroy();
    header('Location: ../index.php');
    exit;
}

?>