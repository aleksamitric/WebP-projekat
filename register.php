<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
</head>
<body>
    
<?php $currentPage = 'register'; ?>
<?php include 'navbar.php'; ?>
<?php
session_start();
  if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
  }
?>
<div class="d-flex align-items-center py-4" id="login-body">
<main class="form-signin w-100 m-auto">
  <form class="card p-4">
    <h1 class="h3 mb-3 fw-normal">Registracija</h1>

    <div class="form-floating">
  <input class="form-control" id="first_name" placeholder="a">
  <label for="floatingInput">Ime</label>
  <div id="first_name_error" class="text-danger"></div>
</div>
<div class="form-floating">
  <input class="form-control" id="last_name" placeholder="a">
  <label for="floatingInput">Prezime</label>
  <div id="last_name_error" class="text-danger"></div>
</div>
<div class="form-floating">
  <input class="form-control" id="phone" placeholder="a">
  <label for="floatingInput">Broj telefona</label>
  <div id="phone_error" class="text-danger"></div>
</div>
<div class="form-floating">
  <input class="form-control" id="email" placeholder="a">
  <label for="floatingInput">Email adresa</label>
  <div id="email_error" class="text-danger"></div>
</div>
<div class="form-floating">
  <input type="password" class="form-control" id="password" placeholder="a">
  <label for="floatingPassword">Lozinka</label>
  <div id="password_error" class="text-danger"></div>
</div>
<div class="form-floating mb-4">
  <input type="password" class="form-control" id="password_confirmation" placeholder="a">
  <label for="floatingPassword">Potvrdi lozinku</label>
  <div id="password_confirmation_error" class="text-danger"></div>
</div>

    <button class="btn btn-primary w-100 py-2" type="submit">Registruj se</button>
  </form>
</main>
</div>
<script src="register_validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>