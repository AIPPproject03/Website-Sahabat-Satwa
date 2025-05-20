<?php
include '../../connection/conn.php';

$id = $_GET['id'];

// Ambil informasi inventory untuk konfirmasi
$inventory = $conn->query("
    SELECT 
        inventory.inventory_id, 
        drug.drug_name, 
        clinic.clinic_name 
    FROM inventory 
    JOIN drug ON inventory.drug_id = drug.drug_id 
    JOIN clinic ON inventory.clinic_id = clinic.clinic_id 
    WHERE inventory_id = $id
")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "DELETE FROM inventory WHERE inventory_id = $id";

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
    <title>Hapus Inventory</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Hapus Inventory</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="confirmation">
            <p>Apakah Anda yakin ingin menghapus inventory berikut?</p>
            <ul>
                <li><strong>Obat:</strong> <?= $inventory['drug_name'] ?></li>
                <li><strong>Klinik:</strong> <?= $inventory['clinic_name'] ?></li>
            </ul>

            <form method="POST">
                <div class="actions">
                    <button type="submit" class="btn btn-delete">Hapus</button>
                    <a href="index.php" class="btn btn-back">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>