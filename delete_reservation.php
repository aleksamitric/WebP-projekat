<?php
session_start();
require_once('db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];

    if (isset($reservation_id)) {
        try {
            $stmt = $pdo->prepare('DELETE FROM reservations WHERE reservation_id = :id AND user_id = :user_id');
            $stmt->execute([
                'id' => $reservation_id,
                'user_id' => $_SESSION['user_id']
            ]);
            
            // Redirektuj korisnika na profil nakon brisanja
            header("Location: profile.php");
            exit();
        } catch (PDOException $e) {
            echo "Greška prilikom brisanja rezervacije: " . $e->getMessage();
        }
    }
} else {
    header("Location: profile.php");
    exit();
}
?>