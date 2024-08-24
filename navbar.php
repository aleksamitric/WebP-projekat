<?php
$currentPage = isset($currentPage) ? $currentPage : '';
?>

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
        <div>
            <h3 class="float-md-start mb-0">Pored Dunava <img src="imgs/logo.png" id="logo-img"></h3>
            <nav class="nav nav-masthead justify-content-center float-md-end">
                <a class="nav-link fw-bold py-1 px-0 <?php echo ($currentPage === 'home') ? 'active' : ''; ?>" href="index.php">Početna</a>
                <a class="nav-link fw-bold py-1 px-0 <?php echo ($currentPage === 'login') ? 'active' : ''; ?>" href="login.php">Prijavi se</a>
                <a class="nav-link fw-bold py-1 px-0 <?php echo ($currentPage === 'register') ? 'active' : ''; ?>" href="register.php">Registruj se</a>
            </nav>
        </div>
    </header>
</div>
