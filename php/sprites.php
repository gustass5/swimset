<?php

require 'config.php';
require 'helpers.php';

$searchValue = !empty(trim($_GET['search_sprite'])) ? trim($_GET['search_sprite']) : null;

if($searchValue !== null){
  $sql = 'SELECT * FROM sprites WHERE title LIKE :search_text OR description LIKE :search_text2';

  $statement = $pdo->prepare($sql);
  $statement->bindValue(':search_text', '%' . $searchValue . '%');
  $statement->bindValue(':search_text2', '%' . $searchValue . '%');

}else{
  $sql = 'SELECT * FROM sprites';
  $statement = $pdo->prepare($sql);
}


$statement->execute();
$sprites = $statement->fetchAll();

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Swimset</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link
      href="https://fonts.googleapis.com/css?family=Roboto&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class='bg-white'>
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
          <form id="search-form" class="flex flex-grow" action='/swimset/php/sprites.php' method='get'>
            <input id="search-bar" class="flex-grow" type="text" name="search_sprite" placeholder="Search..." />
            <button class='px-1 search' type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="fill-current" d="M23.809 21.646l-6.205-6.205c1.167-1.605 1.857-3.579 1.857-5.711 0-5.365-4.365-9.73-9.731-9.73-5.365 0-9.73 4.365-9.73 9.73 0 5.366 4.365 9.73 9.73 9.73 2.034 0 3.923-.627 5.487-1.698l6.238 6.238 2.354-2.354zm-20.955-11.916c0-3.792 3.085-6.877 6.877-6.877s6.877 3.085 6.877 6.877-3.085 6.877-6.877 6.877c-3.793 0-6.877-3.085-6.877-6.877z"/></svg>
            </button>
          </form>
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
          <?php if(checkLoggedIn(false)){
            $user = fetchUserData($_SESSION['user_id']);?>
            <?php if($user['administrator'] === 1){ ?>
              <div class="navigation-item">
                <a href="users.php"><?php echo htmlspecialchars($user['username']);?></a>
              </div>
            <?php }?>
            <a class="navigation-item" href="logout.php?token=<?= $_SESSION['user_token'] ?>">Logout</a>
          <?php }else{?>
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
          <?php }?>
    </header>
    <main class='grid-container mx-10'>
      <?php foreach($sprites as $sprite){ ?>
              <div class="relative sprite-listing shadow bg-white">
                <a class="sprite-title flex-col capitalize" href="../single-sprite.php?id=<?php echo htmlspecialchars($sprite['id']) ?>"><?php echo htmlspecialchars($sprite['title'])?><svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-2" viewBox="0 0 24 24"><path d="M12.015 7c4.751 0 8.063 3.012 9.504 4.636-1.401 1.837-4.713 5.364-9.504 5.364-4.42 0-7.93-3.536-9.478-5.407 1.493-1.647 4.817-4.593 9.478-4.593zm0-2c-7.569 0-12.015 6.551-12.015 6.551s4.835 7.449 12.015 7.449c7.733 0 11.985-7.449 11.985-7.449s-4.291-6.551-11.985-6.551zm-.015 5c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2zm0-2c-2.209 0-4 1.792-4 4 0 2.209 1.791 4 4 4s4-1.791 4-4c0-2.208-1.791-4-4-4z"/></svg></a>
                <div class="sprite-image">
                  <a href="../single-sprite.php?id=<?php echo htmlspecialchars($sprite['id']) ?>">
                    <img class="w-full h-auto" src="../uploads/<?php echo htmlspecialchars($sprite['path'])?>" />
                  </a>
                </div>
              </div>
      <?php }?>
    </main>
  </body>
</html>