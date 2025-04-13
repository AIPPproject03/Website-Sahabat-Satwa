<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$visit = $conn->query("SELECT * FROM visit WHERE visit_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visit_date_time = $_POST['visit_date_time'];
    $visit_notes = $_POST['visit_notes'];
    $animal_id = $_POST['animal_id'];
    $vet_id = $_POST['vet_id'];

    $sql = "UPDATE visit 
            SET visit_date_time = '$visit_date_time', visit_notes = '$visit_notes', 
                animal_id = $animal_id, vet_id = $vet_id 
            WHERE visit_id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$animals = $conn->query("SELECT animal_id, animal_name FROM animal");
$vets = $conn->query("SELECT vet_id, vet_givenname, vet_familyname FROM vet");
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
            <input type="date" id="visit_date_time" name="visit_date_time" value="<?= $visit['visit_date_time'] ?>" required>

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

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>