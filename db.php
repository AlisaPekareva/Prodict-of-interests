<?php

//часть для подключения БД + массив для тестирования, запис. в КУКИ

define('DBSERVER', 'localhost'); // Database server
define('DBUSERNAME', 'root'); // Database username
define('DBPASSWORD', ''); // Database password
define('DBNAME', 'product'); // Database name
 

 /* подключение к  MySQL database */
$mysqli = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
$mysqli->set_charset("utf8mb4");

if($mysqli === false){
    die("Error: connection error. " . mysqli_connect_error());
} 

?>