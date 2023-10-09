<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./Styles/login.css">
</head>
<body class="bg-dark">
    <div class="container vh-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-6">
                <div class="text-center">
                    <img src="AdSpot\Additional\AdSpot.png" class="mb-3" alt="AdSpot Logo">
                </div>
                <form class="bg-light p-4 rounded">
                    <div class="mb-3">
                        <label for="username" class="form-label">Lietotājvārds</label>
                        <input type="text" class="form-control" id="username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Parole</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a class="text-muted" href="#">Aizmirsu paroli</a>
                        <a href="register.php" class="text-primary">Vēlos reģistrēties</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-3">Autorizēties</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJo
