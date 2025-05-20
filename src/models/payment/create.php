<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visit_id = $_POST['visit_id'];
    $cashier_id = $_POST['cashier_id'];
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];

    $sql = "INSERT INTO payment (visit_id, cashier_id, payment_date, payment_amount, payment_method, payment_status) 
            VALUES ($visit_id, $cashier_id, CURDATE(), $payment_amount, '$payment_method', 'Paid')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data kunjungan yang belum dibayar
$visits = $conn->query("
    SELECT 
        visit.visit_id, 
        animal.animal_name, 
        owners.owner_givenname, 
        owners.owner_familyname 
    FROM visit
    JOIN animal ON visit.animal_id = animal.animal_id
    JOIN owners ON animal.owner_id = owners.owner_id
    WHERE visit.visit_status = 'Unpaid'
    ORDER BY visit.visit_id ASC
");

// Ambil data kasir
$cashiers = $conn->query("SELECT cashier_id, cashier_name FROM cashier WHERE is_active = 1");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Pembayaran Baru</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="visit_id">Kunjungan:</label>
            <select id="visit_id" name="visit_id" required>
                <?php while ($visit = $visits->fetch_assoc()): ?>
                    <option value="<?= $visit['visit_id'] ?>">
                        ID: <?= $visit['visit_id'] ?> - <?= $visit['animal_name'] ?> (<?= $visit['owner_givenname'] . ' ' . $visit['owner_familyname'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="cashier_id">Kasir:</label>
            <select id="cashier_id" name="cashier_id" required>
                <?php while ($cashier = $cashiers->fetch_assoc()): ?>
                    <option value="<?= $cashier['cashier_id'] ?>"><?= $cashier['cashier_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="payment_amount">Jumlah Pembayaran (Rp):</label>
            <input type="number" id="payment_amount" name="payment_amount" min="0" required>

            <label for="payment_method">Metode Pembayaran:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="Cash">Cash</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="Transfer">Transfer</option>
            </select>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>