<?php

session_start();

require 'lib/password.php';

require '../core/config.php';

require '../helpers.php';

if(checkLoggedIn(false)){
    header('Location: ../index.php');
};

$error = "";


if(isset($_POST['login'])){
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

    $sql = 'SELECT id, username, administrator, password FROM users WHERE username = :username AND deleted=false';
    $statement = $pdo->prepare($sql);

    $statement->bindValue(':username', $username);

    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);


    if($user === false){
        $error = 'Incorrect username / password combination!';
    }else{
       $validPassword = password_verify($passwordAttempt, $user['password']);
    
        if($validPassword){
            /**
             * User token is created for logout.php script 
             */
            $userToken = bin2hex(openssl_random_pseudo_bytes(24));
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_token'] = $userToken;
            $_SESSION['logged_in'] = time();

            header('Location: ../index.php');
            exit;
        }else{
            $error = 'Incorrect username / password combination!';
        }
    }

}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Register</title>
        <link rel='stylesheet' href='../css/main.css' />
        <link
      href="https://fonts.googleapis.com/css?family=Roboto&display=swap"
      rel="stylesheet"
    />
    </head>
    <body>
        <main class='flex h-screen items-center justify-center my-auto'>
            <section class='flex flex-col px-4 py-2 bg-white shadow'>
                <h1 class='mb-1'>Login</h1>
                <form id="login-form" class='flex flex-col' action="login.php" method="post">
                    <label class='mb-1' for="username">Username</label>
                    <input class='mb-1  text-lg' type="text" id="username" name="username">
                    <label class='mb-1' for="password">Password</label>
                    <input class='mb-1  text-lg' class='mb-1' type="password" id="password" name="password">
                    <button class='p-1 bg-black text-white' type="submit" name="login">Login</button>
                    <?php if($error !== "") echo "<script type='text/javascript'>alert('$error');</script>"; ?>
                </form>
            </section>
        </main>
    </body>
</html>