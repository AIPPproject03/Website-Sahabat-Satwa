<?php
include '../../connection/conn.php';

$role = $_GET['role'] ?? 'admin'; // Default ke 'admin' jika parameter role tidak ada
$vet_id = $_SESSION['vet_id'] ?? null;

// Ambil data kunjungan berdasarkan role
if ($role === 'vet') {
    // Vet hanya dapat memilih kunjungan yang mereka layani
    $visits = $conn->query("SELECT visit_id FROM visit WHERE vet_id = $vet_id ORDER BY visit_id ASC");
} else {
    // Admin dapat memilih semua kunjungan
    $visits = $conn->query("SELECT visit_id FROM visit ORDER BY visit_id ASC");
}

// Ambil data obat
$drugs = $conn->query("SELECT drug_id, drug_name FROM drug");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visit_id = $_POST['visit_id'];
    $drug_id = $_POST['drug_id'];
    $visit_drug_dose = $_POST['visit_drug_dose'];
    $visit_drug_frequency = $_POST['visit_drug_frequency'];
    $visit_drug_qtysupplied = $_POST['visit_drug_qtysupplied'];

    $sql = "INSERT INTO visit_drug (visit_id, drug_id, visit_drug_dose, visit_drug_frequency, visit_drug_qtysupplied) 
            VALUES ($visit_id, $drug_id, '$visit_drug_dose', '$visit_drug_frequency', $visit_drug_qtysupplied)";
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat Kunjungan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Obat Kunjungan</h1>
        <form method="POST">
            <label for="visit_id">ID Kunjungan:</label>
            <select id="visit_id" name="visit_id" required>
                <?php while ($visit = $visits->fetch_assoc()): ?>
                    <option value="<?= $visit['visit_id'] ?>"><?= $visit['visit_id'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="drug_id">Nama Obat:</label>
            <select id="drug_id" name="drug_id" required>
                <?php while ($drug = $drugs->fetch_assoc()): ?>
                    <option value="<?= $drug['drug_id'] ?>"><?= $drug['drug_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="visit_drug_dose">Dosis:</label>
            <input type="text" id="visit_drug_dose" name="visit_drug_dose" required>

            <label for="visit_drug_frequency">Frekuensi:</label>
            <input type="text" id="visit_drug_frequency" name="visit_drug_frequency">

            <label for="visit_drug_qtysupplied">Jumlah:</label>
            <input type="number" id="visit_drug_qtysupplied" name="visit_drug_qtysupplied" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="<?= $role === 'vet' ? '../../pages/dashboard_vet.php' : '../../models/visit_drug/index.php' ?>" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>