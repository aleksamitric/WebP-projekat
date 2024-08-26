<?php
require 'db_config.php';

// Provera da li je forma poslata
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Provera da li je izabran ID i ostali podaci
    if (isset($_POST['selected_place']) && isset($_POST['seats']) && isset($_POST['name'])) {
        // Uzimanje izabranog IDja, broja stolice i naziv
        $selectedPlace = intval($_POST['selected_place']);
        $seats = intval($_POST['seats']);
        $name = htmlspecialchars($_POST['name']); // Očisti naziv od specijalnih karaktera

        try {
            // Provera da li već postoji sto na tom mestu
            $checkStmt = $pdo->prepare("SELECT * FROM tables WHERE position = :position");
            $checkStmt->execute(['position' => $selectedPlace]);
            $existingTable = $checkStmt->fetch();

            if ($existingTable) {
                // Ako sto već postoji, ažuriraj podatke
                $updateStmt = $pdo->prepare("UPDATE tables SET seats = :seats, name = :name WHERE position = :position");
                $updateStmt->execute([
                    'seats' => $seats,
                    'name' => $name,
                    'position' => $selectedPlace
                ]);

                echo "Sto na mestu " . $selectedPlace . " je uspešno ažuriran.";
            } else {
                // Dodavanje novog stola u bazu
                $sql = "INSERT INTO tables (position, seats, name) VALUES (:position, :seats, :name)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'position' => $selectedPlace,
                    'seats' => $seats,
                    'name' => $name
                ]);

                echo "Sto na mestu " . $selectedPlace . " je uspešno dodat u bazu podataka.";
                header("Location: admin.php");
            }
        } catch (PDOException $e) {
            echo "Greška: " . $e->getMessage();
        }
    } else {
        echo "Nisu svi potrebni podaci uneti.";
    }
} else {
    echo "Forma nije poslata.";
}
?>