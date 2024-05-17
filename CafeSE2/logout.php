<?php
session_start();

$_SESSION = [];

session_destroy();

setcookie('admin_logged_in', '', time() - 3600, '/');

header("Location: login.php");
exit();
?>