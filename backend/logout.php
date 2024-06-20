<?php
require "db_con.php";
include "authorization.php";

setcookie('user_id', '', time() - 3600, '/');
setcookie('token', '', time() - 3600, '/');

$_SESSION = array();
session_destroy();

header("Location: /index.php");
exit();
?>
