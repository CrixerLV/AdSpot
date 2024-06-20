<?php
require "db_con.php";
include("authorization.php");

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../admin_panel.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    header("Location: ../admin_panel.php");
    exit();
}
?>
