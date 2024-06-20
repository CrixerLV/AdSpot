<?php
session_start();

if (isset($_SESSION["id"])) {
} elseif (isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {
    require_once "./backend/db_con.php";

    $storedUserId = $_COOKIE['user_id'];
    $storedToken = $_COOKIE['token'];


    $stmt = $pdo->prepare("SELECT user_id, name, lastname, remember_token FROM users WHERE user_id = ?");
    $stmt->execute([$storedUserId]);
    $user = $stmt->fetch();

    if ($user && password_verify($storedToken, $user['remember_token'])) {
        $_SESSION['id'] = $user['user_id'];
        $_SESSION['lastname'] = htmlspecialchars($user["lastname"]);
        $_SESSION['name'] = htmlspecialchars($user["name"]);
    }
}

if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit();
}
?>
