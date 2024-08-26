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

$stmt = $pdo->prepare('SELECT first_name, last_name, email, phone FROM users WHERE user_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();


$stmt = $pdo->prepare('SELECT position FROM tables');
$stmt->execute();
$table_positions = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

$stmt = $pdo->prepare('SELECT position, seats, name FROM tables');
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

$table_positions = array_column($tables, 'position');
$tablesAssoc = array_column($tables, null, 'position');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php
$currentPage = 'admin'; 
include 'navbar.php';
?>

    <script>
    function selectSquare(id) {
        const items = document.querySelectorAll('.grid-item');
        items.forEach(item => item.classList.remove('selected'));

        const selectedItem = document.getElementById(id);
        selectedItem.classList.add('selected');

        document.getElementById('radio' + id).checked = true;
    }
    </script>

    <div id="admin-body" class="d-flex col justify-content-center align-items-center">
        <form action="process.php" method="POST">
            <div class="grid-container">
                <?php
                for ($i = 1; $i <= 64; $i++) {
                    $disabledClass = in_array($i, $table_positions) ? 'disabled-radio' : '';
                    $disabledAttribute = in_array($i, $table_positions) ? 'disabled' : '';
                    $disabledOnClick = in_array($i, $table_positions) ? "console.log('disabled')" : 'selectSquare(\'place'.$i.'\')';
                    
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
                <input type="number" class="form-control" id="numberInput" placeholder="Upiši broj stolica" name="seats"
                    required>
                <input type="text" class="form-control my-3" id="textInput" placeholder="Upiši naziv" name="name"
                    required>

                <button type="submit" class="btn btn-outline-success">Potvrdi</button>
            </div>
        </form>
        <form action="delete_table.php" method="POST" class="mt-3">
            <div class="card p-4">
                <input type="number" class="form-control my-3" id="tableIdInput" placeholder="Upiši redni broj stola"
                    name="table_id" required>
                <button type="submit" class="btn btn-outline-danger">Obriši</button>
            </div>
        </form>

    </div>

</body>

</html>