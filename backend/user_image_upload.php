<?php
session_start();
require "db_con.php";

function logError($message) {
    error_log($message, 3, 'error_log.txt');
    $_SESSION['error'] = $message;
    header('Location: /profile.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userImage']) && isset($_SESSION['id'])) {
    if ($_FILES['userImage']['error'] == 0) {
        $userId = $_SESSION['id'];
        $fileName = $_FILES['userImage']['name'];
        $fileTmpPath = $_FILES['userImage']['tmp_name'];
        $fileSize = $_FILES['userImage']['size'];
        $fileType = $_FILES['userImage']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $uploadDir = "C:\\xampp\\htdocs\\AdSpot\\User_Images\\";
        $newFileName = $userId . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $getImagePathQuery = "SELECT path FROM user_images WHERE user_id = :userId";
            $stmt = $pdo->prepare($getImagePathQuery);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $currentImage = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($currentImage) {
                unlink($uploadDir . $currentImage['path']);
                $updateImagePathQuery = "UPDATE user_images SET path = :newFileName WHERE user_id = :userId";
                $stmt = $pdo->prepare($updateImagePathQuery);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':newFileName', $newFileName);
            } else {
                $updateImagePathQuery = "INSERT INTO user_images (user_id, path) VALUES (:userId, :newFileName)";
                $stmt = $pdo->prepare($updateImagePathQuery);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':newFileName', $newFileName);
            }

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Attēls veiksmīgi augšupielādēts!';
                    header('Location: /profile.php');
                    exit();
                } else {
                    logError('Database error: Could not update image path.');
                }
            } else {
                logError('Error moving the uploaded file. Check the directory permissions and paths.');
            }
        } else {
            logError('Nederīgs attēla formāts. JPG, JPEG, PNG, un GIF tipa attēli ir atļauti.');
        }
    } else {
        logError('Error: ' . $_FILES['userImage']['error'] . ', Nav pievienots attēls');
    }
} else {
    logError('Nav izvēlēts attēls vai arī lietotājs nav autorizējies.');
}
?>