<?php
session_start();
require_once('db_config.php');

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Dohvatimo sve rezervacije sa potrebnim informacijama
$query = '
    SELECT r.reservation_id, u.first_name, u.last_name, u.user_id, r.date, r.start_time, r.end_time, t.position
    FROM reservations r
    JOIN users u ON r.user_id = u.user_id
    JOIN tables t ON r.table_id = t.id
    ORDER BY r.date, r.start_time
';
$stmt = $pdo->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Pregled rezervacija</title>
</head>

<body>
    <?php
    $currentPage = 'reservations_view';
    include 'navbar.php';
    ?>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Pregled rezervacija</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>ID Korisnika</th>
                    <th>Datum</th>
                    <th>Početak</th>
                    <th>Kraj</th>
                    <th>Pozicija stola</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) > 0): ?>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['date']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['start_time']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['end_time']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['position']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Nema rezervacija</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.14.0/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9llxMhlY8nVLE4pN02Vt3YQ9Q7eKl1pG7a2O7HqfZ4dWvqGzM1Zl" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-6C1h+oclmLPdA9MRaxH9JhJWl8W2cIuMKhq5pu6Yw8A71LBj5ljtrL71FJ9Mbh6A" crossorigin="anonymous">
    </script>
</body>

</html>