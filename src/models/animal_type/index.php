<?php
include '../../connection/conn.php';

// Query untuk mengambil data dari tabel animal_type
$sql = "SELECT at_id, at_description FROM animal_type ORDER BY at_id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jenis Hewan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
    <script src="../../../public/assets/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>Daftar Jenis Hewan</h1>
        <div class="actions">
            <a href="create.php" class="btn btn-add">
                <span class="icon">➕</span> Tambah Jenis Hewan Baru
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
                        <th>Jenis Hewan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['at_description'] ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['at_id'] ?>" class="btn btn-edit">
                                    ✏️ Edit
                                </a>
                                <a href="delete.php?id=<?= $row['at_id'] ?>" class="btn btn-delete">
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