<?php
require_once("muteconfig.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() == true) {
$uexten = $_SESSION['username'];

header( 'Location: ./muter.php' ) ;
} else {
header( 'Location: ./start.php' ) ;
}
?>

