<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$payment = $conn->query("SELECT * FROM payment WHERE payment_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];

    $sql = "UPDATE payment 
            SET payment_amount = $payment_amount, 
                payment_method = '$payment_method' 
            WHERE payment_id = $id";

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
    <title>Edit Pembayaran</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Pembayaran</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="payment_amount">Jumlah Pembayaran (Rp):</label>
            <input type="number" id="payment_amount" name="payment_amount" value="<?= $payment['payment_amount'] ?>" min="0" required>

            <label for="payment_method">Metode Pembayaran:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="Cash" <?= $payment['payment_method'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
                <option value="Credit Card" <?= $payment['payment_method'] === 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
                <option value="Debit Card" <?= $payment['payment_method'] === 'Debit Card' ? 'selected' : '' ?>>Debit Card</option>
                <option value="Transfer" <?= $payment['payment_method'] === 'Transfer' ? 'selected' : '' ?>>Transfer</option>
            </select>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>