<?php
session_start();
require 'core/config.php';

/**
 * Checks if user is logged in, redirects by default
 */
function checkLoggedIn($redirect = true){
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || fetchUserData($_SESSION['user_id']) === false){
        if($redirect){
            header('Location: login.php');
            exit;
        }else{
            return false;
        }
    }

    return true;
}

/**
 * Fetch necessary data about the user
 */
function fetchUserData($userId){
    Global $pdo;
    $sql = 'SELECT id, username, role, administrator FROM users WHERE id=:user_id AND deleted=false';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    return $user;
}

?>