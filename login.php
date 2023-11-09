<?php
require "./backend/db_con.php";
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $password = $_POST["password"];

  if (!$email) {
      $loginError = "Invalid email format";
  } else {
      $sql = "SELECT user_id, email, password, name, lastname FROM users WHERE email = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if ($user && password_verify($password, $user["password"])) {

          $_SESSION["id"] = $user["user_id"];
          $_SESSION['lastname'] = htmlspecialchars($user["lastname"]);
          $_SESSION["name"] = htmlspecialchars($user["name"]);

          if (isset($_POST['remember_me'])) {

              $token = bin2hex(random_bytes(32));

              $hashedToken = password_hash($token, PASSWORD_DEFAULT);

              $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE user_id = ?");
              $stmt->execute([$hashedToken, $user['user_id']]);

              setcookie('user_id', $user['user_id'], time() + (30 * 24 * 60 * 60), '/', '', true, true);
              setcookie('token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
          }
          if (isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {
              $storedUserId = $_COOKIE['user_id'];
              $storedToken = $_COOKIE['token'];

              $stmt = $pdo->prepare("SELECT user_id, remember_token FROM users WHERE user_id = ?");
              $stmt->execute([$storedUserId]);
              $user = $stmt->fetch();

              if ($user && password_verify($storedToken, $user['remember_token'])) {
                  $_SESSION['id'] = $user['user_id'];
                  $_SESSION['lastname'] = htmlspecialchars($user["lastname"]);
                  $_SESSION["name"] = htmlspecialchars($user["name"]);
              }
          }

          header("Location: dashboard.php");
          exit();
      } else {
          $loginError = "Nederīgs e-pasts un vai parole!";
      }
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Login</title>
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
                  <h3>Autorizācija</h3>
                  <p>Nēesi izveidojis profilu? <a href="register.php">Reģistrēties</a></p>
                  <h3 style="color:red; font-size: 16px;"><?php echo $loginError; ?></h3>
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
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="remember_me" id="remember_me">
                    <label class="form-check-label text-secondary" for="remember_me">
                      Atcerēties mani
                    </label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn btn-primary btn-lg" type="submit">Ieiet</button>
                  </div>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-12">
                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                  <a href="#!">Aizmirsu paroli</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>