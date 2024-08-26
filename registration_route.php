<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_config.php';

function generateActivationLink($length = 72) {
    return bin2hex(random_bytes($length / 2));
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit;
}

$firstName = trim($data['first_name']);
$lastName = trim($data['last_name']);
$phone = trim($data['phone']);
$email = trim($data['email']);
$password = trim($data['password']);

$errors = [];
if (empty($firstName) || empty($lastName) || empty($phone) || empty($email) || empty($password)) {
    $errors[] = 'Sva polja su obavezna.';
} elseif (!preg_match('/^[a-zA-Z]+$/', $firstName)) {
    $errors[] = 'Ime može sadržati samo slova.';
} elseif (!preg_match('/^[a-zA-Z]+$/', $lastName)) {
    $errors[] = 'Prezime može sadržati samo slova.';
} elseif (!preg_match('/^\d{1,10}$/', $phone)) {
    $errors[] = 'Broj telefona neispravno unet.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Nevažeća email adresa.';
} elseif (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password)) {
    $errors[] = 'Lozinka mora sadržati veliko slovo, malo slovo i broj.';
}

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'Email adresa već postoji.']);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$activationLink = generateActivationLink();

try {
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, phone, email, password, activational_link, password_reset, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $phone, $email, $hashedPassword, $activationLink, null, 1]);
    echo json_encode(['status' => 'success', 'message' => 'Registracija uspešna.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Došlo je do greške prilikom registracije: ' . $e->getMessage()]);
}
?>
