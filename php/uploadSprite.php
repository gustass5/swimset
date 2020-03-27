<?php
if(isset($_POST['submit']))
    $file = $_FILES['file'];
    
    $fileName = $file['name'];
    $fileTmpName= $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            if($fileSize < 500000){
                $newFileName = uniqid('', true).".".$fileActualExt;
                $fileDesination = '../uploads/'.$newFileName;
                move_uploaded_file($fileTmpName, $fileDesination);
            }else{
                echo "Your file is too big";
            }
        }else{
            echo "There was an error uploading your file";
        }
    }else{
        echo "You cannot upload files of this type";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Register</title>
        <link rel='stylesheet' href='../css/main.css' />
    </head>
    
    <body>
        <form action="uploadSprite.php" method="POST" enctype="multipart/form-data">
            <input type='file' name='file' >
            <button type='submit' name='submit'>Upload</button>
        </form>        
    </body>

</html>