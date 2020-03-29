<?php

session_start();

require 'lib/password.php';

require '../core/config.php';

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
    $role = !empty($_POST['role']) ? $_POST['role'] : null;
    
    $usernameExceptions = [];
    $currentPasswordExceptions = [];
    $confirmPasswordExceptions = [];
    $roleExceptions = [];

    if($username === null){
        $usernameExceptions[] = 'Username was not provided';
    }

    if(strpos($username, ' ') !== false ){
        $usernameExceptions = 'Username must not have spaces';
    }

    if(strlen($username) > 25){
        $usernameExceptions = 'Username is too long';
    }

    if($currentPassword === null){
        $currentPasswordExceptions[] = 'Password was not provided';
    }

    if(strpos($currentPassword, ' ') !== false){
        $currentPasswordExceptions[] = 'Password must not have spaces';
    }

    if(preg_match('/[^A-Za-z0-9]/', $currentPassword)){
        $currentPasswordExceptions[] = 'Password can only contain letters and numbers';
    }

    if(strlen($currentPassword) < 8){
        $currentPasswordExceptions[] = 'Password must have at least 8 characters';
    }

    if(!preg_match('/[0-9]/', $currentPassword)){
        $currentPasswordExceptions[] = 'Password must contain at least 1 number';
    }

    if(strpos($currentPassword, $username) !== false){
        $currentPasswordExceptions[] = 'Password must not contain username';
    }

    if($confirmPassword === null){
        $confirmPasswordExceptions[] = 'Password confirmation was not provided';
    }

    if(strcmp($confirmPassword,$currentPassword)){
        $confirmPasswordExceptions[] = 'Passwords do not match';
    }

    if($role === null){
        $roleExceptions[] = 'Role was not provided';
    }

    switch($role){
        case 'developer':
        case 'designer':
        case 'ilustrator':
        case 'vfx':
        case 'editor':
        case 'creator':
        case 'other':
            break;
        default:
        $roleExceptions[] = 'Invalid role was provided';
    }

    if(count($usernameExceptions) > 0 || count($currentPasswordExceptions) > 0 || count($confirmPasswordExceptions) > 0 || count($roleExceptions) > 0){
            echo '<div>'.$usernameExceptions[0].' - '.$currentPasswordExceptions[0].' - '.$confirmPasswordExceptions[0].' '.$roleExceptions[0].' - '.'</div>';
        throw new Exception();
    }

}

function addNewUser(){
    Global $pdo;

    $username = trim($_POST['username']);
    $password = trim($_POST['current-password']);
    $role = $_POST['role'];

    $sql = 'SELECT COUNT(username) AS num FROM users WHERE username = :username';
    $statement = $pdo->prepare($sql);

    $statement->bindValue(':username', $username);

    $statement->execute();

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if($row['num'] > 0){
        throw new Exception('That username already exists!');
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

    $sql = 'INSERT INTO users (username, password, role) VALUES (:username, :password, :role)';
    $statement = $pdo->prepare($sql);

    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $passwordHash);
    $statement->bindValue(':role', $role);

    $result = $statement->execute();

    if($result){
        header('Location: ../index.php');
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
        <main class='flex h-screen items-center justify-center'>
            <section class='flex flex-col px-4 py-2 bg-white shadow'>
                <h1 class='mb-1'>Register</h1>
                <form class='flex flex-col' id='form' action='register.php' method='post'>
                    <div class='flex relative flex-col'>    
                        <label class='mb-1' for='username'>Username</label>
                        <input class='mb-1 text-lg' type='text' id='username' name='username'>
                        <div id='username-error' class='floating-label hidden absolute error-label shadow'>
                        </div>
                    </div>
                    <div class='flex relative flex-col floating-label-wrap'>
                        <label class='mb-1' for='current-password'>Password</label>
                        <input class='mb-1 text-lg' type='password' id='current-password' name='current-password'>
                            <div id='current-password-error' class='floating-label hidden absolute error-label shadow'>
                        </div>
                    </div>
                    <div class='flex relative flex-col floating-label-wrap'>
                        <label class='mb-1' for='confirm-password'>Confirm password</label>
                        <input class='mb-1 text-lg' type='password' id='confirm-password' name='confirm-password'>
                        <div id='confirm-password-error' class='hidden absolute error-label shadow'></div>

                    </div>
                    <div class='flex flex-col'>
                        <label class='mb-1' for='rule'>What do you do?</label>
                        <select class='mb-2' id='role' name='role'>
                            <option value='developer'>Game developer</option>
                            <option value='designer'>Designer</option>
                            <option value='ilustrator'>Ilustrator</option>
                            <option value='vfx'>Visual effects artist</option>
                            <option value='editor'>Video editor</option>
                            <option value='creator'>Level creator</option>
                            <option value='other'>Other</option>
                        </select>
                    </div>
                    <button class='p-1 bg-black text-white' type='submit' name='register'>Register</button>
                </form>
            </section>
        </main>
        <script src='../js/register.js'></script>
    </body>
</html>