<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel visits
$sql = "SELECT 
            visit.visit_id, 
            visit.visit_date_time, 
            visit.visit_notes, 
            animal.animal_name, 
            vet.vet_givenname, 
            vet.vet_familyname 
        FROM visit
        JOIN animal ON visit.animal_id = animal.animal_id
        JOIN vet ON visit.vet_id = vet.vet_id
        ORDER BY visit.visit_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kunjungan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
    <script src="../../../public/assets/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Daftar Kunjungan</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">
                <span class="icon">➕</span> Tambah Kunjungan Baru
            </a>
            <a href="../../pages/dashboard_admin.php" class="btn btn-back">
                <span class="icon">🏠</span> Kembali ke Menu Utama
            </a>
        </div>
        <br>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Kunjungan</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Catatan</th>
                        <th>Nama Hewan</th>
                        <th>Dokter</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['visit_id']) ?></td>
                            <td><?= htmlspecialchars($row['visit_date_time']) ?></td>
                            <td><?= htmlspecialchars($row['visit_notes']) ?></td>
                            <td><?= htmlspecialchars($row['animal_name']) ?></td>
                            <td><?= htmlspecialchars($row['vet_givenname'] . ' ' . $row['vet_familyname']) ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['visit_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['visit_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data kunjungan.</p>
        <?php endif; ?>
    </div>
</body>

</html>