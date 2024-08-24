<?php
session_start();
require_once('db_config.php');

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT user_id, password FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['role'] = 'user';
        $_SESSION['user_id'] = $user['user_id'];

        echo json_encode(['status' => 'success', 'message' => 'Uspešna prijava.']);
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
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
      <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email adresa</label>
    </div>
    <div class="form-floating mb-4">
      <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
      <label for="floatingPassword">Lozinka</label>
    </div>

    <button class="btn btn-primary w-100 py-2" type="submit">Prijavi se</button>
    <a href="#" class="my-3 text-body-secondary" id="forgot-password-link">Zaboravljena lozinka?</a>
  </form>
</main>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        const errorMessageDiv = document.getElementById('error-message');
        
        if (result.status === 'error') {
            errorMessageDiv.textContent = result.message;
            errorMessageDiv.classList.remove('d-none');
        } else {
            window.location.href = 'profile.php';
        }
    })
    .catch(error => {
        console.error('Došlo je do greške:', error);
    });
});

document.getElementById('floatingInput').addEventListener('input', function() {
    document.getElementById('error-message').classList.add('d-none');
});
document.getElementById('floatingPassword').addEventListener('input', function() {
    document.getElementById('error-message').classList.add('d-none');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
