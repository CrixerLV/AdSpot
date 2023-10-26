<?php
require "./backend/db_con.php";

$registrationError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordx2 = $_POST["passwordx2"];
    $name = $_POST["name"];
    $lastname = $_POST["lastname"];

    if (empty($email) || empty($password) || empty($passwordx2) || empty($name) || empty($lastname)) {
        $registrationError = "Lauciņi nedrīkst būt tukši!";
    } elseif ($password != $passwordx2) {
        $registrationError = "Paroles nesakrīt";
    } else {
        $sql = "SELECT user_id FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $registrationError = "E-pasts jau ir reģistrēts!";
        } else {
            $name = ucfirst($name);
            $lastname = ucfirst($lastname);

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (email, password, name, lastname) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$email, $hashedPassword, $name, $lastname])) {
                header("Location: login.php");
                exit();
            } else {
                $registrationError = "Reģistrācija neizdevās!";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Register</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.2/components/logins/login-9/assets/css/login-9.css" />

</head> 
<body class="bg-dark">
<section class="bg-primary py-3 py-md-5 py-xl-8">
  <div class="container">
    <div class="row gy-4 align-items-center">
      <div class="col-12 col-md-6 col-xl-7">
        <div class="d-flex justify-content-center text-bg-primary">
          <div class="col-12 col-xl-9">
            <img class="img-fluid rounded mb-4" loading="lazy" src="AdSpot.png" width="245" height="80" alt="">
            <hr class="border-primary-subtle mb-4">
            <h2 class="h1 mb-4">Sludinājumu portāls tieši tev.</h2>
            <p class="lead mb-5">Ievieto vai meklē sev tīkamo sludinājumu tieši šeit.</p>
            <div class="text-endx">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl-5">
        <div class="card border-0 rounded-4">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-4">
                  <h3>Reģistrācija</h3>
                  <p>Jau esi reģistrējies? <a href="login.php">Autorizēties</a></p>
                  <h3 style="color:red; font-size: 16px;"><?php echo $registrationError; ?></h3>
                </div>
              </div>
            </div>
            <form method="POST">
              <div class="row gy-3 overflow-hidden">
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="piemers@adspot.lv" required>
                    <label for="email" class="form-label">E-pasts</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                    <label for="password" class="form-label">Parole</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="passwordx2" id="passwordx2" value="" placeholder="Password" required>
                    <label for="passwordx2" class="form-label">Parole Atkārtoti</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="name" id="name" value="" placeholder="Name" required>
                    <label for="Name" class="form-label">Vārds</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="lastname" id="lastname" value="" placeholder="Lastname" required>
                    <label for="Lastname" class="form-label">Uzvārds</label>
                  </div>
                </div>
                <div class="col-12">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">Reģistrēties</button>
                    </div>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
