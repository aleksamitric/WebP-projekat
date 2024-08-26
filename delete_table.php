<?php
session_start();
require_once('db_config.php');

// Proverite da li je korisnik admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Dobijanje ID-ja stola iz POST zahteva
if (isset($_POST['table_id'])) {
    $table_id = intval($_POST['table_id']);

    // Priprema SQL upit za brisanje
    $stmt = $pdo->prepare('DELETE FROM tables WHERE position = :position');
    $stmt->execute(['position' => $table_id]);

    // Povratak na admin stranicu nakon brisanja
    header("Location: admin.php");
    exit();
} else {
    // Ako nije poslat table_id, vratite se nazad sa greškom
    header("Location: admin.php?error=missing_id");
    exit();
}
?>