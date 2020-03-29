<?php
session_start();

require "../core/config.php";
require "../helpers.php";

/**
 *  This page can be only accessed by an administrator
 *  He can soft-delete users
 *  Users are not entirely removed because we still want to keep their uploaded assets and display author name
 */

/**
* You can log in as administrator with username - 'Administrator' password - `admin123`
*/

checkLoggedIn();
checkIfAdmin();

$sql = 'SELECT id, username FROM users WHERE deleted=false AND administrator != 1';

$statement = $pdo->prepare($sql);

$statement->execute();

$users = $statement->fetchAll();

if(isset($_POST['delete_user'])){
    $sql = 'UPDATE users SET deleted=true WHERE id=:user_id';
    
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $_POST['user_id']);
    $statement->execute();
    header('Location: users.php');
}

function checkIfAdmin(){
    $user = fetchUserData($_SESSION['user_id']);

    if($user['administrator'] === 0){
        header('Location: ../index.php');
        exit;
    }
}

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
      
      <main class='flex text-lg flex-col bg-white px-4 py-2 items-center justify-center'>
        <table>
          <?php foreach($users as $user){?>
            <tr class='flex'>
            <td class='flex items-center min-w-10 truncate text-blue-500 mx-1'>
              <?php echo htmlspecialchars($user['username']);?>
            </td>
            <td>
              <form id='users-form' action="users.php" method="post">
                  <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id'])?>">
                  <button class='delete-button' name="delete_user" type="submit">Delete</button>
              </form>
            </td>
          </tr>
          <?php }?>
        </table>
      </main>
    </body>
    <script src="../js/menu.js"></script>
    <script src="../js/users.js"></script>
</html>