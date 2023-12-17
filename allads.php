<?php
require "backend/db_con.php";
include("backend/authorization.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Sludinājumi</title>
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
                    <a class="nav-link text-white" href="allads.php">Visi Sludinājumi</a>
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
    <form method="post">
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="text-white" for="priceFrom">Min cena €</label>
                <input type="number" class="form-control" id="priceFrom" name="priceFrom" min="0">
            </div>
            <div class="col-md-3">
                <label class="text-white" for="priceTo">Max cena €</label>
                <input type="number" class="form-control" id="priceTo" name="priceTo" min="0">
            </div>
            <div class="col-md-3">
                <label class="text-white" for="category">Kategorija</label>
                <select class="form-select" id="category" name="category">
                    <option value="">Izvēlies kategoriju</option>
                    <?php
                    $categoriesQuery = "SELECT DISTINCT adType FROM ads";
                    $categoriesStmt = $pdo->query($categoriesQuery);
                    while ($category = $categoriesStmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $category['adType'] . '">' . $category['adType'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="text-white">&nbsp;</label>
                <div class="input-group">
                    <button type="submit" class="btn btn-primary" name="searchButton">Meklēt</button>
                </div>
            </div>
        </div>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $query = "SELECT ads.*, ad_images.image_path 
                      FROM ads 
                      LEFT JOIN ad_images ON ads.adId = ad_images.ad_id 
                      WHERE 1";

            if (isset($_POST['priceFrom']) && $_POST['priceFrom'] !== "") {
                $query .= " AND adPrice >= :priceFrom";
            }
            if (isset($_POST['priceTo']) && $_POST['priceTo'] !== "") {
                $query .= " AND adPrice <= :priceTo";
            }
            if (isset($_POST['category']) && $_POST['category'] !== "") {
                $query .= " AND adType = :category";
            }

            $stmt = $pdo->prepare($query);

            if (isset($_POST['priceFrom']) && $_POST['priceFrom'] !== "") {
                $stmt->bindParam(':priceFrom', $_POST['priceFrom'], PDO::PARAM_INT);
            }
            if (isset($_POST['priceTo']) && $_POST['priceTo'] !== "") {
                $stmt->bindParam(':priceTo', $_POST['priceTo'], PDO::PARAM_INT);
            }
            if (isset($_POST['category']) && $_POST['category'] !== "") {
                $stmt->bindParam(':category', $_POST['category'], PDO::PARAM_STR);
            }

            $stmt->execute();

            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                echo '<table class="table table-dark table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>Bilde</th>
                                <th>Nosaukums</th>
                                <th>Cena</th>
                                <th>Kategorija</th>
                                <th>Lokācija</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td><img class="rounded float-left" src="/AdSpot/AdImages/' . $row['image_path'] . '" alt="Ad Image" style="width: 80px; height: 60px;background-color: grey;"></td>';
                    echo '<td>' . $row['adName'] . '</td>';
                    echo '<td>' . $row['adPrice'] . '€</td>';
                    echo '<td>' . $row['adType'] . '</td>';
                    echo '<td>' . $row['adLocation'] . '</td>';
                    echo '<td><a class="btn btn-outline-primary" href="SeeAd.php?adId=' . $row['adId'] . '">Apskatīt</a></td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            } else {
                echo "<h1 class='text-white text-center'>Nekas netika atrasts.</h1>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else if (isset($_GET['search'])) {
        $searchTerm = '%' . $_GET['search'] . '%';
        $query = "SELECT ads.*, ad_images.image_path 
                  FROM ads 
                  LEFT JOIN ad_images ON ads.adId = ad_images.ad_id 
                  WHERE adName LIKE :searchTerm";

        try {
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                echo '<table class="table table-dark table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>Bilde</th>
                                <th>Nosaukums</th>
                                <th>Cena</th>
                                <th>Kategorija</th>
                                <th>Lokācija</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td><img class="rounded float-left" src="/AdSpot/AdImages/' . $row['image_path'] . '" alt="Ad Image" style="width: 80px; height: 60px;background-color: grey;"></td>';
                    echo '<td>' . $row['adName'] . '</td>';
                    echo '<td>' . $row['adPrice'] . '€</td>';
                    echo '<td>' . $row['adType'] . '</td>';
                    echo '<td>' . $row['adLocation'] . '</td>';
                    echo '<td><a class="btn btn-outline-primary" href="SeeAd.php?adId=' . $row['adId'] . '">Apskatīt</a></td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            } else {
                echo "<h1 class='text-white text-center'>Nekas netika atrasts.</h1>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        try {
            $query = "SELECT ads.*, ad_images.image_path 
                      FROM ads 
                      LEFT JOIN ad_images ON ads.adId = ad_images.ad_id";
            $stmt = $pdo->query($query);

            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                echo '<table class="table table-dark table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>Bilde</th>
                                <th>Nosaukums</th>
                                <th>Cena</th>
                                <th>Kategorija</th>
                                <th>Lokācija</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td><img class="rounded float-left" src="/AdSpot/AdImages/' . $row['image_path'] . '" alt="Ad Image" style="width: 80px; height: 60px;background-color: grey;"></td>';
                    echo '<td>' . $row['adName'] . '</td>';
                    echo '<td>' . $row['adPrice'] . '€</td>';
                    echo '<td>' . $row['adType'] . '</td>';
                    echo '<td>' . $row['adLocation'] . '</td>';
                    echo '<td><a class="btn btn-outline-primary" href="SeeAd.php?adId=' . $row['adId'] . '">Apskatīt</a></td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            } else {
                echo "<h1 class='text-white text-center'>Nekas netika atrasts.</h1>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
