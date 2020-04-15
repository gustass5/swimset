<?php
session_start();

require '../core/config.php';
require '../helpers.php';

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
        move_uploaded_file($fileTmpName, $fileDesination);
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Upload Sprite</title>
        <link rel='stylesheet' href='../css/main.css' />
        <link
          href="https://fonts.googleapis.com/css?family=Roboto&display=swap"
          rel="stylesheet"
        />
    </head>
    
    <body>
    <header class="flex bg-white shadow-2xl mb-5 md-flex-col">
      <a class="flex mx-05 cursor-pointer md-hidden" href="../index.php">
        <svg
          class="w-2 hover-blue-500"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
        >
          <path
            class="fill-current"
            d="M1.004 5.998l10.996-5.998 10.99 6.06-10.985 5.86-11.001-5.922zm11.996 7.675v10.327l10-5.362v-10.326l-10 5.361zm-2 0l-10-5.411v10.376l10 5.362v-10.327z"
          />
        </svg>
      </a>

      <form id="search-form" class="flex flex-grow" action='/pages/sprites.php' method='get'>
        <input id="search-bar" class="flex-grow text-lg" type="text" name="search_sprite" placeholder="Search..."  value="<?php echo htmlspecialchars($_GET['search_sprite'])?>"/>
        <button class='px-1 search' type="submit">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="fill-current" d="M23.809 21.646l-6.205-6.205c1.167-1.605 1.857-3.579 1.857-5.711 0-5.365-4.365-9.73-9.731-9.73-5.365 0-9.73 4.365-9.73 9.73 0 5.366 4.365 9.73 9.73 9.73 2.034 0 3.923-.627 5.487-1.698l6.238 6.238 2.354-2.354zm-20.955-11.916c0-3.792 3.085-6.877 6.877-6.877s6.877 3.085 6.877 6.877-3.085 6.877-6.877 6.877c-3.793 0-6.877-3.085-6.877-6.877z"/></svg>
        </button>
      </form>

      <div onClick='toggleMenu()' class="navigation-item hidden md-block">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class='fill-current' d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg>
      </div>
        
      <nav id="menu" class='flex md-flex-col lg-flex'>
        <a class="flex navigation-item" href="../index.php">
            Home
        </a>
        <a class="flex navigation-item" href="sprites.php">
            Sprites
        </a>

      <?php if(checkLoggedIn(false)){
        $user = fetchUserData($_SESSION['user_id']);?>
          <a class="flex navigation-item" href="uploadSprite.php">
            Upload
          </a>
          
        <?php if($user['administrator'] === 1){ ?>
            <a class='flex navigation-item text-center text-grey' href="users.php">
              <?php echo htmlspecialchars($user['username']);?>
            </a>
        <?php }else{?>
            <div class="flex text-blue-500 navigation-item capitalize text-center">
              <?php echo htmlspecialchars($user['username'])?>
              <span>(<?php echo htmlspecialchars($user['role'])?>)</span>
            </div>
        <?php }?>
        <a class='flex navigation-item text-grey' href="../api/logout.php?token=<?= $_SESSION['user_token'] ?>">
          Logout
        </a>
      <?php }else{?>

      <a class="flex navigation-item" href="login.php">
          Login
      </a>

      <a class="flex navigation-item" href="register.php">
          Sign up
      </a>

      <?php }?>
      </nav>
    </header>

      <main>
        <section class='flex items-center justify-center'>
          <form id="upload-form" class="flex text-lg relative flex-col px-4 py-2 bg-white" action="uploadSprite.php" method="post" enctype="multipart/form-data">
              <div id='sprite-error' class='floating-label hidden absolute error-label shadow'></div>
              <label class="mb-1" for='sprite-name'>Enter sprite/spritesheet name</label>
              <input class="mb-1 text-lg" id='sprite-name' type='text' name='title' placeholder="Sprite name">
              <textarea class="mb-1" name='description' placeholder="Write short description (optional)"></textarea>
              <label class="mb-1" for='sprite-upload'>Upload sprite</label>
              <input class="mb-1" id='sprite-upload' type='file' name='file' >
              <button class='p-1 bg-black text-white' type='submit' name='submit'>Upload</button>
          </form>
        </section>        
      </main>

      <script src="../js/menu.js"></script>
      <script src='../js/upload.js'></script>
    </body>

</html>