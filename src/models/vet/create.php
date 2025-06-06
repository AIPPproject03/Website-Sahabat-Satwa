<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vet_title = $_POST['vet_title'];
    $vet_givenname = $_POST['vet_givenname'];
    $vet_familyname = $_POST['vet_familyname'];
    $vet_phone = $_POST['vet_phone'];
    $vet_employed = $_POST['vet_employed'];
    $spec_id = $_POST['spec_id'] ?? 'NULL';
    $clinic_id = $_POST['clinic_id'];

    $sql = "INSERT INTO vet (vet_title, vet_givenname, vet_familyname, vet_phone, vet_employed, spec_id, clinic_id) 
            VALUES ('$vet_title', '$vet_givenname', '$vet_familyname', '$vet_phone', '$vet_employed', $spec_id, $clinic_id)";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$specialisations = $conn->query("SELECT spec_id, spec_description FROM specialisation");
$clinics = $conn->query("SELECT clinic_id, clinic_name FROM clinic");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Dokter Baru</h1>
        <form method="POST">
            <label for="vet_title">Gelar:</label>
            <input type="text" id="vet_title" name="vet_title" required>

            <label for="vet_givenname">Nama Depan:</label>
            <input type="text" id="vet_givenname" name="vet_givenname" required>

            <label for="vet_familyname">Nama Belakang:</label>
            <input type="text" id="vet_familyname" name="vet_familyname" required>

            <label for="vet_phone">Telepon:</label>
            <input type="text" id="vet_phone" name="vet_phone" required>

            <label for="vet_employed">Tanggal Bergabung:</label>
            <input type="date" id="vet_employed" name="vet_employed" required>

            <label for="spec_id">Spesialisasi:</label>
            <select id="spec_id" name="spec_id">
                <option value="">Tidak Ada</option>
                <?php while ($spec = $specialisations->fetch_assoc()): ?>
                    <option value="<?= $spec['spec_id'] ?>"><?= $spec['spec_description'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="clinic_id">Klinik:</label>
            <select id="clinic_id" name="clinic_id" required>
                <?php while ($clinic = $clinics->fetch_assoc()): ?>
                    <option value="<?= $clinic['clinic_id'] ?>"><?= $clinic['clinic_name'] ?></option>
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