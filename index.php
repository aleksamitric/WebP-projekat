<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
  $currentPage = 'home'; 
  include 'navbar.php';
  
  session_start();
  if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
  }
?>

<main>
  <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary text_div">
    <div class="col-md-6 p-lg-5 mx-auto my-5">
      <h1 class="display-3 fw-bold">restoran <span id="title">"Pored Dunava"</span></h1>
      <h3 class="fw-normal text-muted mb-3">Ukus tradicije uz zvuke Dunava</h3>
    </div>
  </div>

  <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary text_div">
    <div class="col-md-6 p-lg-5 mx-auto my-5">
      <h4 class="fw-normal text-muted mb-3">Restoran "Pored Dunava" nalazi se u prelepom ambijentu Novog Sada, uz samu obalu Dunava. Ovaj jedinstveni restoran pruža savršen spoj ukusne hrane, opuštajuće atmosfere i panoramskog pogleda na reku. Sa kapacitetom od 20 stolova i 60 udobnih sedišta, "Pored Dunava" je idealno mesto za uživanje u specijalitetima domaće kuhinje, bilo da tražite miran kutak za opuštanje ili želite da podelite poseban trenutak sa dragim osobama. Dođite i doživite autentično gastronomsko iskustvo uz zvuke Dunava u pozadini.</h3>
    </div>
    <div class="d-flex gap-3 justify-content-center lead fw-normal">
        <a class="icon-link" href="#">
          Learn more
          <svg class="bi"><use xlink:href="#chevron-right"/></svg>
        </a>
        <a class="icon-link" href="#">
          Buy
          <svg class="bi"><use xlink:href="#chevron-right"/></svg>
        </a>
      </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>