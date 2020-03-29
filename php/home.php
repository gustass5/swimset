<?php

session_start();
require "helpers.php";

checkLoggedIn();
?>

<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        	
<a href="logout.php?token=<?= $_SESSION['user_token'] ?>">Logout</a>
    </body>
</html>