<?php
require 'php/config.php';
require 'php/helpers.php';

$id = !empty($_GET['id']) ? $_GET['id'] : null;
if($id === null || !is_numeric($id)){
    exit;
}

$sql = 'SELECT * FROM sprites WHERE id = :sprite_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':sprite_id', $id);
$statement->execute();

$sprite = $statement->fetch(PDO::FETCH_ASSOC);

if(!$sprite){
    echo 'There is no such sprite';
    exit;
}

$author = fetchUserData($sprite['user_id']);
$currentUser = fetchUserData($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Swimset</title>
    <link rel="stylesheet" href="css/main.css" />
    <link
      href="https://fonts.googleapis.com/css?family=Roboto&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
  <header class="flex bg-white shadow-2xl mb-5">
      <svg
        class="w-2 mx-05 my-auto cursor-pointer"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
      >
        <path
          class="fill-current"
          d="M1.004 5.998l10.996-5.998 10.99 6.06-10.985 5.86-11.001-5.922zm11.996 7.675v10.327l10-5.362v-10.326l-10 5.361zm-2 0l-10-5.411v10.376l10 5.362v-10.327z"
        />
      </svg>
      <input class="flex-grow" type="text" name="searchbar" />
      <nav class="flex items-center">
        <a href="php/sprites.php">
          <div class="navigation-item">
            Home
          </div>
        </a>
        <a href="php/sprites.php">
          <div class="navigation-item">
            Sprites
          </div>
        </a>
      </nav>
      <a href="php/login.php">
        <div class="navigation-item">
          Login
        </div>
      </a>
      <a href="php/register.php">
        <div class="navigation-item">
          Sign up
        </div>
      </a>
    </header>

    <main class="flex flex-col items-center">
        <header class='flex flex-col items-center justify-center'>
            <div class='text-center text-2xl capitalize mb-2'><?php echo htmlspecialchars($sprite['title'])?></div>
            <a class='flex justify-center items-center' href="uploads/<?php echo htmlspecialchars($sprite['path'])?>">
                <img class='bg-white max-w-half h-auto' src="uploads/<?php echo htmlspecialchars($sprite['path'])?>" />
            </a>
        </header>
        <section class='flex flex-col items-center'>
            <form action='./php/download-sprite.php' method='post'>
                <input type='hidden' name='path' value="<?php echo htmlspecialchars($sprite['path'])?>">
                <button type='submit' name='download_sprite' class='p-1 mb-1'>Download</button>
            </form>
            <?php if(checkLoggedIn(false) && ($currentUser['administrator'] === 1 || $sprite['user_id'] === $currentUser['id'])){?>
              <form action='./php/download-sprite.php' method='post'>
                  <input type='hidden' name='sprite_id' value="<?php echo htmlspecialchars($sprite['id'])?>">
                  <input type='hidden' name='author_id' value="<?php echo htmlspecialchars($author['id'])?>">
                  <button type='submit' name='delete_sprite' class='p-1 mb-1'>Delete</button>
              </form>
            <?php }?>
            <div class='mb-1'>
                Uploaded By: <span><?php echo htmlspecialchars($author['username'])?></span>
                <span>(<?php echo htmlspecialchars($author['role'])?>)</span>
            </div>
        </section>
        <?php if($sprite['description'] !== null){?>
                <p class='max-w-half'><?php echo htmlspecialchars($sprite['description'])?></p>
        <?php }?>
    </main>
  </body>
</html>