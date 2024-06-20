<?php
require "db_con.php";

if (
    isset($_POST['user_id']) &&
    isset($_POST['phone']) &&
    isset($_POST['name']) &&
    isset($_POST['email']) &&
    isset($_POST['lastname']) &&
    isset($_POST['adress']) &&
    isset($_POST['dob'])) 
{
    $userId = $_POST['user_id'];
    $phone = htmlspecialchars($_POST['phone']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $adress = htmlspecialchars($_POST['adress']);
    $dob = htmlspecialchars($_POST['dob']);
    
    $query = "UPDATE users SET phone = ?, name = ?, email = ?, lastname = ?, adress = ?, dob = ? WHERE user_id = ?";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$phone, $name, $email, $lastname, $adress, $dob, $userId]);
        
        header("Location: /admin_panel.php");
        exit();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: /admin_panel.php");
    exit();
}
?>
