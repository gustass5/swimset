<?php

require 'config.php';

if(isset($_POST['compareUsername'])){

    $username = !empty(trim($_POST['compareUsername'])) ? trim($_POST['compareUsername']): null;
    if($username === null){
        throw new Exception($username);
    }

    $sql = 'SELECT COUNT(username) AS num FROM users WHERE username = :username';

    $statement = $pdo->prepare($sql);

    $statement->bindValue(':username', $username);
    $statement->execute();

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if($row['num'] > 0){
        echo json_encode(['exist'=> true]);
    }else{
        echo json_encode(['exist'=> false]);
    }
}

?>