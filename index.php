<?php

#COOKIE REMEMBER ME CODE
session_start();
if (isset($_SESSION["id"])) {
    header("Location: dashboard.php");
    exit();
}
if (isset($_COOKIE['user_id']) && isset($_COOKIE['token'])) {
    require_once "./backend/db_con.php";

    $storedUserId = $_COOKIE['user_id'];
    $storedToken = $_COOKIE['token'];

    $stmt = $pdo->prepare("SELECT user_id, name, lastname, remember_token FROM users WHERE user_id = ?");
    $stmt->execute([$storedUserId]);
    $user = $stmt->fetch();

    if ($user && password_verify($storedToken, $user['remember_token'])) {
        $_SESSION['id'] = $user['user_id'];
        $_SESSION['lastname'] = htmlspecialchars($user["lastname"]);
        $_SESSION['name'] = htmlspecialchars($user["name"]);

        header("Location: dashboard.php");
        exit();
    }
}

#LOGIN LOGIC
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

          session_regenerate_id(true);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image" href="favico.png">

    <style>
      #externals:hover{
        transform: scale(1.1);
        transition: transform 0.1s ease-in-out;
        color: #0d6efd;
        cursor: pointer;
      }
    </style>
</head> 
<body class="bg-dark">
<section class="bg-primary py-3 py-md-5 py-xl-8" style="min-height:100vh;">
  <div class="container">
    <div class="row gy-4 align-items-center">
      <div class="col-12 col-md-6 col-xl-7">
        <div class="d-flex justify-content-center text-bg-primary">
          <div class="col-12 col-xl-9">
            <img class="img-fluid rounded mb-4" loading="lazy" src="LogoBetter.png" width="245" height="80" alt="">
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
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
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
            <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                          <a href="#!" data-toggle="modal" data-target="#resetPasswordModal">Aizmirsu paroli</a>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="resetPasswordModalLabel">Paroles atjaunošana</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <form id="resetPasswordForm" method="POST" action="backend/reset_password.php">
                              <div class="form-group">
                                  <label for="emailreset">E-pasts</label>
                                  <input type="email" class="form-control" id="emailreset" name="emailreset" required>
                              </div>
                              <button type="submit" class="btn btn-primary mt-2 mb-3">Atjaunot paroli</button>
                          </form>
                          <div id="resetPasswordMessage" class="mt-3"></div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#resetPasswordForm').submit(function(event) {
        event.preventDefault();

        $('#resetPasswordForm button[type="submit"]').prop('disabled', true);

        var formData = $(this).serialize();
        var resetPasswordMessage = $('#resetPasswordMessage');

        $.ajax({
            type: 'POST',
            url: 'backend/reset_password.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                resetPasswordMessage.empty();
                resetPasswordMessage.removeClass();
                
                if (response.status === 'success') {
                    resetPasswordMessage.addClass('alert alert-success');
                    resetPasswordMessage.text(response.message);
                } else {
                    resetPasswordMessage.addClass('alert alert-danger');
                    resetPasswordMessage.text(response.message);
                }

                $('#resetPasswordForm button[type="submit"]').prop('disabled', true);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                resetPasswordMessage.empty();
                resetPasswordMessage.addClass('alert alert-danger');
                resetPasswordMessage.text('Notika kļūda mēģiniet velreiz!');

                $('#resetPasswordForm button[type="submit"]').prop('disabled', false);
            }
        });
    });
});
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>