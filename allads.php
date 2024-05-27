<?php
require "backend/db_con.php";
include("backend/authorization.php");

$minPrice = $maxPrice = $location = $adType = $var1 = $var2 = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Sludinājumi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image" href="favico.png">    
    <style>
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-text i {
            margin-right: 5px;
        }
        .nav-link{
            color: white;
            font-weight: bold;
        }
        .nav-link:focus, .nav-link:hover {
            color: #0d6efd;
        }
        #externals:hover{
        transform: scale(1.1);
        transition: transform 0.1s ease-in-out;
        color: #0d6efd;
        cursor: pointer;
      }
    </style>
</head>
<body class="bg-light" style="font-family: 'Open Sans', sans-serif;">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid ">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse bg-dark p-3 mt-2" id="navbarNav">
                <a class="navbar-brand d-flex justify-content-center align-items-center" href="dashboard.php">
                    <img src="LogoBetter.png" class="w-50" alt="Logo">
                </a>
                <ul class="navbar-nav d-flex d-sm-flex flex-sm-row flex-column align-items-center justify-content-center text-center">
                    <li class="nav-item">
                        <a class="nav-link text" href="dashboard.php">Sākums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text" href="allads.php">Visi Sludinājumi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text" href="create_ad.php">Izveidot Sludinājumu</a>
                    </li>
                </ul>
                <?php
                if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
                    echo '<div class="dropdown ms-auto me-lg-5 d-flex flex-row align-items-center justify-content-center">';
                    echo '<a href="#" class="nav-link nav-item dropdown-toggle text-center" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
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
<div class="container-fluid mt-4 mb-4" style="height: 100vh;">
<form id="filterForm" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="bg-white p-2 rounded">
    <div class="bg-white p-2 rounded">
        <div class="row">
            <div class="col-sm-2">
                <label for="price">Cena</label>
                <div class="input-group input-group-sm">
                    <input type="number" step="10" class="form-control" name="priceMin" id="priceMin" placeholder="Min" value="0">
                    <input type="number" step="10" class="form-control" name="priceMax" id="priceMax" placeholder="Max" value="0">
                </div>
            </div>
            <div class="col-sm-2">
                <label for="location">Lokācija</label>
                <select class="form-control form-control-sm" id="location" name="location">
                    <option value="None" style="display:none">Izvēlieties lokāciju..</option>
                        <option value="Alūksne">Alūksne</option>
                        <option value="Bauska">Bauska</option>
                        <option value="Cēsis">Cēsis</option>
                        <option value="Daugavpils">Daugavpils</option>
                        <option value="Dobele">Dobele</option>
                        <option value="Gulbene">Gulbene</option>
                        <option value="Jelgava">Jelgava</option>
                        <option value="Jēkabpils">Jēkabpils</option>
                        <option value="Jūrmala">Jūrmala</option>
                        <option value="Krāslava">Krāslava</option>
                        <option value="Kuldīga">Kuldīga</option>
                        <option value="Liepāja">Liepāja</option>
                        <option value="Madona">Madona</option>
                        <option value="Ogre">Ogre</option>
                        <option value="Rēzekne">Rēzekne</option>
                        <option value="Rīga">Rīga</option>
                        <option value="Saldus">Saldus</option>
                        <option value="Sigulda">Sigulda</option>
                        <option value="Tukums">Tukums</option>
                        <option value="Valmiera">Valmiera</option>
                        <option value="Ventspils">Ventspils</option>
                </select>
            </div>
            <div class="col-sm-2">
                <label for="adType">Tips</label>
                <select class="form-control form-control-sm" id="adType" name="adType">
                    <option value="None" style="display:none">Izvēlieties tipu...</option>
                        <option value="Transports">Transports</option>
                        <option value="Elektronika">Elektronika</option>
                        <option value="Darbs un bizness">Darbs un bizness</option>
                        <option value="Mēbeles">Mājai, dārzam</option>
                        <option value="Dzīvnieki">Dzīvnieki</option>
                        <option value="Cits">Cits</option>
                </select>
            </div>
            <div class="col-sm-2" id="vehicleTypeSection" style="display: none">
                <label for="vehicleType">Transporta veids</label>
                <select class="form-control form-control-sm" id="vehicleType" name="vehicleType">
                    <option value="" style="display:none">Izvēlies</option>
                    <option value="Vieglā automašina">Vieglās automašīnas</option>
                    <option value="Smagā automašīna">Smagās automašīnas</option>
                    <option value="Motocikls">Motocikli</option>
                    <option value="Ūdens transports">Ūdens transports</option>
                    <option value="Lauksaimniecības tehnika">Lauksaimniecības tehnika</option>
                </select>
            </div>
            <div class="col-sm-2" id="vehicleBrandSection" style="display: none">
                <label for="vehicleBrand">Marka</label>
                <select class="form-control form-control-sm" id="vehicleBrand" name="vehicleBrand">
                    <option style="display:none">Marka</option>
                </select>
            </div>
            <div class="col-sm-2" id="petTypeSection" style="display: none;">
                    <label for="petType">Dzīvnieka tips</label>
                    <select class="form-control form-control-sm" id="petType" name="petType">
                        <option style="display:none">Izvēlies</option>
                        <option value="Suns">Suņi</option>
                        <option value="Kaķis">Kaķi</option>
                        <option value="Grauzējs">Grauzēji</option>
                        <option value="Zivs">Zivtiņas</option>
                        <option value="Putns">Putni</option>
                        <option value="Lauksaimniecības dzīvnieks">Lauksaimniecības dzīvnieki</option>
                    </select>
                </div>

                <div class="col-sm-2" id="petBrandSection" style="display: none;">
                    <label for="petBrand">Škirne</label>
                    <select class="form-control form-control-sm" id="petBrand" name="petBrand">
                        <option style="display:none">Šķirne</option>
                    </select>
                </div>

                <div class="col-sm-2" id="electronicTypeSection" style="display: none;">
                    <label for="electronicType">Eletronikas tips</label>
                    <select class="form-control form-control-sm" id="electronicType" name="electronicType">
                        <option style="display:none">Izvēlies</option>
                        <option value="Sakaru līdzeklis">Sakaru līdzekļi</option>
                        <option value="Sadzīves tehnika">Sadzīves tehnika</option>
                        <option value="Dators">Datori</option>
                        <option value="Audio tehnika">Audio tehnika</option>
                        <option value="Video tehnika">Video tehnika</option>
                        <option value="Televizors">Televizori</option>
                    </select>
                </div>

                <div class="col-sm-2" id="electronicBrandSection" style="display: none;">
                    <label for="electronicBrand">Veids</label>
                    <select class="form-control form-control-sm" id="electronicBrand" name="electronicBrand">
                        <option style="display:none">Veids</option>
                    </select>
                </div>

                <div class="col-sm-2" id="JobTypeSection" style="display: none;">
                    <label for="JobType">Darbs un bizness</label>
                    <select class="form-control form-control-sm" id="JobType" name="JobType">
                        <option style="display:none">Izvēlies</option>
                        <option value="Vakance">Vakances</option>
                        <option value="Kursi">Kursi</option>
                        <option value="Darbs un bizness">Meklē darbu</option>
                    </select>
                </div>

                <div class="col-sm-2" id="JobBrandSection" style="display: none;">
                    <label for="JobBrand">Veids</label>
                    <select class="form-control form-control-sm" id="JobBrand" name="JobBrand">
                        <option style="display:none">Izvēlies</option>
                    </select>
                </div>

                <div class="col-sm-2" id="FurnitureTypeSection" style="display: none;">
                    <label for="FurnitureType">Mājai</label>
                    <select class="form-control form-control-sm" id="FurnitureType" name="FurnitureType">
                        <option style="display:none">Izvēlies</option>
                        <option value="Mājai, dārzam">Mēbeles, interjers</option>
                        <option value="Gleznas">Gleznas</option>
                        <option value="Augi">Augi</option>
                    </select>
                </div>

                <div class="col-sm-2" id="FurnitureBrandSection" style="display: none;">
                    <label for="FurnitureBrand">Veids</label>
                    <select class="form-control form-control-sm" id="FurnitureBrand" name="FurnitureBrand">
                        <option style="display:none">Izvēlies</option>
                    </select>
                </div>

                <div class="col-sm-2" id="otherTypeSection" style="display: none;">
                    <label for="otherType">Cits</label>
                    <input class="form-control form-control-sm" id="otherBrand" name="othersBrand"></input>
                </div>

                <div class="col-sm-2">
                <label for="Search"></label>
                <button type="submit" class="btn btn-primary btn-sm form-control">Filtrēt</button>
            </div>
        </div>
    </form>
