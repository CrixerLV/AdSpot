<?php
require "db_con.php";

if (
    isset($_POST['adId']) &&
    isset($_POST['adName']) &&
    isset($_POST['adPrice']) &&
    isset($_POST['adDescription']) &&
    isset($_POST['adLocation']) &&
    isset($_POST['adType']) &&
    isset($_POST['sellerId'])
) {
    $adId = $_POST['adId'];
    $adName = htmlspecialchars($_POST['adName']);
    $adPrice = htmlspecialchars($_POST['adPrice']);
    $adDescription = htmlspecialchars($_POST['adDescription']);
    $adLocation = htmlspecialchars($_POST['adLocation']);
    $adType = htmlspecialchars($_POST['adType']);
    $sellerId = htmlspecialchars($_POST['sellerId']);
    
    $query = "UPDATE ads SET adName = ?, adPrice = ?, adDescription = ?, adLocation = ?, adType = ?, sellerId = ? WHERE adId = ?";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$adName, $adPrice, $adDescription, $adLocation, $adType, $sellerId, $adId]);
        
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
