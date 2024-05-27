<?php
require "backend/db_con.php";
include("backend/authorization.php");



if (!isset($pdo)) {
    die("Connection not established. Check your database connection.");
}

if (isset($_GET['adId'])) {
    $adId = $_GET['adId'];

    $mainQuery = "SELECT ads.*, ad_images.image_path 
            FROM ads 
            LEFT JOIN ad_images ON ads.adId = ad_images.ad_id 
            WHERE ads.adId = :adId";
    $mainStmt = $pdo->prepare($mainQuery);
    $mainStmt->bindParam(':adId', $adId);
    $mainStmt->execute();

    $mainRow = $mainStmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - <?php echo $mainRow['adName']?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image" href="favico.png">

    <style>
        #card-ad:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .card-text i {
            margin-right: 5px;
        }

        #externals:hover{
            transform: scale(1.1);
            transition: transform 0.1s ease-in-out;
            color: #0d6efd;
            cursor: pointer;
        }
        .nav-link{
            color: black;
            font-weight: bold;
        }
        .nav-link:focus, .nav-link:hover {
            color: #0d6efd;
        }
    </style>

</head>

<body class="bg-light" style="font-family: 'Open Sans', sans-serif;">
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top">
            <div class="container-fluid ">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <a class="navbar-brand" href="dashboard.php"><img src="Logo.png" class="w-50" alt="Logo"></a>
                    <ul class="navbar-nav d-flex d-sm-flex flex-sm-row flex-column align-items-center justify-content-center text-center">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Sākums</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="allads.php">Visi Sludinājumi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create_ad.php">Izveidot Sludinājumu</a>
                        </li>
                    </ul>
                    <?php
                    if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
                        echo '<div class="dropdown ms-auto me-5 pe-5 d-flex flex-row align-items-center">';
                        echo '<a href="#" class="nav-link nav-item dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
                        echo '<ul class="dropdown-menu mx-0" aria-labelledby="dropdownMenuLink">';
                        echo '<li><a class="dropdown-item" href="profile.php">Profils</a></li>';
                        echo '<li><a class="dropdown-item" href="messages.php">Ziņojumi</a></li>';
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
            </div>
        </nav>
        <div class="container-fluid mt-4">
            <div class="row bg-white mx-2">
                    <div class="col">
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
                                echo '<div class="mb-3">';
                                ?>
                                <div class="row g-1">
                                    <?php
                                    if (isset($_GET['adId'])) {
                                        $adId = $_GET['adId'];

                                        try {
                                            $imageQuery = "SELECT image_path FROM ad_images WHERE ad_id = :adId";
                                            $imageStmt = $pdo->prepare($imageQuery);
                                            $imageStmt->bindParam(':adId', $adId);
                                            $imageStmt->execute();
                                            $images = $imageStmt->fetchAll(PDO::FETCH_ASSOC);

                                            $totalImages = count($images);
                                            $displayedImages = array_slice($images, 0, min($totalImages, 4));

                                            foreach ($displayedImages as $index => $image) {
                                                echo '<div class="col">';
                                                echo '<a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">';
                                                echo '<img src="/AdSpot/AdImages/' . $image['image_path'] . '" class="img-fluid" alt="Ad Image">';
                                                echo '</a>';
                                                echo '</div>';
                                            }

                                            echo '<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">';
                                            echo '<div class="modal-dialog modal-dialog-centered modal-xl">';
                                            echo '<div class="modal-content">';
                                            echo '<div class="modal-header">';
                                            echo '<h5 class="modal-title" id="imageModalLabel">' . $mainRow['adName'] . '</h5>';
                                            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                            echo '</div>';
                                            echo '<div class="modal-body">';
                                            echo '<div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">';
                                            echo '<div class="carousel-inner">';

                                            foreach ($images as $index => $image) {
                                                echo '<div class="carousel-item' . ($index === 0 ? ' active' : '') . '">';
                                                echo '<img src="/AdSpot/AdImages/' . $image['image_path'] . '" class="d-block w-100" alt="Ad Image">';
                                                echo '</div>';
                                            }

                                            echo '</div>';
                                            echo '<button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">';
                                            echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                                            echo '<span class="visually-hidden">Previous</span>';
                                            echo '</button>';
                                            echo '<button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">';
                                            echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                                            echo '<span class="visually-hidden">Next</span>';
                                            echo '</button>';
                                            echo '</div>';

                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';

                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                    }
                                    ?>
                                </div>
                            <div class="container-fluid mt-5">
                                <?php
                                echo '<div class="container d-sm-flex justify-content-around align-items-center m-auto">';
                                    echo '<div class="">';
                                    echo '<h3 class="card-text text-black"><strong class="text-black">' . $mainRow['adName'] . '</strong><h3>';
                                    echo '</div>';
                                    echo '<div class="">';
                                        echo '<h3 class="card-text text-black"><strong class="text-primary">' . $mainRow['adPrice'] . ' €</strong><h3>';
                                    echo '</div>';
                                echo '</div>';
                                echo '<hr class="w-100 pb-5 mt-5">';
                                echo '<div class="row justify-content-evenly">';
                                    echo '<div class="col-sm-3 mb-4">';
                                        echo '<p class="card-text bg-white text-dark rounded">' . $mainRow['adDescription'] . '</p>';
                                    echo '</div>';
                                echo '<div class="d-block col-sm-3 mt-4 mb-4">';
                                    echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-map-pin"></i></strong> ' . $mainRow['adLocation'] . '</p>';
                                    echo '<p class="card-text text-muted"><strong class="text-black"><i class="fas fa-tags"></i></strong> '. $mainRow['adType'] . '</p>';
                                switch ($mainRow['adType']) {
                                    case 'Transports':
                                        $vehicleQuery = "SELECT * FROM vehicles WHERE adId = :adId";
                                        $vehicleStmt = $pdo->prepare($vehicleQuery);
                                        $vehicleStmt->bindParam(':adId', $adId);
                                        $vehicleStmt->execute();
                                
                                        $vehicleRow = $vehicleStmt->fetch(PDO::FETCH_ASSOC);
                                
                                        if ($vehicleRow) {
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-layer-group"></i></strong> ' . $vehicleRow['vehicleType'] . '</p>';
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-copyright"></i></strong> ' . $vehicleRow['vehicleBrand'] . '</p>';
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
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-layer-group"></i></strong> ' . $petRow['petType'] . '</p>';
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-copyright"></i></strong> ' . $petRow['petBrand'] . '</p>';
                                        }
                                        break;

                                    case 'Elektronika':
                                        $electronicQuery = "SELECT * FROM electronics WHERE adId = :adId";
                                        $electronicStmt = $pdo->prepare($electronicQuery);
                                        $electronicStmt->bindParam(':adId', $adId);
                                        $electronicStmt->execute();
                                
                                        $electronicRow = $electronicStmt->fetch(PDO::FETCH_ASSOC);
                                
                                        if ($electronicRow) {
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-layer-group"></i></strong> ' . $electronicRow['electronicType'] . '</p>';
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-copyright"></i></strong> ' . $electronicRow['electronicBrand'] . '</p>';
                                        }
                                        break;

                                    case 'Mājai, dārzam':
                                        $furnitureQuery = "SELECT * FROM furniture WHERE adId = :adId";
                                        $furnitureStmt = $pdo->prepare($furnitureQuery);
                                        $furnitureStmt->bindParam(':adId', $adId);
                                        $furnitureStmt->execute();
                                
                                        $furnitureRow = $furnitureStmt->fetch(PDO::FETCH_ASSOC);
                                
                                        if ($furnitureRow) {
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-layer-group"></i></strong> ' . $furnitureRow['furnitureType'] . '</p>';
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-copyright"></i></strong> ' . $furnitureRow['furnitureBrand'] . '</p>';
                                        }
                                        break;
                                    case 'Darbs un bizness':
                                        $jobQuery = "SELECT * FROM jobs WHERE adId = :adId";
                                        $jobStmt = $pdo->prepare($jobQuery);
                                        $jobStmt->bindParam(':adId', $adId);
                                        $jobStmt->execute();
                                
                                        $jobRow = $jobStmt->fetch(PDO::FETCH_ASSOC);
                                
                                        if ($jobRow) {
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-layer-group"></i></strong> ' . $jobRow['jobType'] . '</p>';
                                            echo '<p class="card-text text-muted"><strong class="text-black"><i class="fa-solid fa-copyright"></i></strong> ' . $jobRow['jobBrand'] . '</p>';
                                        }
                                        break;
                                }
                                if (isset($mainRow['sellerId'])) {
                                    $sellerId = $mainRow['sellerId'];

                                    $sellerQuery = "SELECT * FROM users WHERE user_id = :sellerId";
                                    $sellerStmt = $pdo->prepare($sellerQuery);
                                    $sellerStmt->bindParam(':sellerId', $sellerId);
                                    $sellerStmt->execute();

                                    $sellerRow = $sellerStmt->fetch(PDO::FETCH_ASSOC);
                                }
                                echo '</div>';
                                echo '<div class="col-sm-3 d-flex flex-column my-auto text-center">';
                                echo '<button class="btn btn-primary mb-2 w-100" data-bs-toggle="modal" data-bs-target="#messageModal"><strong>Rakstīt ziņu</strong></button><br>';
                                echo '<div class="d-flex flex-row align-items-center w-100"><hr class="w-50"><label class="text-muted mx-2">Vai</label><hr class="w-50"></div>';
                                echo '<label class="mt-3 text-success">Zemāk pieejama tirgotāja informācija</label>';
                                echo '</div>';
                            } else {
                                echo "Error";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                    } else {
                        echo "Error";
                    }
                    if (isset($mainRow['sellerId'])) {
                        $sellerId = $mainRow['sellerId'];
                    
                        try {
                            $sellerQuery = "SELECT * FROM users WHERE user_id = :sellerId";
                            $sellerStmt = $pdo->prepare($sellerQuery);
                            $sellerStmt->bindParam(':sellerId', $sellerId);
                            $sellerStmt->execute();
                    
                            $sellerRow = $sellerStmt->fetch(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                    }
                    ?>
                    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="messageModal">Ziņa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="backend/message_ad.php" method="post">
                                        <div class="mb-3">
                                            <div>
                                                <div class="d-flex flex-row align-items-center">
                                                    <i class="text-white me-1">*</i><label class="fw-bold w-25">Sludinājums:</label><input readonly type="text" class="form-control text-muted w-75" value="<?php echo $mainRow['adName']?>">
                                                </div>
                                                <div class="d-flex flex-row align-items-center mt-3">
                                                <i class="text-white me-1">*</i><label class="fw-bold w-25">Pārdevējs</label><input readonly type="text" class="form-control w-75 text-muted" value="<?php echo $sellerRow['name'] . ' ' . $sellerRow['lastname'] ?>">
                                                </div>
                                                <div class="d-flex flex-row align-items-center mt-3">
                                                    <i class="text-danger me-1">*</i><label class="fw-bold w-25">Ziņas Tēma:</label><input required placeholder="Tēma/Iemesls..." type="text" class="form-control w-75">
                                                </div>
                                                    <div class="d-flex flex-row align-items-center mt-3">
                                                    <i class="text-danger me-1">*</i><label class="fw-bold w-25">Ziņa:</label><textarea required name="message" style="height:250px" class="form-control w-75"></textarea>
                                                </div>
                                                <input type="hidden" name="seller_id" value="<?php echo $sellerId; ?>">
                                                <input type="hidden" name="messager_id" value="<?php echo $_SESSION['id']; ?>">
                                                <input type="hidden" name="ad_id" value="<?php echo $adId; ?>">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Nosūtīt</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-5 mb-5">
                    <div class="row justify-content-evenly mt-4">
                        <div class="col-md-5 text-center border border rounded-5 p-2 p-sm-1">
                            <h4 class="mt-4"><strong>Komentāri</strong></h4>
                            <div class="overflow-auto" style="scrollbar-color: #0d6efd rgba(248,244,244,50); max-height: 600px">
                                <?php
                                $commentQuery = "SELECT * FROM comments WHERE ad_id = :ad_id";
                                $commentStmt = $pdo->prepare($commentQuery);
                                $commentStmt->bindParam(':ad_id', $adId);
                                $commentStmt->execute();
                                $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($comments) {
                                    foreach ($comments as $comment) {
                                        $comenteerId = $comment['comenteer_id'];
                                    
                                        $userQuery = "SELECT name, lastname FROM users WHERE user_id = :comenteerId";
                                        $userStmt = $pdo->prepare($userQuery);
                                        $userStmt->bindParam(':comenteerId', $comenteerId);
                                        $userStmt->execute();
                                        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
                                    
                                        if ($user) {
                                            echo '<div class="card border-0 mx-sm-5 mx-2">';
                                            echo '<div class="card-body text-start">';
                                            echo '<div class="row d-flex align-items-center mb-2">';
                                            echo '<div class="col-md-1">';
                                            echo '<img src="unknown.jpg" class="card-img rounded">';
                                            echo '</div>';
                                            echo '<div class="col-md-10">';
                                            echo '<h5 class="card-title m-0">' . $user['name'] . ' ' . $user['lastname'] . '</h5>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '<p class="card-text">' . $comment['comment'] . '</p>';
                                            echo '</div>';
                                            echo '<div class="d-flex text-start mx-3">';
                                            echo '<p><i class="fa-regular fa-thumbs-up text-primary"></i><strong> ' . $comment['likes'] . '</strong></p>';
                                            echo '<p class="mx-3"><i class="fa-regular fa-thumbs-down text-danger"></i><strong> ' . $comment['dislikes'] . '</strong></p>';
                                            echo '</div>';
                                            echo '<hr>';
                                            echo '</div>';
                                        } else {
                                            echo "Commenter information not found.";
                                        }
                                    }
                                } else {
                                    echo '<div class="alert alert-info" role="alert">Nav komentāru.</div>';
                                }
                                ?>
                            </div>
                            <div class="card border-0 mx-sm-5">
                                    <div class="card-body w-100 p-2">
                                        <form action="backend/comment.php" method="post">
                                            <div class="input-group">
                                                <input type="text" class="form-control p-3" placeholder="Raksti komentāru..." aria-label="Raksti komentaru" aria-describedby="button-addon2" name="comment">
                                                <input type="hidden" name="comenteer_id" value="<?php echo $sellerId; ?>">
                                                <input type="hidden" name="ad_id" value="<?php echo $adId; ?>">
                                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">Iesniegt</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-5 mt-4">
                            <div class="card border-0 text-center">
                                <h4><strong>Tirgotāja Informācija</strong></h4>
                                <div class="text-start">
                                    <div class="row d-flex">
                                        <div class="col-md-5 text-center">
                                            <?php

                                            $userId = $_SESSION['id'];

                                            $sql = "SELECT path FROM user_images WHERE user_id = :userId";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->bindParam(':userId', $userId);
                                            $stmt->execute();
                                            $imageData = $stmt->fetch(PDO::FETCH_ASSOC);
                                            
                                            if ($imageData && isset($imageData['path'])) {
                                                $imagePath = 'User_Images/' . $imageData['path'];
                                            } else {
                                                $imagePath = 'unknown.jpg';
                                            }
                                            
                                            
                                            ?>
                                            <img src="<?php echo $imagePath; ?>" alt="User Image" id="profileImage" class="img-fluid mx-auto text-center mb-3" data-bs-toggle="modal" data-bs-target="#imageModal">
                                            <h3><?php echo $sellerRow['name'] . ' ' . $sellerRow['lastname']  ?></h3>
                                            <?php
                                                if (isset($mainRow['sellerId'])) {
                                                    $sellerId = $mainRow['sellerId'];

                                                    try {
                                                        $sellerQuery = "SELECT rating, ratingamount FROM users WHERE user_id = :sellerId";
                                                        $sellerStmt = $pdo->prepare($sellerQuery);
                                                        $sellerStmt->bindParam(':sellerId', $sellerId);
                                                        $sellerStmt->execute();

                                                        $sellerRating = $sellerStmt->fetch(PDO::FETCH_ASSOC);

                                                        if ($sellerRating) {
                                                            $Rating = $sellerRating['rating'];
                                                            $ratingAmount = $sellerRating['ratingamount'];
                                                            $averageRating = $Rating > 0 ? $Rating / $ratingAmount : 0;
                                                            $averageRating = round($averageRating, 1);

                                                            echo '<div class="d-sm-flex justify-content-center text-warning">';
                                                            for ($i = 0; $i < floor($averageRating); $i++) {
                                                                echo '<i class="fa-solid fa-star"></i>';
                                                            }
                                                            if ($averageRating - floor($averageRating) > 0) {
                                                                echo '<i class="fa-solid fa-star-half"></i>';
                                                            }
                                                            for ($i = ceil($averageRating); $i < 5; $i++) {
                                                                echo '<i class="far fa-star"></i>';
                                                            }
                                                            echo '</div>';
                                                            echo '<i>Novērtējums (' . $averageRating . ')</i>';
                                                        } else {
                                                            echo "Novērtējums nav pieejams";
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo "Error: " . $e->getMessage();
                                                    }
                                                }
                                            ?>
                                        </div>
                                        <div class="col-md-6 p-2 d-block m-auto">
                                            <div class="d-flex flex-row align-items-center mb-4 shadow p-3 rounded-5">
                                                <h4 class="m-0"><i class="fa-solid fa-square-phone text-black"></i></h4>
                                                <p class="mx-2 mb-0"><strong><?php echo $sellerRow['phone'] ?></strong></p>
                                            </div>
                                                <div class="d-flex flex-row align-items-center mb-0 shadow p-3 rounded-5">
                                                <h4 class="m-0"><i class="fa-solid fa-square-envelope text-black"></i></h4>
                                                <p class="mx-2 mb-0"><strong><?php echo $sellerRow['email'] ?></strong></p>
                                            </div>
                                            <div class="d-block text-center w-100 mt-4">
                                            <button class="btn btn-danger rounded-0" data-bs-toggle="modal" data-bs-target="#reportModal">Ziņot par lietotāju!</button>
                                                <?php
                                                    $fromWhoId = $_SESSION['id'];
                                                    $toWhoId = $sellerId;
                                                    if (isset($_SESSION['id'])) {
                                                        if ($fromWhoId != $toWhoId) {
                                                            $checkVoteQuery = "SELECT * FROM votes WHERE from_who_id = :fromWhoId AND to_who_id = :toWhoId";
                                                            $checkVoteStmt = $pdo->prepare($checkVoteQuery);
                                                            $checkVoteStmt->bindParam(':fromWhoId', $fromWhoId, PDO::PARAM_INT);
                                                            $checkVoteStmt->bindParam(':toWhoId', $toWhoId, PDO::PARAM_INT);
                                                            $checkVoteStmt->execute();
                                                            $existingVote = $checkVoteStmt->fetch(PDO::FETCH_ASSOC);

                                                            if ($existingVote === false) {
                                                                echo '<button class="btn btn-warning text-white rounded-0 mt-3 mt-lg-0" data-bs-toggle="modal" data-bs-target="#rateModal">Vērtēt!</button>';
                                                            } else {
                                                                echo '<p class="mt-3">Tu jau esi novērtējis šo lietotāju!</p>';
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rateModalLabel">Vērtēt lietotāju</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="backend/rate.php" method="post">
                                                                <div class="mb-3">
                                                                    <label for="rating" class="form-label">Izvēlieties vērtējumu (1 - 5)</label>
                                                                    <div>
                                                                        <input type="radio" id="rating1" name="rating" value="1">
                                                                        <label for="rating1">1</label>
                                                                        <input type="radio" id="rating2" name="rating" value="2">
                                                                        <label for="rating1">2</label>
                                                                        <input type="radio" id="rating3" name="rating" value="3">
                                                                        <label for="rating1">3</label>
                                                                        <input type="radio" id="rating4" name="rating" value="4">
                                                                        <label for="rating1">4</label>
                                                                        <input type="radio" id="rating5" name="rating" value="5">
                                                                        <label for="rating1">5</label>
                                                                        <input type="hidden" name="seller_id" value="<?php echo $sellerId; ?>">
                                                                        <input type="hidden" name="ad_id" value="<?php echo $adId; ?>">
                                                                    </div>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Vērtēt</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="reportModalLabel">Ziņojums</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="backend/report.php" method="post">
                                                                <div class="mb-3">
                                                                    <label for="rating" class="form-label"></label>
                                                                    <div>
                                                                        <div class="d-flex flex-row align-items-center">
                                                                            <i class="text-white me-1">*</i><label class="fw-bold w-25">Ziņo par:</label><input readonly type="text" class="form-control text-muted w-75" value="<?php echo $sellerRow['name'] . ' ' . $sellerRow['lastname']?>">
                                                                        </div>
                                                                        <div class="d-flex flex-row align-items-center mt-3">
                                                                            <i class="text-danger me-1">*</i><label class="fw-bold w-25">Ziņojuma iemesls/Tēma:</label><input required placeholder="Tēma/Iemesls..." type="text" class="form-control w-75">
                                                                        </div>
                                                                            <div class="d-flex flex-row align-items-center mt-3">
                                                                            <i class="text-danger me-1">*</i><label class="fw-bold w-25">Ziņojuma iemesls/Tēma:</label><textarea required name="report" style="height:250px" class="form-control w-75"></textarea>
                                                                        </div>
                                                                        <input type="hidden" name="seller_id" value="<?php echo $sellerId; ?>">
                                                                        <input type="hidden" name="reporter_id" value="<?php echo $_SESSION['id']; ?>">
                                                                        <input type="hidden" name="ad_id" value="<?php echo $adId; ?>">
                                                                    </div>
                                                                </div>
                                                                <p class="text-muted w-75 mt-5"><strong>Privātuma politika:</strong>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                                                <button type="submit" class="btn btn-danger">Ziņot</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-1 d-flex w-100 mt-4">
                                        <hr class="p-3 mt-4">
                                        <h4 class="text-center"><strong>Citi šī lietotāja sludinājumi.</strong></h4>
                                        <?php
                                        $otherAdsQuery = "SELECT ads.adId, ads.adName, ads.adType, ads.adLocation, ads.adPrice, ads.created_at, MIN(ad_images.image_path) as image_path
                                                        FROM ads 
                                                        LEFT JOIN ad_images ON ads.adId = ad_images.ad_id
                                                        WHERE ads.sellerId = :sellerId AND ads.adId != :adId 
                                                        GROUP BY ads.adId
                                                        LIMIT 3";
                                        $otherAdsStmt = $pdo->prepare($otherAdsQuery);
                                        $otherAdsStmt->bindParam(':sellerId', $sellerId, PDO::PARAM_INT);
                                        $otherAdsStmt->bindParam(':adId', $adId, PDO::PARAM_INT);
                                        $otherAdsStmt->execute();
                                        $otherAds = $otherAdsStmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($otherAds) {
                                            foreach ($otherAds as $otherAd) {
                                                ?>
                                                <div class="col">
                                                    <div class="card border-0 shadow mt-4" id="card-ad">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-center"><strong><?php echo $otherAd['adName']; ?></strong></h5>
                                                            <?php
                                                            if (!empty($otherAd['image_path'])) {
                                                                ?>
                                                                <img src="/AdSpot/AdImages/<?php echo $otherAd['image_path']; ?>" class="img-fluid"
                                                                    alt="Ad Image">
                                                                <?php
                                                            }
                                                            ?>
                                                            <div class="p-2">
                                                            <p class="card-text text-muted"><i class="fas fa-tags  text-dark me-2"></i><?php echo $otherAd['adType']; ?></p>
                                                            <p class="card-text text-muted"><i class="fas fa-map-marker-alt  text-dark me-2"></i><?php echo $otherAd['adLocation']; ?></p>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo '<p class="mt-4 text-danger text-center">Šis lietotājs nav publicējis citus sludinājumus!</p>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-5 mb-5">
                        <form class="w-100 text-center pb-5" action="allads.php">
                            <button class="btn btn-outline-danger w-sm-25 mt-5"><i class="fa-solid fa-chevron-left"></i><strong>ATPAKAĻ</strong></button>
                        </form>
                    </div>
                            </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<footer class="row bg-dark p-sm-5 p-1 text-white m-0 footer static-bottom">
    <div class="col-md-3">
        <div class="row">
            <div class="p-0">
                <img src="Logo.png" class="w-100">
            </div>
        </div>
        <div class="row g-0 mt-3">
            <label class="text-light text-opacity-25 mt-3 mb-3">AdSpot sludinājumu vietne ir labākais un efektīvākais veids, kā tev notirgot savu īpašumu vai piederīgo mantu.</label>
        </div>
    </div>
    <div class="col-md-6 d-sm-flex">
        <div class="w-50">

        </div>
        <div class="w-sm-25">
        <label classs="mb-3"><strong>Pārvietoties</strong></label>
            <li class="list-group-item"><a href="dashboard.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Sākums</a></li>
            <li class="list-group-item"><a href="allads.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Visi sludinājumi</a></li>
            <li class="list-group-item"><a href="create_ad.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Izveidot sludinājumu</a></li>
            <li class="list-group-item"><a href="profile.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Profils</a></li>
            <label class="mt-3 mb-1"><strong>Informācija</strong></label>
            <li class="list-group-item"><a href="#" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Noteikumi</a></li>
            <li class="list-group-item"><a href="#" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Par mums</a></li>
        </div>                              
    </div>
    <div class="col-md-3">
        <div class="">
            <div class="w-100">
                <div class="text-start">
                    <label><strong>Ar mums vari sazināties šeit!</strong></label>
                </div>
                <div class="d-flex">
                    <h3 class="m-1"><i id="externals" class="fab fa-instagram-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fab fa-facebook-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fab fa-youtube-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fa-brands fa-square-x-twitter"></i></h3>
                </div>
            </div>
            <div class="mt-5">
                <div class="input-group">
                    <input type="text" class="form-control rounded-5 p-2" placeholder="Vieta ieteikumam..." aria-label="Vieta ieteikumiem" aria-describedby="button-addon2">
                    <button class="btn btn-primary rounded-5 mx-4" type="button" id="button-addon2">Iesniegt</button>
                </div>
            </div>
        </div>
    </div>
    <hr class="mt-5 mb-5">
    <p class="text-center text-light italic p-0 m-0">© 2024 AdSpot</p>
</footer>

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
