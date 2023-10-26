<?php
require("db_con.php");
session_destroy();
header("Location: ../login.php");
exit();
?>