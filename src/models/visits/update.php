<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$role = $_GET['role'] ?? 'admin'; // Default ke 'admin' jika parameter role tidak ada

$visit = $conn->query("SELECT * FROM visit WHERE visit_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visit_notes = $_POST['visit_notes'];
    $animal_id = $_POST['animal_id'];
    $vet_id = $_POST['vet_id'];
    $from_visit_id = $_POST['from_visit_id'] ?? 'NULL'; // Jika tidak ada kunjungan sebelumnya, set NULL

    $sql = "UPDATE visit 
            SET visit_notes = '$visit_notes', 
                animal_id = $animal_id, 
                vet_id = $vet_id, 
                from_visit_id = $from_visit_id 
            WHERE visit_id = $id";
    if ($conn->query($sql) === TRUE) {
        // Redirect berdasarkan role
        if ($role === 'vet') {
            header("Location: ../../pages/dashboard_vet.php");
        } else {
            header("Location: ../../models/visits/index.php");
        }
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data hewan
$animals = $conn->query("SELECT animal_id, animal_name FROM animal");

// Ambil data dokter
$vets = $conn->query("SELECT vet_id, vet_givenname, vet_familyname FROM vet");

// Ambil data kunjungan sebelumnya
$previous_visits = $conn->query("SELECT visit_id, visit_date_time FROM visit WHERE visit_id != $id ORDER BY visit_id ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kunjungan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Kunjungan</h1>
        <form method="POST">
            <label for="visit_date_time">Tanggal Kunjungan:</label>
            <!-- Tampilkan tanggal kunjungan sebagai teks yang tidak dapat diedit -->
            <input type="text" id="visit_date_time" value="<?= $visit['visit_date_time'] ?>" disabled>

            <label for="visit_notes">Catatan:</label>
            <textarea id="visit_notes" name="visit_notes" rows="4" required><?= $visit['visit_notes'] ?></textarea>

            <label for="animal_id">Nama Hewan:</label>
            <select id="animal_id" name="animal_id" required>
                <?php while ($animal = $animals->fetch_assoc()): ?>
                    <option value="<?= $animal['animal_id'] ?>" <?= $animal['animal_id'] == $visit['animal_id'] ? 'selected' : '' ?>>
                        <?= $animal['animal_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="vet_id">Dokter:</label>
            <select id="vet_id" name="vet_id" required>
                <?php while ($vet = $vets->fetch_assoc()): ?>
                    <option value="<?= $vet['vet_id'] ?>" <?= $vet['vet_id'] == $visit['vet_id'] ? 'selected' : '' ?>>
                        <?= $vet['vet_givenname'] . ' ' . $vet['vet_familyname'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="from_visit_id">Kunjungan Sebelumnya (Opsional):</label>
            <select id="from_visit_id" name="from_visit_id">
                <option value="">Tidak Ada</option>
                <?php while ($prev_visit = $previous_visits->fetch_assoc()): ?>
                    <option value="<?= $prev_visit['visit_id'] ?>" <?= $prev_visit['visit_id'] == $visit['from_visit_id'] ? 'selected' : '' ?>>
                        ID <?= $prev_visit['visit_id'] ?> - <?= $prev_visit['visit_date_time'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <!-- Tombol Kembali -->
                <a href="<?= $role === 'vet' ? '../../pages/dashboard_vet.php' : '../../models/visits/index.php' ?>" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>