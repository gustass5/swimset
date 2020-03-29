<?php
require '../core/config.php';
require '../helpers.php';

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
    <title>Sprite</title>
    <link rel="stylesheet" href="../css/main.css" />
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

      <form id="search-form" class="flex flex-grow" action='/swimset/pages/sprites.php' method='get'>
        <input id="search-bar" class="flex-grow text-lg" type="text" name="search_sprite" placeholder="Search..." />
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

    <main class="flex flex-col items-center">
        <div class='flex flex-col items-center justify-center'>
            <div class='text-center text-2xl capitalize mb-2'><?php echo htmlspecialchars($sprite['title'])?></div>
            <a class='flex justify-center items-center' href="../uploads/<?php echo htmlspecialchars($sprite['path'])?>">
                <img class='bg-white max-w-half h-auto' src="../uploads/<?php echo htmlspecialchars($sprite['path'])?>" />
            </a>
      </div>
        <section class='flex flex-col items-center'>
            <form action='../api/handle-sprite.php' method='post'>
                <input type='hidden' name='path' value="<?php echo htmlspecialchars($sprite['path'])?>">
                <button class='download-button' type='submit' name='download_sprite' class='p-1 mb-1'>Download</button>
            </form>
            <?php if(checkLoggedIn(false) && ($currentUser['administrator'] === 1 || $sprite['user_id'] === $currentUser['id'])){?>
              <form action='../api/handle-sprite.php' method='post'>
                  <input type='hidden' name='sprite_id' value="<?php echo htmlspecialchars($sprite['id'])?>">
                  <input type='hidden' name='author_id' value="<?php echo htmlspecialchars($author['id'])?>">
                  <button class='delete-button' type='submit' name='delete_sprite' class='p-1 mb-1'>Delete</button>
              </form>
            <?php }?>
            <div class='mb-1'>
                Uploaded By: <span class='text-lg text-blue-500'><?php echo htmlspecialchars($author['username'])?></span>
                <span>(<?php echo htmlspecialchars($author['role'])?>)</span>
            </div>
        </section>
        <?php if($sprite['description'] !== null){?>
                <p class='max-w-half'><?php echo htmlspecialchars($sprite['description'])?></p>
        <?php }?>
    </main>
    <script src="../js/menu.js"></script>
  </body>
</html>