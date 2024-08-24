<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_config.php';

$email = $_GET['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'invalid_email';
    exit;
}

try {
    if (!isset($pdo)) {
        echo 'PDO object not set';
        exit;
    }
    
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo 'exists';
    } else {
        echo 'not_exists';
    }
} catch (\PDOException $e) {
    echo 'error';
    error_log('PDO Exception: ' . $e->getMessage());
}
?>
