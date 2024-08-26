<?php
$host = 'localhost';
// $dbname = 'restaurant';
// $dbuser = 'root';
// $dbpass = 'root'; 
$dbname = 'blok5';
$dbuser = 'blok5';
$dbpass = 'UE0ui26cM58OqEv'; 

// Postavljanje DSN za PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $dbuser, $dbpass, $options);
} catch (\PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>