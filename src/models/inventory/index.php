<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel inventory
$sql = "SELECT 
            inventory.inventory_id, 
            drug.drug_name, 
            clinic.clinic_name, 
            inventory.quantity, 
            inventory.last_restock_date, 
            inventory.expiration_date,
            CASE
                WHEN inventory.quantity <= 10 AND inventory.expiration_date <= CURDATE() THEN 'LOW STOCK & EXPIRED'
                WHEN inventory.quantity <= 10 THEN 'LOW STOCK'
                WHEN inventory.expiration_date <= CURDATE() THEN 'EXPIRED'
                WHEN inventory.expiration_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'NEAR EXPIRY'
                ELSE 'OK'
            END AS status
        FROM inventory
        JOIN drug ON inventory.drug_id = drug.drug_id
        JOIN clinic ON inventory.clinic_id = clinic.clinic_id
        ORDER BY inventory.inventory_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Inventory</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Daftar Inventory</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">➕ Tambah Inventory Baru</a>
            <a href="../../pages/dashboard_admin.php" class="btn btn-back">🏠 Kembali ke Dashboard</a>
        </div>
        <br>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Klinik</th>
                        <th>Jumlah</th>
                        <th>Tanggal Restock</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['drug_name']) ?></td>
                            <td><?= htmlspecialchars($row['clinic_name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['last_restock_date']) ?></td>
                            <td><?= htmlspecialchars($row['expiration_date']) ?></td>
                            <td class="<?= strtolower(str_replace(' ', '-', $row['status'])) ?>"><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['inventory_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['inventory_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data inventory.</p>
        <?php endif; ?>
    </div>
</body>

</html>