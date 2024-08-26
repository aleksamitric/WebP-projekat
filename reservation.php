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

$stmt = $pdo->prepare('SELECT position FROM tables');
$stmt->execute();
$table_positions = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

$stmt = $pdo->prepare('SELECT position, seats, name FROM tables');
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

$table_positions = array_column($tables, 'position'); 
$tablesAssoc = array_column($tables, null, 'position');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_number = isset($_POST['table_number']) ? intval($_POST['table_number']) : 0;
    $reservation_date = isset($_POST['reservation_date']) ? $_POST['reservation_date'] : '';
    $arrival_time = isset($_POST['arrival_time']) ? $_POST['arrival_time'] : '';
    $departure_time = isset($_POST['departure_time']) ? $_POST['departure_time'] : '';
    $note = isset($_POST['note']) ? $_POST['note'] : '';

    if (!in_array($table_number, $table_positions)) {
        echo '<script>alert("Izabrani sto nije dostupan.");</script>';
    } else {
        // Provera konflikta rezervacija
        $stmt = $pdo->prepare('
            SELECT COUNT(*) FROM reservations 
            WHERE table_id = ? 
            AND date = ? 
            AND ((start_time < ? AND end_time > ?) OR (start_time < ? AND end_time > ?))
        ');
        $stmt->execute([$table_number, $reservation_date, $departure_time, $arrival_time, $arrival_time, $arrival_time]);
        $conflict = $stmt->fetchColumn();

        if ($conflict > 0) {
            echo '<script>alert("Izabrani sto je već rezervisan u tom vremenskom periodu.");</script>';
        } else {
            // Unos nove rezervacije ako nema konflikta
            $stmt = $pdo->prepare('INSERT INTO reservations (user_id, date, start_time, end_time, table_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$user_id, $reservation_date, $arrival_time, $departure_time, $table_number]);

            echo '<script>alert("Rezervacija uspešna!");</script>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reservation.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php
    $currentPage = 'reservation'; 
    include 'navbar.php';
    ?>

    <div id="reservation-body" class="d-flex col justify-content-center align-items-center">
        <form action="reservation_saving.php" method="POST" id="reservationForm">
            <div class="grid-container">
                <?php
                for ($i = 1; $i <= 64; $i++) {
                    $disabledClass = !in_array($i, $table_positions) ? 'disabled-radio' : '';
                    $disabledAttribute = !in_array($i, $table_positions) ? 'disabled' : '';
                    $disabledOnClick = !in_array($i, $table_positions) ? "console.log('disabled')" : 'selectSquare(\'place'.$i.'\')';
                    
                    if (isset($tablesAssoc[$i])) {
                        $table = $tablesAssoc[$i];
                        $name = htmlspecialchars($table['name']);
                        $seats = intval($table['seats']);
                    } else {
                        $name = '';
                        $seats = '';
                    }

                    echo '<div id="place'.$i.'" class="grid-item '.$disabledClass.'" onclick="'. $disabledOnClick . '">';
                    echo '<p style="font-size: 10px; position: absolute; top: 0; left: 0;">'.$i.'.</p>';
                    echo '<p style="font-size: 10px">'.$name.'</p>';
                    if (isset($tablesAssoc[$i])){
                        echo '<p style="font-size: 10px; position: absolute; bottom: 0px; right: 0px;">('.$seats.')</p>';
                    }
                    echo '<input type="radio" name="selected_place" value="'.$i.'" id="radioplace'.$i.'" class="hidden-radio" '.$disabledAttribute.'>';
                    echo '</div>';
                }
                ?>
            </div>
            <br>


            <div class="card p-4">
                <div class="mb-3">
                    <label for="reservationDate" class="form-label">Datum</label>
                    <input type="date" class="form-control" id="reservationDate" name="reservation_date" required>
                </div>
                <div class="mb-3">
                    <label for="arrivalTime" class="form-label">Vreme dolaska</label>
                    <input type="time" class="form-control" id="arrivalTime" name="arrival_time" required>
                </div>
                <div class="mb-3">
                    <label for="departureTime" class="form-label">Vreme odlaska</label>
                    <input type="time" class="form-control" id="departureTime" name="departure_time" required>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Napomena (opciono)</label>
                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="tableNumber" class="form-label">Redni broj stola</label>
                    <input type="number" class="form-control" id="tableNumber" name="table_number" min="1" max="64"
                        required>
                </div>
                <button type="submit" class="btn btn-outline-primary">Prosledi</button>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('reservationForm').addEventListener('submit', function(event) {
        const arrivalTime = document.getElementById('arrivalTime').value;
        const departureTime = document.getElementById('departureTime').value;

        //čuvanje i provera da li su vreme početka i kraja rezervacije minimum 6h
        if (arrivalTime && departureTime) {
            const arrival = new Date(`1970-01-01T${arrivalTime}:00`);
            const departure = new Date(`1970-01-01T${departureTime}:00`);
            const diffHours = (departure - arrival) / 1000 / 60 / 60;

            if (diffHours > 6) {
                alert('Vreme dolaska i vreme odlaska moraju imati razmak od najmanje 6 sati.');
                event.preventDefault();
            } else if (arrival > departure) {
                alert('Pogrešno uneto vreme dolaska i odlaska.');
                event.preventDefault();
            }
        }
    });

    function selectSquare(id) {
        // skidanje selekta za sve grid items
        const items = document.querySelectorAll('.grid-item');
        items.forEach(item => item.classList.remove('selected'));

        // Selektovanje kliknutih itema
        const selectedItem = document.getElementById(id);
        selectedItem.classList.add('selected');

        document.getElementById('radio' + id).checked = true;

        document.getElementById('tableNumber').value = id.substr(5);
    }
    </script>

</body>

</html>