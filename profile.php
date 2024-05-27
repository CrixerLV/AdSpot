<?php
require "backend/db_con.php";
include("backend/authorization.php");

$successMessage = '';

try {
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $userEmail = $user['email'];
    $userPhone = $user['phone'];
    $userAdress = $user['adress'];
    $userDob = $user['dob'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            if (isset($_POST['userEmail']) && isset($_POST['userPhone'])) {
                $newEmail = $_POST['userEmail'];
                $newPhone = $_POST['userPhone'];
    
                $updateSql = "UPDATE users SET email = :email, phone = :phone WHERE user_id = :user_id";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
                $updateStmt->bindParam(':phone', $newPhone, PDO::PARAM_STR);
                $updateStmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $updateStmt->execute();
    
                $_SESSION['email'] = $newEmail;
    
                $successMessage = "Labojumi veiksmīgi!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['userAddress']) && isset($_POST['userDob'])) {
            $newAdress = $_POST['userAddress'];
            $newDob = $_POST['userDob'];

            $updateSql = "UPDATE users SET adress = :adress, dob = :dob WHERE user_id = :user_id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':adress', $newAdress, PDO::PARAM_STR);
            $updateStmt->bindParam(':dob', $newDob, PDO::PARAM_STR);
            $updateStmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
            $updateStmt->execute();

            $successMessage = "Labojumi veiksmīgi!";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}




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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Profile</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image" href="favico.png">

    <style>
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

<div class="container-fluid mt-5" style="min-height: 100vh; margin-bottom: 80px;">
    <div class="row justify-content-center mt-5 mb-5 pb-5">
        <div class="col-md-3 bg-white m-2 p-4">
            <div class="text-center">
                    <img src="<?php echo $imagePath; ?>" alt="User Image" id="profileImage" class="img-fluid" data-bs-toggle="modal" data-bs-target="#imageModal">
                <hr>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <h5 class="text-start">Mainīt profila bildi</h5>
                <form id="uploadForm" action="backend/user_image_upload.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="file" class="form-control rounded-0" id="userImage" name="userImage">
                        <input type="submit" class="form-control btn btn-outline-dark mt-3 w-50 rounded-0" value="Mainīt">
                    </div>
                </form>
            </div>
        </div>


        <div class="col-md-5 bg-white m-2 p-4">
            <h1 class="fw-bold">Mans profils</h1>
            <h3 class="mt-1 text-secondary"><?php echo $_SESSION['name'] . ' ' . $_SESSION['lastname']; ?></h3>
            <hr>
            <div class="d-sm-flex mb-3 w-50">
                <button class="btn btn-sm btn-outline-dark rounded-0 me-0 me-sm-2 p-1" onclick="showPrivateForm()">Privātā Informācija</button>
                <button class="btn btn-sm btn-outline-dark rounded-0 ms-0 ms-sm-2 p-1 mt-2 mt-sm-0" onclick="showPublicForm()">Publiskā Informācija</button>
            </div>
            <?php if (isset($successMessage) && $successMessage != "") : ?>
                <div class="alert alert-success mb-3">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            <form id="privateForm" method="post" action="" style="display: none;">
                <div class="mb-3">
                    <label for="address" class="form-label">Adrese</label>
                    <input type="text" class="form-control" id="address" name="userAddress" value="<?php echo $userAdress; ?>" required>
                    <label for="dob">Dzimšanas dati</label>
                    <input type="date" class="form-control" id="dob" name="userDob" value="<?php echo $userDob; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Saglabāt</button>
            </form>

            <form id="publicForm" method="post" action="" style="display: none;">
                <div class="mb-3">
                    <label for="userName" class="form-label">Vārds Uzvārds</label>
                    <input type="text-muted" readonly class="form-control" id="userName" name="userName" value="<?php echo $user['name'] . ' ' . $user['lastname']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="userEmail" class="form-label">E-pasts</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" value="<?php echo $userEmail; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Talr. Nr.</label>
                    <input type="text" class="form-control" id="phone" name="userPhone" value="<?php echo $userPhone; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Saglabāt</button>
            </form>
        </div>
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

<script src="https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<script>
    function showPrivateForm() {
        document.getElementById('privateForm').style.display = 'block';
        document.getElementById('publicForm').style.display = 'none';
    }

    function showPublicForm() {
        document.getElementById('privateForm').style.display = 'none';
        document.getElementById('publicForm').style.display = 'block';
    }
</script>
</body>
</html>
