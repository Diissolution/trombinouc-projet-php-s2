<?php
session_start();
$_SESSION = array(); //Tableau $_SESSION vidé
session_destroy();  //Session détruite
setcookie('PHPSESSID', '', time() - 3600); //Destruction du cookie
header('Location:index.php?msg=logout');
exit();
?>