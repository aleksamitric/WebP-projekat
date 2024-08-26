<?php
$currentPage = isset($currentPage) ? $currentPage : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0">Pored Dunava <img src="imgs/logo.png" id="logo-img"></h3>
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    <?php 
                        if ($_SESSION['role'] === "guest") {
                            echo '<a class="nav-link fw-bold py-1 px-0 ' . ($currentPage === 'home' ? 'active' : '') . '" href="index.php">Početna</a>';
                            echo '<a class="nav-link fw-bold py-1 px-0 ' . ($currentPage === 'login' ? 'active' : '') . '" href="login.php">Prijavi se</a>';
                            echo '<a class="nav-link fw-bold py-1 px-0 ' . ($currentPage === 'register' ? 'active' : '') . '" href="register.php">Registruj se</a>';
                        } elseif($_SESSION['role'] === "user"){
                            echo '<a class="nav-link fw-bold py-1 px-0 ' . ($currentPage === 'home' ? 'active' : '') . '" href="index.php">Početna</a>';
                            echo '<a class="nav-link fw-bold py-1 px-0 ' . ($currentPage === 'profile' ? 'active' : '') . '" href="profile.php">Moj Profil</a>';
                            echo '<a class="nav-link fw-bold py-1 px-0" href="logout.php">Odjavi se</a>';
                        }elseif($_SESSION['role'] === "admin"){
                            echo '<a class="nav-link fw-bold py-1 px-0 ' . ($currentPage === 'admin' ? 'active' : '') . '" href="admin.php">Kreiranje stolova</a>';
                            echo '<a class="nav-link fw-bold py-1 px-0 ' . ($currentPage === 'reservations_view' ? 'active' : '') . '" href="reservations_view.php">Pregled rezervacija</a>';
                            echo '<a class="nav-link fw-bold py-1 px-0" href="logout.php">Odjavi se</a>';
                        } else {}
                    ?>
                </nav>
            </div>
            
            
            <div id="popup-div">
            <?php
            $linkUrl = '#';
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] === 'guest') {
                    $linkUrl = 'login.php';
                } elseif ($_SESSION['role'] === 'user') {
                    $linkUrl = 'reservation.php';
                }
            }
            ?>
                <span class="material-symbols-outlined"> close </span>
                <a href="<?php echo htmlspecialchars($linkUrl); ?>">Rezerviši sto!</a>
                <script src="popup.js"></script>
            </div>
        </header>
    </div>
    <script>
    var currentPage = '<?php echo $currentPage; ?>';
    </script>

</body>
</html>
