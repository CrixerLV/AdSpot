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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-text i {
            margin-right: 5px;
        }
    </style>
</head>
<body class="bg-light">
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
                <label class="text-dark" for="priceFrom">Min cena €</label>
                <input type="number" class="form-control" id="priceFrom" name="priceFrom" min="0">
            </div>
            <div class="col-md-3">
                <label class="text-dark" for="priceTo">Max cena €</label>
                <input type="number" class="form-control" id="priceTo" name="priceTo" min="0">
            </div>
            <div class="col-md-3">
                <label class="text-dark" for="category">Kategorija</label>
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
                echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="col">
                            <a href="SeeAd.php?adId=<?php echo $row['adId']; ?>" class="card h-100 text-decoration-none">
                                <img src="/AdSpot/AdImages/<?php echo $row['image_path']; ?>" class="card-img-top" alt="Ad Image" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['adName']; ?></h5>
                                    <p class="card-text text-muted"><i class="fas fa-tags  text-dark"></i><?php echo $row['adType']; ?></p>
                                    <p class="card-text text-muted"><i class="fas fa-map-marker-alt  text-dark"></i><?php echo $row['adLocation']; ?></p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="price m-0 ml-2 text-primary" style="font-size: 1.5rem; font-weight: 500;"><?php echo $row['adPrice']; ?>€</p>
                                        <p class="price m-0 ml-2 text-muted"><i class="fa-solid fa-calendar-days text-dark"></i> <?php echo substr($row['created_at'], 0, 10); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                }
                echo '</div>';
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
                echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="col">
                            <a href="SeeAd.php?adId=<?php echo $row['adId']; ?>" class="card h-100 text-decoration-none">
                                <img src="/AdSpot/AdImages/<?php echo $row['image_path']; ?>" class="card-img-top" alt="Ad Image" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['adName']; ?></h5>
                                    <p class="card-text text-muted"><i class="fas fa-tags  text-dark"></i><?php echo $row['adType']; ?></p>
                                    <p class="card-text text-muted"><i class="fas fa-map-marker-alt  text-dark"></i><?php echo $row['adLocation']; ?></p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="price m-0 ml-2 text-primary" style="font-size: 1.5rem; font-weight: 500;"><?php echo $row['adPrice']; ?>€</p>
                                        <p class="price m-0 ml-2 text-muted"><i class="fa-solid fa-calendar-days text-dark"></i> <?php echo substr($row['created_at'], 0, 10); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                }
                echo '</div>';
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
                echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="col">
                            <a href="SeeAd.php?adId=<?php echo $row['adId']; ?>" class="card bg-white h-100 text-decoration-none p-3" style="border-radius: 0; border: 0">
                                <img src="/AdSpot/AdImages/<?php echo $row['image_path']; ?>" class="card-img-top" alt="Ad Image" style="height: 200px; object-fit: cover;">
                                <div class="p-2">
                                    <h5 class="card-title"><?php echo $row['adName']; ?></h5>
                                    <p class="card-text text-muted"><i class="fas fa-tags  text-dark"></i><?php echo $row['adType']; ?></p>
                                    <p class="card-text text-muted"><i class="fas fa-map-marker-alt  text-dark"></i><?php echo $row['adLocation']; ?></p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="price m-0 ml-2 text-primary" style="font-size: 1.5rem; font-weight: 500;"><?php echo $row['adPrice']; ?>€</p>
                                        <p class="price m-0 ml-2 text-muted"><i class="fa-solid fa-calendar-days text-dark"></i> <?php echo substr($row['created_at'], 0, 10); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                }
                echo '</div>';
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
