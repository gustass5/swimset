<?php

session_start();

require 'lib/password.php';

require 'config.php';

if(isset($_POST['register'])){
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']): null;
    
    $sql = 'SELECT COUNT(username) AS num FROM users WHERE username = :username';
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':username', $username);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row['num'] > 0){
        die('That username already exists!');
    }

    $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array('cost' => 12));

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
            <input type="submit" name="register" value="Register"></button>
        </form>
    </body>
</html>