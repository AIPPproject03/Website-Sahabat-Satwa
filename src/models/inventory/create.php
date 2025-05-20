<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $drug_id = $_POST['drug_id'];
    $clinic_id = $_POST['clinic_id'];
    $quantity = $_POST['quantity'];
    $last_restock_date = $_POST['last_restock_date'];
    $expiration_date = $_POST['expiration_date'];

    // Periksa apakah kombinasi drug_id dan clinic_id sudah ada
    $check_sql = "SELECT COUNT(*) as count FROM inventory WHERE drug_id = $drug_id AND clinic_id = $clinic_id";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();

    if ($check_row['count'] > 0) {
        $error_message = "Kombinasi Obat dan Klinik ini sudah ada dalam inventory. Silakan gunakan fitur Edit untuk mengubah jumlah.";
    } else {
        $sql = "INSERT INTO inventory (drug_id, clinic_id, quantity, last_restock_date, expiration_date) 
                VALUES ($drug_id, $clinic_id, $quantity, '$last_restock_date', '$expiration_date')";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Ambil data obat
$drugs = $conn->query("SELECT drug_id, drug_name FROM drug ORDER BY drug_name ASC");

// Ambil data klinik
$clinics = $conn->query("SELECT clinic_id, clinic_name FROM clinic ORDER BY clinic_name ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Inventory Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Inventory Baru</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="drug_id">Nama Obat:</label>
            <select id="drug_id" name="drug_id" required>
                <?php while ($drug = $drugs->fetch_assoc()): ?>
                    <option value="<?= $drug['drug_id'] ?>"><?= $drug['drug_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="clinic_id">Klinik:</label>
            <select id="clinic_id" name="clinic_id" required>
                <?php while ($clinic = $clinics->fetch_assoc()): ?>
                    <option value="<?= $clinic['clinic_id'] ?>"><?= $clinic['clinic_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="quantity">Jumlah:</label>
            <input type="number" id="quantity" name="quantity" min="0" required>

            <label for="last_restock_date">Tanggal Restock:</label>
            <input type="date" id="last_restock_date" name="last_restock_date" required>

            <label for="expiration_date">Tanggal Kadaluarsa:</label>
            <input type="date" id="expiration_date" name="expiration_date" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>