<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel drug
$sql = "SELECT drug_id, drug_name, drug_usage, price FROM drug ORDER BY drug_id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Obat</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Daftar Obat</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">➕ Tambah Obat Baru</a>
            <a href="../../pages/dashboard_admin.php" class="btn btn-back">🏠 Kembali ke Dashboard</a>
        </div>
        <br>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Penggunaan</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['drug_name']) ?></td>
                            <td><?= htmlspecialchars($row['drug_usage']) ?></td>
                            <td>Rp. <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['drug_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['drug_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data obat.</p>
        <?php endif; ?>
    </div>
</body>

</html>