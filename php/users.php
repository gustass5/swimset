<?php
session_start();

require "config.php";
require "helpers.php";

checkLoggedIn();
checkIfAdmin();

$sql = 'SELECT id, username FROM users WHERE deleted=false';

$statement = $pdo->prepare($sql);

$statement->execute();

$users = $statement->fetchAll();

if(isset($_POST['delete_user'])){
    $sql = 'UPDATE users SET deleted=true WHERE id=:user_id';
    
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $_POST['user_id']);
    $statement->execute();
}

function checkIfAdmin(){
    $user = fetchUserData($_SESSION['user_id']);

    if($user['administrator'] === 0){
        header('Location: home.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php foreach($users as $user){
                echo htmlspecialchars($user['username']);?>
                <form action="users.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id'])?>">
                    <button name="delete_user" type="submit">Delete</button>
                </form><br />
            <?php }?>

    </body>
</html>