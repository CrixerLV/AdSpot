<?php
require "backend/db_con.php";
include("backend/authorization.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Sludinājuma apskatīšana</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>

<body class="bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Sākums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="allads.php">Visi Sludinājumi</a>
                    </li>
                </ul>
                <form method="get" action="allads.php" class="form-inline mx-2">
                    <div class="input-group">
                        <input class="form-control mx-1" style="width: 300px;" type="search" name="search" placeholder="Meklē pēc nosaukuma" aria-label="Search">
                        <button class="btn btn-light" type="submit">Meklēt</button>
                    </div>
                </form>
            </div>
            <?php
            if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
                echo '<div class="dropdown ms-auto px-2">';
                echo '<a href="#" class="nav-link nav-item text-white dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
                echo '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                echo '<li><a class="dropdown-item" href="profile.php">Profils</a></li>';
                echo '<li><a class="dropdown-item" href="user_ads.php">Mani sludinājumi</a></li>';

                $adminCheckSql = "SELECT admin FROM users WHERE user_id = :user_id";
                $adminCheckStmt = $pdo->prepare($adminCheckSql);
                $adminCheckStmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $adminCheckStmt->execute();
                $isAdmin = $adminCheckStmt->fetchColumn();

                if ($isAdmin) {
                    echo '<li><a class="dropdown-item" href="admin_panel.php">Admina panelis</a></li>';
                }

                echo '<li><a class="dropdown-item" href="./backend/logout.php">Iziet</a></li>';
                echo '</ul>';
                echo '</div>';
            }
        ?>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="w-100">
                <?php
                if (!isset($pdo)) {
                    die("Connection not established. Check your database connection.");
                }

                if (isset($_GET['adId'])) {
                    $adId = $_GET['adId'];

                    try {
                        $mainQuery = "SELECT ads.*, ad_images.image_path 
                                FROM ads 
                                LEFT JOIN ad_images ON ads.adId = ad_images.ad_id 
                                WHERE ads.adId = :adId";
                        $mainStmt = $pdo->prepare($mainQuery);
                        $mainStmt->bindParam(':adId', $adId);
                        $mainStmt->execute();

                        $mainRow = $mainStmt->fetch(PDO::FETCH_ASSOC);

                        if ($mainRow) {
                            echo '<div class="card mb-3">';
                            echo '<img src="/AdSpot/AdImages/' . $mainRow['image_path'] . '" class="card-img-top mx-auto d-block" style="max-width: 50%;" alt="Ad Image">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $mainRow['adName'] . '</h5>';
                            echo '<p class="card-text">' . $mainRow['adDescription'] . '</p>';
                            echo '<p class="card-text"><strong>Cena:</strong> <small class="text-muted">$' . $mainRow['adPrice'] . '</small></p>';
                            echo '<p class="card-text"><strong>Lokācija:</strong> ' . $mainRow['adLocation'] . '</p>';
                            echo '<p class="card-text"><strong>Kategorija:</strong> <small class="text-muted">' . $mainRow['adType'] . '</small></p>';
                            if (isset($mainRow['sellerId'])) {
                                $sellerId = $mainRow['sellerId'];

                                $sellerQuery = "SELECT * FROM users WHERE user_id = :sellerId";
                                $sellerStmt = $pdo->prepare($sellerQuery);
                                $sellerStmt->bindParam(':sellerId', $sellerId);
                                $sellerStmt->execute();

                                $sellerRow = $sellerStmt->fetch(PDO::FETCH_ASSOC);
                            }
                            switch ($mainRow['adType']) {
                                case 'Transports':
                                    $vehicleQuery = "SELECT * FROM vehicles WHERE adId = :adId";
                                    $vehicleStmt = $pdo->prepare($vehicleQuery);
                                    $vehicleStmt->bindParam(':adId', $adId);
                                    $vehicleStmt->execute();
                            
                                    $vehicleRow = $vehicleStmt->fetch(PDO::FETCH_ASSOC);
                            
                                    if ($vehicleRow) {
                                        echo '<p class="card-text"><strong>Tips:</strong> ' . $vehicleRow['vehicleType'] . '</p>';
                                        echo '<p class="card-text"><strong>Marka:</strong> ' . $vehicleRow['vehicleBrand'] . '</p>';
                                    } else {
                                        echo "Error: Vehicle details not found.";
                                    }
                                    break;
                            
                                case 'Dzīvnieki':
                                    $petQuery = "SELECT * FROM pets WHERE adId = :adId";
                                    $petStmt = $pdo->prepare($petQuery);
                                    $petStmt->bindParam(':adId', $adId);
                                    $petStmt->execute();
                            
                                    $petRow = $petStmt->fetch(PDO::FETCH_ASSOC);
                            
                                    if ($petRow) {
                                        echo '<p class="card-text"><strong>Kategorija:</strong> ' . $petRow['petCategory'] . '</p>';
                                        echo '<p class="card-text"><strong>Šķirne:</strong> ' . $petRow['petBreed'] . '</p>';
                                    }
                                    break;

                                case 'Elektronika':
                                    $electronicQuery = "SELECT * FROM electronics WHERE adId = :adId";
                                    $electronicStmt = $pdo->prepare($electronicQuery);
                                    $electronicStmt->bindParam(':adId', $adId);
                                    $electronicStmt->execute();
                            
                                    $electronicRow = $electronicStmt->fetch(PDO::FETCH_ASSOC);
                            
                                    if ($electronicRow) {
                                        echo '<p class="card-text"><strong>Tips:</strong> ' . $electronicRow['electronicType'] . '</p>';
                                        echo '<p class="card-text"><strong>Kategorija:</strong> ' . $electronicRow['electronicBrand'] . '</p>';
                                    }
                                    break;

                                case 'Elektronika':
                                    $electronicQuery = "SELECT * FROM electronics WHERE adId = :adId";
                                    $electronicStmt = $pdo->prepare($electronicQuery);
                                    $electronicStmt->bindParam(':adId', $adId);
                                    $electronicStmt->execute();
                            
                                    $electronicRow = $electronicStmt->fetch(PDO::FETCH_ASSOC);
                            
                                    if ($electronicRow) {
                                        echo '<p class="card-text"><strong>Tips:</strong> ' . $electronicRow['electronicType'] . '</p>';
                                        echo '<p class="card-text"><strong>Kategorija:</strong> ' . $electronicRow['electronicBrand'] . '</p>';
                                    }
                                    break;
                            }
                            
                            if (isset($mainRow['sellerId'])) {
                                echo '<div class="card">';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title">Tirgotāja kontakti</h5>';
                                echo '<p class="card-text"><strong>Email:</strong> ' . $sellerRow['email'] . '</p>';
                                echo '<p class="card-text"><strong>Telefons:</strong> ' . $sellerRow['phone'] . '</p>';
                                echo '</div>';
                                echo '</div>';
                            } else {
                                echo "Error: Seller details not found.";
                            }
                        } else {
                            echo "Error";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "Error";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script>
        const priceRange = document.getElementById('priceRange');
        const priceRangeValue = document.getElementById('priceRangeValue');
        priceRange.addEventListener('input', () => {
            priceRangeValue.innerText = priceRange.value;
        });
    </script>
</body>

</html>
