<?php
session_start();
include '../../connection/conn.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../pages/login.php");
    exit();
}

// Periksa role pengguna
$redirect_dashboard = ($_SESSION['role'] === 'cashier') ? "../../pages/dashboard_cashier.php" : "../../pages/dashboard_admin.php";

// Query untuk mengambil data dari tabel payment
$sql = "SELECT 
            payment.payment_id, 
            visit.visit_id, 
            animal.animal_name, 
            owners.owner_givenname, 
            owners.owner_familyname, 
            payment.payment_date, 
            payment.payment_amount, 
            payment.payment_method, 
            payment.payment_status, 
            cashier.cashier_name 
        FROM payment
        JOIN visit ON payment.visit_id = visit.visit_id
        JOIN animal ON visit.animal_id = animal.animal_id
        JOIN owners ON animal.owner_id = owners.owner_id
        JOIN cashier ON payment.cashier_id = cashier.cashier_id
        ORDER BY payment.payment_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembayaran</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Daftar Pembayaran</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">➕ Tambah Pembayaran Baru</a>
            <a href="<?= $redirect_dashboard ?>" class="btn btn-back">🏠 Kembali ke Dashboard</a>
        </div>
        <br>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pembayaran</th>
                        <th>ID Kunjungan</th>
                        <th>Nama Hewan</th>
                        <th>Pemilik</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['payment_id']) ?></td>
                            <td><?= htmlspecialchars($row['visit_id']) ?></td>
                            <td><?= htmlspecialchars($row['animal_name']) ?></td>
                            <td><?= htmlspecialchars($row['owner_givenname'] . ' ' . $row['owner_familyname']) ?></td>
                            <td><?= htmlspecialchars($row['payment_date']) ?></td>
                            <td>Rp. <?= number_format($row['payment_amount'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td><?= htmlspecialchars($row['payment_status']) ?></td>
                            <td><?= htmlspecialchars($row['cashier_name']) ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['payment_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['payment_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data pembayaran.</p>
        <?php endif; ?>
    </div>
</body>

</html>