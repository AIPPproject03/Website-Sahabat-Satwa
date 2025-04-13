<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clinic_id = $_POST['clinic_id'];
    $vet_id = $_POST['vet_id'];
    $sv_count = $_POST['sv_count'];

    $sql = "INSERT INTO spec_visit (clinic_id, vet_id, sv_count) 
            VALUES ($clinic_id, $vet_id, $sv_count)";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$clinics = $conn->query("SELECT clinic_id, clinic_name FROM clinic");
$vets = $conn->query("SELECT vet_id, vet_givenname, vet_familyname FROM vet");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kunjungan Spesialis</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Kunjungan Spesialis</h1>
        <form method="POST">
            <label for="clinic_id">Klinik:</label>
            <select id="clinic_id" name="clinic_id" required>
                <?php while ($clinic = $clinics->fetch_assoc()): ?>
                    <option value="<?= $clinic['clinic_id'] ?>"><?= $clinic['clinic_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="vet_id">Dokter Spesialis:</label>
            <select id="vet_id" name="vet_id" required>
                <?php while ($vet = $vets->fetch_assoc()): ?>
                    <option value="<?= $vet['vet_id'] ?>"><?= $vet['vet_givenname'] . ' ' . $vet['vet_familyname'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="sv_count">Jumlah Kunjungan:</label>
            <input type="number" id="sv_count" name="sv_count" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>