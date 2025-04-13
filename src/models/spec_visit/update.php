<?php
include '../../connection/conn.php';

$clinic_id = $_GET['clinic_id'];
$vet_id = $_GET['vet_id'];
$spec_visit = $conn->query("SELECT * FROM spec_visit WHERE clinic_id = $clinic_id AND vet_id = $vet_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sv_count = $_POST['sv_count'];

    $sql = "UPDATE spec_visit 
            SET sv_count = $sv_count 
            WHERE clinic_id = $clinic_id AND vet_id = $vet_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$clinic = $conn->query("SELECT clinic_name FROM clinic WHERE clinic_id = $clinic_id")->fetch_assoc();
$vet = $conn->query("SELECT vet_givenname, vet_familyname FROM vet WHERE vet_id = $vet_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kunjungan Spesialis</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Kunjungan Spesialis</h1>
        <form method="POST">
            <label>Klinik:</label>
            <input type="text" value="<?= $clinic['clinic_name'] ?>" disabled>

            <label>Dokter Spesialis:</label>
            <input type="text" value="<?= $vet['vet_givenname'] . ' ' . $vet['vet_familyname'] ?>" disabled>

            <label for="sv_count">Jumlah Kunjungan:</label>
            <input type="number" id="sv_count" name="sv_count" value="<?= $spec_visit['sv_count'] ?>" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>