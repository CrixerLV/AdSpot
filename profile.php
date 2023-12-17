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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['userEmail']) && isset($_POST['phone'])) {
            $newEmail = $_POST['userEmail'];
            $newPhone = $_POST['phone'];

            $updateSql = "UPDATE users SET email = :email, phone = :phone WHERE user_id = :user_id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
            $updateStmt->bindParam(':phone', $newPhone, PDO::PARAM_STR);
            $updateStmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
            $updateStmt->execute();

            $_SESSION['email'] = $newEmail;

            $successMessage = "Labojumi veiksmīgi!";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Profile</title>
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

<div class="container-sm mt-4">
    <div class="row">
        <div class="col-md bg-white rounded m-2 p-4">
            <div class="text-center">
                <img src="Logo.png" alt="User Image" class="img-fluid" style="max-width: 200px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal">
                <h5 class="mt-3"><?php echo $_SESSION['name'] . ' ' . $_SESSION['lastname']; ?></h5>
            </div>
        </div>

        <div class="col-md bg-white rounded m-2 p-4">
            <h5>Labot profilu</h5>
            <?php if (isset($successMessage) && $successMessage != "") : ?>
                <div class="alert alert-success mb-3">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            <form id="profileForm" method="post" action="">
                <div class="mb-3">
                    <label for="userEmail" class="form-label">E-pasts</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" value="<?php echo $userEmail; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Talr. Nr.</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $userPhone; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Saglabāt</button>
            </form>
        </div>
    </div>
</div>
<script src="https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
