<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$receipt = $conn->query("SELECT * FROM receipt WHERE receipt_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receipt_number = $_POST['receipt_number'];
    $issue_date = $_POST['issue_date'];
    $total_amount = $_POST['total_amount'];

    $sql = "UPDATE receipt 
            SET receipt_number = '$receipt_number', 
                issue_date = '$issue_date', 
                total_amount = $total_amount 
            WHERE receipt_id = $id";

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
    <title>Edit Kwitansi</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Kwitansi</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="receipt_number">Nomor Kwitansi:</label>
            <input type="text" id="receipt_number" name="receipt_number" value="<?= $receipt['receipt_number'] ?>" required>

            <label for="issue_date">Tanggal Terbit:</label>
            <input type="date" id="issue_date" name="issue_date" value="<?= $receipt['issue_date'] ?>" required>

            <label for="total_amount">Total Pembayaran (Rp):</label>
            <input type="number" id="total_amount" name="total_amount" value="<?= $receipt['total_amount'] ?>" min="0" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>