<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel visit_drug
$sql = "SELECT 
            visit_drug.visit_id, 
            visit.visit_date_time, 
            drug.drug_id, 
            drug.drug_name, 
            visit_drug.visit_drug_dose, 
            visit_drug.visit_drug_frequency, 
            visit_drug.visit_drug_qtysupplied 
        FROM visit_drug
        JOIN visit ON visit_drug.visit_id = visit.visit_id
        JOIN drug ON visit_drug.drug_id = drug.drug_id
        ORDER BY visit_drug.visit_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Obat Kunjungan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
    <script src="../../../public/assets/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Daftar Obat Kunjungan</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">
                <span class="icon">➕</span> Tambah Obat Kunjungan
            </a>
            <a href="../../app.php" class="btn btn-back">
                <span class="icon">🏠</span> Kembali ke Menu Utama
            </a>
        </div>
        <br>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Kunjungan</th>
                        <th>Nama Obat</th>
                        <th>Dosis</th>
                        <th>Frekuensi</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['visit_date_time']) ?></td>
                            <td><?= htmlspecialchars($row['drug_name']) ?></td>
                            <td><?= htmlspecialchars($row['visit_drug_dose']) ?></td>
                            <td><?= htmlspecialchars($row['visit_drug_frequency']) ?></td>
                            <td><?= htmlspecialchars($row['visit_drug_qtysupplied']) ?></td>
                            <td>
                                <a href="update.php?visit_id=<?= $row['visit_id'] ?>&drug_id=<?= $row['drug_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?visit_id=<?= $row['visit_id'] ?>&drug_id=<?= $row['drug_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data untuk kunjungan ini.</p>
        <?php endif; ?>
    </div>
</body>

</html>