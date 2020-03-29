<?php
session_start();

require 'config.php';
require "helpers.php";

checkLoggedIn();

if(isset($_POST['submit'])){
    try{
        validateNewSprite();
        uploadNewSprite();
    }catch(Exception $error){
        echo $error; 
    }
}

function validateNewSprite(){
    $title = !empty(trim($_POST['title'])) ? trim($_POST['title']) : null;
    $file = $_FILES['file'];
    
    $spriteExceptions = [];

    if($title === null){
        $spriteExceptions[] = "Name was not provided";
    }

    if(!file_exists($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $spriteExceptions[] =  'No file selected';

    }else{
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if(!in_array($fileActualExt, $allowed)){
            $spriteExceptions[] = "You cannot upload files of this type";
        }

        if($fileError !== 0){
            $spriteExceptions[] = "There was an error uploading your file";
        }

        if($fileSize > 500000){
            $spriteExceptions[] = "Your file is too big";
        }
    }

    if(count($spriteExceptions) > 0){
        foreach($spriteExceptions as $exception){
            echo $exception.'<br />';
        }
        throw new Exception;
    }
}

function uploadNewSprite(){
    Global $pdo;

    $title = trim($_POST['title']);
    $description = !empty(trim($_POST['description'])) ? trim($_POST['description']) : null;
    $file = $_FILES['file'];
    
    $fileName = $file['name'];
    $fileTmpName= $file['tmp_name'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $newFileName = uniqid('', true).".".$fileActualExt;
    $fileDesination = '../uploads/'.$newFileName;

    $sql = 'INSERT INTO sprites (title, description, path, user_id) VALUES (:title, :description, :path, :user_id)';

    $statement = $pdo->prepare($sql);

    $statement->bindValue(':title', $title);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':path', $newFileName);
    $statement->bindValue(':user_id', $_SESSION["user_id"]);

    $result = $statement->execute();

    if($result){
        // $$ Check if error while uploading
        move_uploaded_file($fileTmpName, $fileDesination);
        echo 'Uploaded';
    }
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Upload</title>
        <link rel='stylesheet' href='../css/main.css' />
    </head>
    
    <body>
        <form action="uploadSprite.php" method="post" enctype="multipart/form-data">
            <label for='sprite-name'>Enter sprite/spritesheet name</label>
            <input id='sprite-name' type='text' name='title'><br />
            <textarea name='description' placeholder="Write short description (optional)"></textarea>
            <label for='sprite-upload'>Upload sprite</label><br />
            <input id='sprite-upload' type='file' name='file' >
            <button type='submit' name='submit'>Upload</button>
        </form>        
    </body>

</html>