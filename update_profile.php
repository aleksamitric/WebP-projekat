<?php
session_start();
require_once('db_config.php');

if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$field = $_POST['field'];
$value = $_POST['value'];

$allowed_fields = ['first_name', 'last_name', 'email', 'phone'];

if (in_array($field, $allowed_fields)) {
    if ($field === 'phone') {
        // Provera da li drugi korisnik vec koristi broj telefona
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = :value AND user_id != :user_id");
        $stmt->execute(['value' => $value, 'user_id' => $user_id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(['error' => 'Broj je neispravno unet ga vec koristi drugi korisnik']);
            exit();
        }
    }

    // Nova vrednost se upisuje u bazu
    $stmt = $pdo->prepare("UPDATE users SET $field = :value WHERE user_id = :user_id");
    $stmt->execute(['value' => $value, 'user_id' => $user_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Nevažeće polje']);
}
?>