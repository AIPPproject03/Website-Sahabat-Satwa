<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel animal dengan urutan berdasarkan ID
$sql = "SELECT 
            animal.animal_id, 
            animal.animal_name, 
            animal.animal_born, 
            owners.owner_givenname, 
            owners.owner_familyname, 
            animal_type.at_description 
        FROM animal
        JOIN owners ON animal.owner_id = owners.owner_id
        JOIN animal_type ON animal.at_id = animal_type.at_id
        ORDER BY animal.animal_id ASC"; // Tambahkan ORDER BY untuk mengurutkan berdasarkan ID

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hewan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
    <script src="../../../public/assets/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Daftar Hewan</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">
                <span class="icon">➕</span> Tambah Hewan Baru
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
                        <th>Nama Hewan</th>
                        <th>Tanggal Lahir</th>
                        <th>Pemilik</th>
                        <th>Jenis Hewan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['animal_name'] ?></td>
                            <td><?= $row['animal_born'] ?></td>
                            <td><?= $row['owner_givenname'] . ' ' . $row['owner_familyname'] ?></td>
                            <td><?= $row['at_description'] ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['animal_id'] ?>" class="btn btn-edit">
                                    ✏️ Edit
                                </a>
                                <a href="delete.php?id=<?= $row['animal_id'] ?>" class="btn btn-delete">
                                    🗑️ Hapus
                                </a>
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