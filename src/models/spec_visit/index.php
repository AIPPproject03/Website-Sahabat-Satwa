<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel spec_visit
$sql = "SELECT 
            spec_visit.clinic_id, 
            clinic.clinic_name, 
            spec_visit.vet_id, 
            vet.vet_givenname, 
            vet.vet_familyname, 
            spec_visit.sv_count 
        FROM spec_visit
        JOIN clinic ON spec_visit.clinic_id = clinic.clinic_id
        JOIN vet ON spec_visit.vet_id = vet.vet_id
        ORDER BY spec_visit.clinic_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kunjungan Spesialis</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
    <script src="../../../public/assets/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Daftar Kunjungan Spesialis</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">
                <span class="icon">➕</span> Tambah Kunjungan Baru
            </a>
            <a href="../../app.php" class="btn btn-back">
                <span class="icon">🏠</span> Kembali ke Menu Utama
            </a>
        </div>
        <br>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Klinik</th>
                        <th>Dokter Spesialis</th>
                        <th>Jumlah Kunjungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['clinic_name'] ?></td>
                            <td><?= $row['vet_givenname'] . ' ' . $row['vet_familyname'] ?></td>
                            <td><?= $row['sv_count'] ?></td>
                            <td>
                                <a href="update.php?clinic_id=<?= $row['clinic_id'] ?>&vet_id=<?= $row['vet_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?clinic_id=<?= $row['clinic_id'] ?>&vet_id=<?= $row['vet_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada data.</p>
        <?php endif; ?>
    </div>
</body>

</html>