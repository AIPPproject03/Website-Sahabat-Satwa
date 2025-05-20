<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel cashier
$sql = "SELECT 
            cashier.cashier_id, 
            cashier.cashier_name, 
            cashier.cashier_username, 
            clinic.clinic_name, 
            cashier.is_active 
        FROM cashier
        JOIN clinic ON cashier.clinic_id = clinic.clinic_id
        ORDER BY cashier.cashier_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kasir</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Daftar Kasir</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">➕ Tambah Kasir Baru</a>
            <a href="../../pages/dashboard_admin.php" class="btn btn-back">🏠 Kembali ke Dashboard</a>
        </div>
        <br>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Kasir</th>
                        <th>Username</th>
                        <th>Klinik</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['cashier_name']) ?></td>
                            <td><?= htmlspecialchars($row['cashier_username']) ?></td>
                            <td><?= htmlspecialchars($row['clinic_name']) ?></td>
                            <td><?= $row['is_active'] ? 'Aktif' : 'Tidak Aktif' ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['cashier_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['cashier_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data kasir.</p>
        <?php endif; ?>
    </div>
</body>

</html>