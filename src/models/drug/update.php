<?php
// filepath: d:\AIPPROJECT03\TUGAS WEB\Website Sahabat Satwa\src\models\drug\update.php
include '../../connection/conn.php';

$id = $_GET['id'];
$drug = $conn->query("SELECT * FROM drug WHERE drug_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $drug_name = $_POST['drug_name'];
    $drug_usage = $_POST['drug_usage'];
    $price = $_POST['price'];

    $sql = "UPDATE drug SET drug_name = '$drug_name', drug_usage = '$drug_usage', price = $price WHERE drug_id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Obat</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Obat</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="drug_name">Nama Obat:</label>
            <input type="text" id="drug_name" name="drug_name" value="<?= $drug['drug_name'] ?>" required>

            <label for="drug_usage">Penggunaan:</label>
            <input type="text" id="drug_usage" name="drug_usage" value="<?= $drug['drug_usage'] ?>" required>

            <label for="price">Harga (Rp):</label>
            <input type="number" id="price" name="price" value="<?= $drug['price'] ?>" min="0" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>