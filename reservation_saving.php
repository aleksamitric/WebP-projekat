<?php
session_start();
require_once('db_config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Proveri da li je metoda POST korišćena
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preuzimanje podataka iz forme
    $table_number = isset($_POST['table_number']) ? intval($_POST['table_number']) : 0;
    $stmt = $pdo->prepare('SELECT * FROM tables WHERE position = ?');
        $stmt->execute([$table_number]);
        $table = $stmt->fetch(PDO::FETCH_ASSOC);
    $reservation_date = isset($_POST['reservation_date']) ? $_POST['reservation_date'] : '';
    $arrival_time = isset($_POST['arrival_time']) ? $_POST['arrival_time'] : '';
    $departure_time = isset($_POST['departure_time']) ? $_POST['departure_time'] : '';
    $note = isset($_POST['note']) ? $_POST['note'] : '';

    if ($table_number && $reservation_date && $arrival_time && $departure_time) {
        
        // Proveri da li je sto dostupan
        if ($table) {
            $stmt = $pdo->prepare('INSERT INTO reservations (user_id, date, start_time, end_time, table_id, note) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$user_id, $reservation_date, $arrival_time, $departure_time, $table['id'], $note]);

            // Uspešno kreirana rezervacija
            echo '<script>alert("Rezervacija uspešna!"); window.location.href = "reservation.php";</script>';
        } else {
            // Sto nije dostupan
            echo '<script>alert("Izabrani sto nije dostupan."); window.location.href = "reservation.php";</script>';
        }
    } else {
        // Nedostaju obavezni podaci
        echo '<script>alert("Molimo popunite sva obavezna polja."); window.location.href = "reservation.php";</script>';
    }
} else {
    // Ako nije POST zahtev
    header("Location: reservation.php");
    exit();
}
?>