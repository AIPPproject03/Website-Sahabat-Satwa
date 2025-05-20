<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel receipt
$sql = "SELECT 
            receipt.receipt_id, 
            receipt.receipt_number, 
            receipt.issue_date, 
            receipt.total_amount, 
            payment.payment_id, 
            payment.payment_date, 
            payment.payment_method, 
            payment.payment_status 
        FROM receipt
        JOIN payment ON receipt.payment_id = payment.payment_id
        ORDER BY receipt.receipt_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kwitansi</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Daftar Kwitansi</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">➕ Tambah Kwitansi Baru</a>
            <a href="../../pages/dashboard_admin.php" class="btn btn-back">🏠 Kembali ke Dashboard</a>
        </div>
        <br>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Kwitansi</th>
                        <th>Nomor Kwitansi</th>
                        <th>Tanggal Terbit</th>
                        <th>Total Pembayaran</th>
                        <th>ID Pembayaran</th>
                        <th>Metode Pembayaran</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['receipt_id']) ?></td>
                            <td><?= htmlspecialchars($row['receipt_number']) ?></td>
                            <td><?= htmlspecialchars($row['issue_date']) ?></td>
                            <td>Rp. <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['payment_id']) ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td><?= htmlspecialchars($row['payment_status']) ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['receipt_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['receipt_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data kwitansi.</p>
        <?php endif; ?>
    </div>
</body>

</html>