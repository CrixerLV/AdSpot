<?php
require "backend/db_con.php";

session_start();

if (!isset($_GET['token'])) {
    echo "Nederīga saite";
    exit;
}

$token = $_GET['token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($password) || strlen($password) < 8) {
        $error = "Parolei ir vismaz jāsastāv no 8 rakstzīmēm!";
    } elseif ($password !== $confirmPassword) {
        $error = "Paroles nesakrīt!";
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = :token AND expires_at > NOW()");
            $stmt->execute(['token' => $token]);
            $resetInfo = $stmt->fetch();

            if (!$resetInfo) {
                $error = "Nederīgs pieprasījums!";
            } else {
                $userId = $resetInfo['user_id'];
                $updateStmt = $pdo->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
                $updateStmt->execute(['password' => $hashedPassword, 'user_id' => $userId]);

                $deleteStmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
                $deleteStmt->execute(['token' => $token]);

                echo "Parole veiksmīgi nomainīta!";
                exit;
            }
        } catch (PDOException $e) {
            $error = "Database error. Please try again later.";
            error_log('Database Error: ' . $e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Paroles atjaunošana</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="icon" type="image" href="favico.png">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div id="password-reset-form" class="card border-0 rounded-4 shadow">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="text-center mb-4">
                            <img src="Logo.png" class="img-fluid mb-3" style="max-width: 200px;" alt="AdSpot Logo">
                            <h3>Paroles atjaunošana</h3>
                        </div>

                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-floating mb-3">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Jaunā Parole" required>
                                <label for="password">Jaunā Parole</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Jaunā Parole atkārtoti" required>
                                <label for="confirm_password">Jaunā Parole atkārtoti</label>
                            </div>

                            <div class="d-grid text-center">
                                <button class="btn btn-primary btn-sm w-50 mx-auto mb-3" type="submit">Atjaunot paroli</button>
                                <a href="index.php">Doties uz autorizācijas lapu</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
