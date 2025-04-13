<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel vet
$sql = "SELECT 
            vet.vet_id, 
            vet.vet_title, 
            vet.vet_givenname, 
            vet.vet_familyname, 
            vet.vet_phone, 
            vet.vet_employed, 
            specialisation.spec_description, 
            clinic.clinic_name 
        FROM vet
        LEFT JOIN specialisation ON vet.spec_id = specialisation.spec_id
        LEFT JOIN clinic ON vet.clinic_id = clinic.clinic_id
        ORDER BY vet.vet_id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokter Hewan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
    <script src="../../../public/assets/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Daftar Dokter Hewan</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">
                <span class="icon">➕</span> Tambah Dokter Baru
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
                        <th>Nama Dokter</th>
                        <th>Telepon</th>
                        <th>Tanggal Bergabung</th>
                        <th>Spesialisasi</th>
                        <th>Klinik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['vet_title'] . ' ' . $row['vet_givenname'] . ' ' . $row['vet_familyname'] ?></td>
                            <td><?= $row['vet_phone'] ?></td>
                            <td><?= $row['vet_employed'] ?></td>
                            <td><?= $row['spec_description'] ?? 'Tidak Ada' ?></td>
                            <td><?= $row['clinic_name'] ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['vet_id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['vet_id'] ?>" class="btn btn-delete">🗑️ Hapus</a>
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