<?php
include '../../connection/conn.php';

$role = $_GET['role'] ?? 'admin'; // Default ke 'admin' jika parameter role tidak ada

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visit_date_time = $_POST['visit_date_time'];
    $visit_notes = $_POST['visit_notes'];
    $animal_id = $_POST['animal_id'];
    $vet_id = $_POST['vet_id'];
    $from_visit_id = $_POST['from_visit_id'] ?? 'NULL'; // Jika tidak ada kunjungan sebelumnya, set NULL

    $sql = "INSERT INTO visit (visit_date_time, visit_notes, animal_id, vet_id, from_visit_id) 
            VALUES ('$visit_date_time', '$visit_notes', $animal_id, $vet_id, $from_visit_id)";
    if ($conn->query($sql) === TRUE) {
        // Redirect berdasarkan role
        if ($role === 'vet') {
            header("Location: ../../pages/dashboard_vet.php");
        } else {
            header("Location: ../../pages/dashboard_admin.php");
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
$previous_visits = $conn->query("SELECT visit_id, visit_date_time FROM visit ORDER BY visit_id ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kunjungan Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Kunjungan Baru</h1>
        <form method="POST">
            <label for="visit_date_time">Tanggal Kunjungan:</label>
            <input type="datetime-local" id="visit_date_time" name="visit_date_time" required>

            <label for="visit_notes">Catatan:</label>
            <textarea id="visit_notes" name="visit_notes" rows="4" required></textarea>

            <label for="animal_id">Nama Hewan:</label>
            <select id="animal_id" name="animal_id" required>
                <?php while ($animal = $animals->fetch_assoc()): ?>
                    <option value="<?= $animal['animal_id'] ?>"><?= $animal['animal_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="vet_id">Dokter:</label>
            <select id="vet_id" name="vet_id" required>
                <?php while ($vet = $vets->fetch_assoc()): ?>
                    <option value="<?= $vet['vet_id'] ?>"><?= $vet['vet_givenname'] . ' ' . $vet['vet_familyname'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="from_visit_id">Kunjungan Sebelumnya (Opsional):</label>
            <select id="from_visit_id" name="from_visit_id">
                <option value="">Tidak Ada</option>
                <?php while ($visit = $previous_visits->fetch_assoc()): ?>
                    <option value="<?= $visit['visit_id'] ?>">ID <?= $visit['visit_id'] ?> - <?= $visit['visit_date_time'] ?></option>
                <?php endwhile; ?>
            </select>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="<?= $role === 'vet' ? '../../pages/dashboard_vet.php' : '../../pages/dashboard_admin.php' ?>" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>