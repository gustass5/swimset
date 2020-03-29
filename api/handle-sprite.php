<?php
session_start();

require '../core/config.php';
require '../helpers.php';

/**
 * File download
 */
if(isset($_POST['download_sprite'])){
    $path = "../uploads/".basename($_POST['path']);
    
    if (file_exists($path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($path).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}

/**
 * Sprite deletion handling
 */
if(isset($_POST['delete_sprite'])){
    checkLoggedIn();
    $currentUser = fetchUserData($_SESSION['user_id']);
  
    if($currentUser['administrator'] === 0 && $_POST['author_id'] !== $currentUser['id']){
      echo 'You do not have permission to do that';
      return;
    }

    $sql = 'SELECT path FROM sprites WHERE id = :sprite_id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':sprite_id', $_POST['sprite_id']);
    $statement->execute();

    $spritePath = $statement->fetch(PDO::FETCH_ASSOC);

    if($spritePath){
      removeFromDatabase($spritePath['path']);
    }else{
      echo 'Sprite does not exist';
    }

}

/**
 * Complete sprite removal
 */
function removeFromDatabase($spritePath){
  Global $pdo;
  $path = "../uploads/".basename($spritePath);
  $sql = 'DELETE FROM sprites WHERE id = :sprite_id';
  
  $statement = $pdo->prepare($sql);

  $statement->bindValue(':sprite_id', $_POST['sprite_id']);
  $statement->execute();

  if(!$statement->rowCount()) {
    echo "Deletion failed";
    return;
  }

  unlink($path);

  header('Location: ../pages/sprites.php');
}

?>