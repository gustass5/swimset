<?php

session_start();

require 'lib/password.php';

require 'config.php';

if(isset($_POST['register'])){
    try{
        validateNewUser();
        addNewUser();
    }catch(Exception $error){
        echo $error;
    }
}

function validateNewUser(){
    $username = !empty(trim($_POST['username'])) ? trim($_POST['username']) : null;
    $currentPassword = !empty(trim($_POST['current-password'])) ? trim($_POST['current-password']) : null;
    $confirmPassword = !empty(trim($_POST['confirm-password'])) ? trim($_POST['confirm-password']) : null;
    
    $usernameExceptions = [];
    $currentPasswordExceptions = [];
    $confirmPasswordExceptions = [];

    if($username === null){
        $usernameExceptions[] = 'Username was not provided';
    }

    if(strpos($username, ' ') !== false ){
        $usernameExceptions = 'Username must not have spaces';
    }

    if($currentPassword === null){
        $currentPasswordExceptions[] = 'Password was not provided';
    }

    if(strpos($currentPassword, ' ') !== false){
        $currentPasswordExceptions[] = 'Password must not have spaces';
    }

    if(preg_match("/[^A-Za-z0-9]/", $currentPassword)){
        $currentPasswordExceptions[] = 'Password can only contain letters and numbers';
    }

    if(strlen($currentPassword) < 8){
        $currentPasswordExceptions[] = 'Password must have at least 8 characters';
    }

    if(!preg_match('/[0-9]/', $currentPassword)){
        $currentPasswordExceptions[] = 'Password must contain at least 1 number';
    }

    if($confirmPassword === null){
        $confirmPasswordExceptions[] = 'Password confirmation was not provided';
    }

    if(strcmp($confirmPassword,$currentPassword)){
        $confirmPasswordExceptions[] = 'Passwords do not match';
    }

    if(count($usernameExceptions) > 0 || count($currentPasswordExceptions) > 0 || count($confirmPasswordExceptions) > 0){
            echo '<div>'.$usernameExceptions[0].' - '.$currentPasswordExceptions[0].' - '.$confirmPasswordExceptions[0].'</div>';
        throw new Exception();
    }

}

function addNewUser(){
    Global $pdo;
    return;
    $username = trim($_POST['username']);
    $password = trim($_POST['current-password']);

    $sql = 'SELECT COUNT(username) AS num FROM users WHERE username = :username';
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':username', $username);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row['num'] > 0){
        throw new Exception('That username already exists!');
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $passwordHash);

    $result = $stmt->execute();

    if($result){
        echo 'Registered';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
        <script defer src="../js/register.js"></script>
    </head>
    <body>
        <h1>Register</h1>
        <form id="form" action="register.php" method="post">
            <div>    
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
                <div id='username-error'></div>
            </div>
            <div>
                <label for="current-password">Password</label>
                <input type="password" id="current-password" name="current-password"><br>
                <div id='current-password-error'></div>
            </div>
            <div>
                <label for="confirm-password">Confirm password</label>
                <input type="password" id="confirm-password" name="confirm-password"><br>
                <div id='confirm-password-error'></div>

            </div>
            <input type="submit" name="register" value="Register">
        </form>
    </body>
</html>