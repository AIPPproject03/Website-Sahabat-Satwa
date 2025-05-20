<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$inventory = $conn->query("SELECT * FROM inventory WHERE inventory_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];
    $last_restock_date = $_POST['last_restock_date'];
    $expiration_date = $_POST['expiration_date'];

    $sql = "UPDATE inventory 
            SET quantity = $quantity, 
                last_restock_date = '$last_restock_date', 
                expiration_date = '$expiration_date' 
            WHERE inventory_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data obat
$drug = $conn->query("SELECT drug_name FROM drug WHERE drug_id = {$inventory['drug_id']}")->fetch_assoc();

// Ambil data klinik
$clinic = $conn->query("SELECT clinic_name FROM clinic WHERE clinic_id = {$inventory['clinic_id']}")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Inventory</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="drug_name">Nama Obat:</label>
            <input type="text" id="drug_name" value="<?= $drug['drug_name'] ?>" readonly>

            <label for="clinic_name">Klinik:</label>
            <input type="text" id="clinic_name" value="<?= $clinic['clinic_name'] ?>" readonly>

            <label for="quantity">Jumlah:</label>
            <input type="number" id="quantity" name="quantity" min="0" value="<?= $inventory['quantity'] ?>" required>

            <label for="last_restock_date">Tanggal Restock:</label>
            <input type="date" id="last_restock_date" name="last_restock_date" value="<?= $inventory['last_restock_date'] ?>" required>

            <label for="expiration_date">Tanggal Kadaluarsa:</label>
            <input type="date" id="expiration_date" name="expiration_date" value="<?= $inventory['expiration_date'] ?>" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>