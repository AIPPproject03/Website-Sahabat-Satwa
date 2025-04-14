<?php
include '../../connection/conn.php';

$visit_id = $_GET['visit_id'];
$drug_id = $_GET['drug_id'];
$role = $_GET['role'] ?? 'admin'; // Default ke 'admin' jika parameter role tidak ada

// Ambil data visit_drug berdasarkan visit_id dan drug_id
$visit_drug = $conn->query("SELECT * FROM visit_drug WHERE visit_id = $visit_id AND drug_id = $drug_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visit_drug_dose = $_POST['visit_drug_dose'];
    $visit_drug_frequency = $_POST['visit_drug_frequency'];
    $visit_drug_qtysupplied = $_POST['visit_drug_qtysupplied'];

    $sql = "UPDATE visit_drug 
            SET visit_drug_dose = '$visit_drug_dose', 
                visit_drug_frequency = '$visit_drug_frequency', 
                visit_drug_qtysupplied = $visit_drug_qtysupplied 
            WHERE visit_id = $visit_id AND drug_id = $drug_id";
    if ($conn->query($sql) === TRUE) {
        // Redirect berdasarkan role
        if ($role === 'vet') {
            header("Location: ../../pages/dashboard_vet.php");
        } else {
            header("Location: ../../models/visit_drug/index.php");
        }
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data tambahan untuk ditampilkan di form
$visit = $conn->query("SELECT visit_date_time FROM visit WHERE visit_id = $visit_id")->fetch_assoc();
$drug = $conn->query("SELECT drug_name FROM drug WHERE drug_id = $drug_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Obat Kunjungan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Obat Kunjungan</h1>
        <form method="POST">
            <label>Tanggal Kunjungan:</label>
            <input type="text" value="<?= $visit['visit_date_time'] ?>" disabled>

            <label>Nama Obat:</label>
            <input type="text" value="<?= $drug['drug_name'] ?>" disabled>

            <label for="visit_drug_dose">Dosis:</label>
            <input type="text" id="visit_drug_dose" name="visit_drug_dose" value="<?= $visit_drug['visit_drug_dose'] ?>" required>

            <label for="visit_drug_frequency">Frekuensi:</label>
            <input type="text" id="visit_drug_frequency" name="visit_drug_frequency" value="<?= $visit_drug['visit_drug_frequency'] ?>">

            <label for="visit_drug_qtysupplied">Jumlah:</label>
            <input type="number" id="visit_drug_qtysupplied" name="visit_drug_qtysupplied" value="<?= $visit_drug['visit_drug_qtysupplied'] ?>" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <!-- Tombol Kembali -->
                <a href="<?= $role === 'vet' ? '../../pages/dashboard_vet.php' : '../../models/visit_drug/index.php' ?>" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>