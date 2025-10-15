<?php
require_once("classes/Login.php");

$login = new Login();

if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
$uexten = $_SESSION['username'];
} else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
header( 'Location: ./start.php' ) ;
}
?>
