<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'];
    $receipt_number = $_POST['receipt_number'];
    $issue_date = $_POST['issue_date'];
    $total_amount = $_POST['total_amount'];

    $sql = "INSERT INTO receipt (payment_id, receipt_number, issue_date, total_amount) 
            VALUES ($payment_id, '$receipt_number', '$issue_date', $total_amount)";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data pembayaran
$payments = $conn->query("
    SELECT 
        payment.payment_id, 
        payment.payment_date, 
        payment.payment_amount, 
        payment.payment_method 
    FROM payment
    WHERE NOT EXISTS (
        SELECT 1 FROM receipt WHERE receipt.payment_id = payment.payment_id
    )
    ORDER BY payment.payment_id ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kwitansi Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Kwitansi Baru</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="payment_id">ID Pembayaran:</label>
            <select id="payment_id" name="payment_id" required>
                <?php while ($payment = $payments->fetch_assoc()): ?>
                    <option value="<?= $payment['payment_id'] ?>">
                        ID: <?= $payment['payment_id'] ?> - Rp. <?= number_format($payment['payment_amount'], 0, ',', '.') ?> (<?= $payment['payment_method'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="receipt_number">Nomor Kwitansi:</label>
            <input type="text" id="receipt_number" name="receipt_number" required>

            <label for="issue_date">Tanggal Terbit:</label>
            <input type="date" id="issue_date" name="issue_date" required>

            <label for="total_amount">Total Pembayaran (Rp):</label>
            <input type="number" id="total_amount" name="total_amount" min="0" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>