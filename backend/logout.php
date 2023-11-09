<?php
require("db_con.php");

$_SESSION = array();
session_destroy();

setcookie('user_id', '', time() - 3600, '/');
setcookie('token', '', time() - 3600, '/');

header("Location: ../login.php");
exit();
?>
