<?php
define('MYSQL_USER', 'dbi434358');
define('MYSQL_PASSWORD', 'Jdy1wgdHkc');
define('MYSQL_HOST', 'studmysql01.fhict.local');
define('MYSQL_DATABASE', 'dbi434358');


$pdoOptions = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false
);
 
/**
 * Connect to MySQL and instantiate the PDO object.
 */
$pdo = new PDO(
    "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE, //DSN
    MYSQL_USER, //Username
    MYSQL_PASSWORD, //Password
    $pdoOptions //Options
);
?>