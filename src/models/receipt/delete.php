<?php
include '../../connection/conn.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "DELETE FROM receipt WHERE receipt_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data kwitansi untuk konfirmasi
$receipt = $conn->query("
    SELECT 
        receipt.receipt_id, 
        receipt.receipt_number, 
        receipt.issue_date, 
        receipt.total_amount 
    FROM receipt 
    WHERE receipt_id = $id
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Kwitansi</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Hapus Kwitansi</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <p>Apakah Anda yakin ingin menghapus kwitansi berikut?</p>
        <ul>
            <li><strong>ID Kwitansi:</strong> <?= $receipt['receipt_id'] ?></li>
            <li><strong>Nomor Kwitansi:</strong> <?= $receipt['receipt_number'] ?></li>
            <li><strong>Tanggal Terbit:</strong> <?= $receipt['issue_date'] ?></li>
            <li><strong>Total Pembayaran:</strong> Rp. <?= number_format($receipt['total_amount'], 0, ',', '.') ?></li>
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