<?php
require "../backend/db_con.php";

if (isset($_GET['adId'])) {
    $adId = $_GET['adId'];

    try {
        $query = "UPDATE ads SET Status = 1 WHERE adId = :adId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':adId', $adId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            header("Location: /admin_panel.php");
            exit;
        } else {
            header("Location: /admin_panel.php");
            exit;
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: /admin_panel.php");
    exit;
}
?>