</div>

<div class="container mt-4 mb-5 pb-5">
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
    $minPrice = isset($_GET['priceMin']) ? $_GET['priceMin'] : "";
    $maxPrice = isset($_GET['priceMax']) ? $_GET['priceMax'] : "";

    $location = isset($_GET['location']) ? $_GET['location'] : "";

    $adType = isset($_GET['adType']) ? $_GET['adType'] : "";

    switch ($adType) {
        case "Transports":
            $var1 = isset($_GET['vehicleType']) ? $_GET['vehicleType'] : "";
            $var2 = isset($_GET['vehicleBrand']) ? $_GET['vehicleBrand'] : "";
            break;
        case "Dzīvnieki":
            $var1 = isset($_GET['petType']) ? $_GET['petType'] : "";
            $var2 = isset($_GET['petBrand']) ? $_GET['petBrand'] : "";
            break;
        case "Elektronika":
            $var1 = isset($_GET['electronicType']) ? $_GET['electronicType'] : "";
            $var2 = isset($_GET['electronicBrand']) ? $_GET['electronicBrand'] : "";
            break;
        case "Darbs un bizness":
            $var1 = isset($_GET['JobType']) ? $_GET['JobType'] : "";
            $var2 = isset($_GET['JobBrand']) ? $_GET['JobBrand'] : "";
            break;
        case "Mēbeles":
            $var1 = isset($_GET['FurnitureType']) ? $_GET['FurnitureType'] : "";
            $var2 = isset($_GET['FurnitureBrand']) ? $_GET['FurnitureBrand'] : "";
            break;
        case "Cits":
            $var1 = isset($_GET['otherType']) ? $_GET['otherType'] : "";
            $var2 = isset($_GET['otherBrand']) ? $_GET['otherBrand'] : "";
            break;
        default:
            break;
    }
    try {
        $query = "SELECT ads.adId, ads.adName, ads.adType, ads.adLocation, ads.adPrice, ads.created_at, MIN(ad_images.image_path) as image_path 
                    FROM ads 
                    LEFT JOIN ad_images ON ads.adId = ad_images.ad_id";
    
        $query .= " LEFT JOIN vehicles ON ads.adId = vehicles.adId";
        $query .= " LEFT JOIN pets ON ads.adId = pets.adId";
        $query .= " LEFT JOIN electronics ON ads.adId = electronics.adId";
        $query .= " LEFT JOIN jobs ON ads.adId = jobs.adId";
        $query .= " LEFT JOIN furniture ON ads.adId = furniture.adId";
        $query .= " LEFT JOIN others ON ads.adId = others.adId";
    
        $query .= " WHERE ads.status = 1";
    
        if (!empty($minPrice)) {
            $query .= " AND ads.adPrice >= $minPrice";
        }
    
        if (!empty($maxPrice)) {
            $query .= " AND ads.adPrice <= $maxPrice";
        }
    
        if (!empty($location) && $location != 'None') {
            $query .= " AND ads.adLocation = '$location'";
        }
    
        if (!empty($adType)) {
            switch ($adType) {
                case "Elektronika":
                    if (!empty($var1)) {
                        $query .= " AND electronics.electronicType = '$var1'";
                    }
                    if (!empty($var2)) {
                        $query .= " AND electronics.electronicBrand = '$var2'";
                    }
                    break;
                case "Mēbeles":
                    if (!empty($var1)) {
                        $query .= " AND furniture.furnitureType = '$var1'";
                    }
                    if (!empty($var2)) {
                        $query .= " AND furniture.furnitureBrand = '$var2'";
                    }
                    break;
                case "Darbs un bizness":
                    if (!empty($var1)) {
                        $query .= " AND jobs.jobType = '$var1'";
                    }
                    if (!empty($var2)) {
                        $query .= " AND jobs.jobBrand = '$var2'";
                    }
                    break;
                case "Cits":
                    if (!empty($var1)) {
                        $query .= " AND others.othersBrand = '$var1'";
                    }
                    break;
                case "Dzīvnieki":
                    if (!empty($var1)) {
                        $query .= " AND pets.petType = '$var1'";
                    }
                    if (!empty($var2)) {
                        $query .= " AND pets.petBrand = '$var2'";
                    }
                    break;
                case "Transports":
                    if (!empty($var1)) {
                        $query .= " AND vehicles.vehicleType = '$var1'";
                    }
                    if (!empty($var2)) {
                        $query .= " AND vehicles.vehicleBrand = '$var2'";
                    }
                    break;
                default:
                    break;
            }
        }
    
        $query .= " GROUP BY ads.adId";
    
        $stmt = $pdo->query($query);
    
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {?>
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
            echo "<h1 class='text-dark text-center'>Nekas netika atrasts!</h1>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if(empty($_GET)) {
    try {
        $query = "SELECT ads.adId, ads.adName, ads.adType, ads.adLocation, ads.adPrice, ads.created_at, MIN(ad_images.image_path) as image_path 
                    FROM ads 
                    LEFT JOIN ad_images ON ads.adId = ad_images.ad_id
                    WHERE ads.status = 1";
        $query .= " GROUP BY ads.adId";
        
        $stmt = $pdo->query($query);

        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {?>
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
            echo "<h1 class='text-dark text-center'>Nekas netika atrasts!</h1>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
</div>
</div>
<footer class="row bg-dark p-sm-5 p-1 text-white mt-5 m-0 static-bottom">
    <div class="col-md-3">
        <div class="row">
            <div class="p-0">
                <img src="LogoBetter.png" class="w-100">
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
<script src="JS/type_of_ad.js"></script>
</body>
</html>
