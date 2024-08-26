<?php
session_start();
require_once('db_config.php');

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT first_name, last_name, email, phone FROM users WHERE user_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

$reservationsStmt = $pdo->prepare('
    SELECT r.*, t.position 
    FROM reservations r
    JOIN tables t ON r.table_id = t.id 
    WHERE r.user_id = :user_id 
    AND r.end_time > NOW()
');
$reservationsStmt->execute(['user_id' => $user_id]);
$reservations = $reservationsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Korisnički profil</title>
</head>

<body>
    <?php
    $currentPage = 'profile';
    include 'navbar.php';
    ?>
    <div id="profile-body">
        <div class="d-flex flex-column flex-lg-row w-100 min-width-full align-items-center justify-content-around">
            <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-start">
                <div class="list-group">
                    <div class="gap-2 w-100 py-1">
                        <p class="text-center custom-css">Podaci o korisniku</p>
                    </div>

                    <!-- Ime -->
                    <div href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h6 class="mb-0">Ime</h6>
                                <p class="mb-0 opacity-75" id="first_name_display">
                                    <?php echo htmlspecialchars($user['first_name']); ?></p>
                                <div id="first_name_edit" class="edit-section" style="display: none;">
                                    <input type="text" id="first_name_input"
                                        value="<?php echo htmlspecialchars($user['first_name']); ?>">
                                </div>
                            </div>
                            <a href="#" aria-current="true" onclick="toggleEdit('first_name')"><small
                                    class="opacity-50 text-nowrap"><span
                                        class="material-symbols-outlined">edit</span></small>
                            </a>
                        </div>
                    </div>

                    <!-- Prezime -->
                    <div href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h6 class="mb-0">Prezime</h6>
                                <p class="mb-0 opacity-75" id="last_name_display">
                                    <?php echo htmlspecialchars($user['last_name']); ?></p>
                                <div id="last_name_edit" class="edit-section" style="display: none;">
                                    <input type="text" id="last_name_input"
                                        value="<?php echo htmlspecialchars($user['last_name']); ?>">
                                </div>
                            </div>
                            <a href="#" aria-current="true" onclick="toggleEdit('last_name')"><small
                                    class="opacity-50 text-nowrap"><span
                                        class="material-symbols-outlined">edit</span></small>
                            </a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true" ">
                        <div class=" d-flex gap-2 w-100 justify-content-between">
                        <div>
                            <h6 class="mb-0">Email</h6>
                            <p class="mb-0 opacity-75" id="email_display">
                                <?php echo htmlspecialchars($user['email']); ?></p>
                            <div id="email_edit" class="edit-section" style="display: none;">
                                <input type="email" id="email_input"
                                    value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Telefon -->
                <div href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true" ">
                        <div class=" d-flex gap-2 w-100 justify-content-between">
                    <div>
                        <h6 class="mb-0">Telefon</h6>
                        <p class="mb-0 opacity-75" id="phone_display">
                            <?php echo htmlspecialchars($user['phone']); ?></p>
                        <div id="phone_edit" class="edit-section" style="display: none;">
                            <input type="number" id="phone_input"
                                value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                    </div>

                    <a href="#" aria-current="true" onclick="toggleEdit('phone')"><small
                            class="opacity-50 text-nowrap"><span class="material-symbols-outlined">edit</span></small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-end">
        <div class="list-group">
            <div class="gap-2 w-100 py-1">
                <p class="text-center custom-css">Moje rezervacije</p>
            </div>

            <?php if (count($reservations) > 0): ?>
            <?php foreach ($reservations as $reservation): ?>
            <?php
        $reservationDateTime = new DateTime($reservation['date'] . ' ' . $reservation['start_time']);
        $currentDateTime = new DateTime();

        $interval = $currentDateTime->diff($reservationDateTime);
        $hoursUntilReservation = ($interval->days * 24) + $interval->h + ($interval->i / 60);

        $isDisabled = ($hoursUntilReservation < 4);
        ?>
            <div class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                <div class="d-flex gap-2 w-100 justify-content-between">
                    <div>
                        <h6 class="mb-0">Rezervacija ID:
                            <?php echo htmlspecialchars($reservation['reservation_id']); ?>
                        </h6>
                        <p class="mb-0 opacity-75">Pozicija stola:
                            <?php echo htmlspecialchars($reservation['position']); ?></p>
                        <p class="mb-0 opacity-75">Datum:
                            <?php echo htmlspecialchars($reservation['date']); ?></p>
                        <p class="mb-0 opacity-75">Vreme početka:
                            <?php echo htmlspecialchars($reservation['start_time']); ?></p>
                        <p class="mb-0 opacity-75">Počinje za:
                            <?php echo htmlspecialchars(floor($hoursUntilReservation)); ?>h</p>
                        <p class="mb-0 opacity-75">Vreme kraja:
                            <?php echo htmlspecialchars($reservation['end_time']); ?></p>
                    </div>
                    <form method="post" action="delete_reservation.php"
                        onsubmit="return confirm('Da li ste sigurni da želite da obrišete ovu rezervaciju?');">
                        <input type="hidden" name="reservation_id"
                            value="<?php echo htmlspecialchars($reservation['reservation_id']); ?>">
                        <button type="submit" class="btn btn-danger"
                            <?php echo $isDisabled ? 'disabled' : ''; ?>>Obriši</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                <div class="d-flex gap-2 w-100 justify-content-between">
                    <div>
                        <h6 class="mb-0">Trenutno nemate aktivne rezervacije</h6>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
    </div>
    <div class="d-flex w-100 min-width-full justify-content-center" id="register-btn-div">
        <a href="reservation.php">Napravi rezervaciju</a>

    </div>
    </div>
    </div>
    <script src="js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.14.0/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9llxMhlY8nVLE4pN02Vt3YQ9Q7eKl1pG7a2O7HqfZ4dWvqGzM1Zl" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-6C1h+oclmLPdA9MRaxH9JhJWl8W2cIuMKhq5pu6Yw8A71LBj5ljtrL71FJ9Mbh6A" crossorigin="anonymous">
    </script>
    <script>
    function toggleEdit(field) {
        document.getElementById(field + '_display').style.display = document.getElementById(field + '_display').style
            .display === 'none' ? 'block' : 'none';
        document.getElementById(field + '_edit').style.display = document.getElementById(field + '_edit').style
            .display === 'none' ? 'block' : 'none';
    }
    </script>
</body>

</html>