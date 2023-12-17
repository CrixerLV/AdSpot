<?php
require "backend/db_con.php";
include("backend/authorization.php");

$user_id = $_SESSION['id'];

$sql = "SELECT ads.*, ad_images.image_path FROM ads
        LEFT JOIN ad_images ON ads.adId = ad_images.ad_id
        WHERE ads.sellerId = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>
<body class="bg-dark">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
    <h2 class="text-center text-white">Tavi Sludinājumi</h2>
    <table class="table table-dark table-striped text-center align-middle">
    <thead>
        <tr>
            <th>Bilde</th>
            <th>Nosaukums</th>
            <th>Cena</th>
            <th>Kategorija</th>
            <th>Lokācija</th>
            <th>Statuss</th>
            <th>Darbības</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($ads as $ad) {
            echo '<tr>';
            echo '<td><img class="rounded float-left" src="/AdSpot/AdImages/' . $ad['image_path'] . '" alt="Ad Image" style="width: 80px; height: 60px;background-color: grey;"></td>';
            echo '<td>' . $ad['adName'] . '</td>';
            echo '<td>' . $ad['adPrice'] . '€</td>';
            echo '<td>' . $ad['adType'] . '</td>';
            echo '<td>' . $ad['adLocation'] . '</td>';
            echo '<td style="color: ' . ($ad['Status'] == 1 ? 'green' : 'red') . ';">' . ($ad['Status'] == 1 ? 'Apstiprināts' : 'Neapstiprināts') . '</td>';
            echo '<td class="text-center">';
            echo '<a class="btn btn-outline-primary mx-2" href="SeeAd.php?adId=' . $ad['adId'] . '">Apskatīt</a>';
            echo '<a class="btn btn-outline-danger mx-2" href="backend\Delete_Ad.php?adId=' . $ad['adId'] . '">Dzēst</a>';
            echo '</td>';                    
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
</div>

<div class="container mt-4">
    <h2 class="text-center text-white">Citas darbības</h2>
    <div class="d-flex justify-content-center mb-2">
                <a href="create_ad.php" class="btn btn-primary me-3 btn-md">Izveido savu sludinājumu</a>
                <a href="profile.php" class="btn btn-primary btn-md">Doties uz profilu</a>
    </div>
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</html>
