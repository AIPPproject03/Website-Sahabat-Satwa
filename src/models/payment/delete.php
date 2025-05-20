<?php
include '../../connection/conn.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "DELETE FROM payment WHERE payment_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data pembayaran untuk konfirmasi
$payment = $conn->query("
    SELECT 
        payment.payment_id, 
        visit.visit_id, 
        animal.animal_name, 
        owners.owner_givenname, 
        owners.owner_familyname 
    FROM payment
    JOIN visit ON payment.visit_id = visit.visit_id
    JOIN animal ON visit.animal_id = animal.animal_id
    JOIN owners ON animal.owner_id = owners.owner_id
    WHERE payment.payment_id = $id
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Pembayaran</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Hapus Pembayaran</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <p>Apakah Anda yakin ingin menghapus pembayaran berikut?</p>
        <ul>
            <li><strong>ID Pembayaran:</strong> <?= $payment['payment_id'] ?></li>
            <li><strong>ID Kunjungan:</strong> <?= $payment['visit_id'] ?></li>
            <li><strong>Nama Hewan:</strong> <?= $payment['animal_name'] ?></li>
            <li><strong>Pemilik:</strong> <?= $payment['owner_givenname'] . ' ' . $payment['owner_familyname'] ?></li>
        </ul>

        <form method="POST">
            <div class="actions">
                <button type="submit" class="btn btn-delete">Hapus</button>
                <a href="index.php" class="btn btn-back">Batal</a>
            </div>
        </form>
    </div>
</body>

</html>