<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guest') {
    $_SESSION['role'] = 'guest';
}
require_once('db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prikupljanje podataka iz POST zahteva
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Priprema i izvršavanje SQL upita
    $stmt = $pdo->prepare('SELECT user_id, password, role FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Provera korisnika i lozinke
    if ($user && password_verify($password, $user['password'])) {
        // Postavljanje sesijskih promenljivih
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];

        //na osnovu role-a (admin ili user) se određuje sledeća stranica
        if ($user['role'] === 'admin') {
            error_log("Korisnik je admin. Preusmeravanje na admin.php.");
            echo json_encode(['status' => 'success', 'redirect' => 'admin.php']);
        } else {
            error_log("Korisnik je regularan korisnik. Preusmeravanje na profile.php.");
            echo json_encode(['status' => 'success', 'redirect' => 'profile.php']);
        }
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pogrešan email ili lozinka.']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <?php $currentPage = 'login'; ?>
    <?php include 'navbar.php'; ?>

    <div class="d-flex align-items-center py-4" id="login-body">
        <main class="form-signin w-100 m-auto">
            <form id="login-form" class="card p-4">
                <h1 class="h3 mb-3 fw-normal">Prijava</h1>

                <div id="error-message" class="alert alert-danger d-none" role="alert"></div>

                <div class="form-floating">
                    <input type="email" name="email" class="form-control" id="floatingInput"
                        placeholder="name@example.com" required>
                    <label for="floatingInput">Email adresa</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control" id="floatingPassword"
                        placeholder="Password" required>
                    <label for="floatingPassword">Lozinka</label>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Prijavi se</button>
                <a href="#" class="my-3 text-body-secondary" id="forgot-password-link">Zaboravljena lozinka?</a>
            </form>
        </main>
    </div>

    <script src="login_validate.js"></script>
    <script src="password_recovery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>