<?php

session_start();

require 'lib/password.php';

require 'config.php';

if(isset($_POST['login'])){
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

    $sql = 'SELECT id, username, administrator, password FROM users WHERE username = :username AND deleted=false';
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':username', $username);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user === false){
        die('Incorrect username / password combination!');
    }else{
       $validPassword = password_verify($passwordAttempt, $user['password']);
    
        if($validPassword){
            $userToken = bin2hex(openssl_random_pseudo_bytes(24));
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_token'] = $userToken;
            $_SESSION['logged_in'] = time();

            header('Location: home.php');
            exit;
        }else{
            die('Incorrect username / password combination!');
        }
    }

}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password</label>
            <input type="text" id="password" name="password"><br>
            <input type="submit" name="login" value="Login">
        </form>
    </body>
</html>