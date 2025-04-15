<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran vet
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'vet') {
    header("Location: ../app.php");
    exit();
}
include '../connection/conn.php';

// Ambil vet_id dari session
$vet_id = $_SESSION['vet_id'];

// Query untuk mengambil data kunjungan yang hanya milik vet yang login
$visits_sql = "SELECT 
                    visit.visit_id, 
                    visit.visit_date_time, 
                    visit.visit_notes, 
                    animal.animal_name, 
                    owners.owner_givenname, 
                    owners.owner_familyname 
                FROM visit
                JOIN animal ON visit.animal_id = animal.animal_id
                JOIN owners ON animal.owner_id = owners.owner_id
                WHERE visit.vet_id = $vet_id
                ORDER BY visit.visit_id ASC";
$visits_result = $conn->query($visits_sql);

// Query untuk mengambil data obat kunjungan yang hanya milik vet yang login
$visit_drug_sql = "SELECT 
                        visit_drug.visit_id, 
                        visit_drug.drug_id, 
                        drug.drug_name, 
                        visit_drug.visit_drug_dose, 
                        visit_drug.visit_drug_frequency, 
                        visit_drug.visit_drug_qtysupplied 
                    FROM visit_drug
                    JOIN drug ON visit_drug.drug_id = drug.drug_id
                    JOIN visit ON visit_drug.visit_id = visit.visit_id
                    WHERE visit.vet_id = $vet_id
                    ORDER BY visit_drug.visit_id ASC";
$visit_drug_result = $conn->query($visit_drug_sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vet - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">Sahabat Satwa 🐾</a>
            <ul class="navbar-menu">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section id="visits" class="dashboard-section">
        <div class="container">
            <h1>Data Kunjungan</h1>
            <!-- Tambahkan parameter role=vet ke tautan -->
            <a href="../models/visits/create.php?role=vet" class="btn btn-add">Tambah Kunjungan</a>
            <?php if ($visits_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal Kunjungan</th>
                            <th>Catatan</th>
                            <th>Nama Hewan</th>
                            <th>Pemilik</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $visits_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['visit_date_time']) ?></td>
                                <td><?= htmlspecialchars($row['visit_notes']) ?></td>
                                <td><?= htmlspecialchars($row['animal_name']) ?></td>
                                <td><?= htmlspecialchars($row['owner_givenname'] . ' ' . $row['owner_familyname']) ?></td>
                                <td>
                                    <!-- Tambahkan parameter role=vet ke tautan -->
                                    <a href="../models/visits/update.php?id=<?= $row['visit_id'] ?>&role=vet" class="btn btn-edit">✏️ Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Tidak ada data kunjungan.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="visit-drugs" class="dashboard-section">
        <div class="container">
            <h1>Data Obat Kunjungan</h1>
            <!-- Tambahkan parameter role=vet ke tautan -->
            <a href="../models/visit_drug/create.php?role=vet" class="btn btn-add">Tambah Obat Kunjungan</a>
            <?php if ($visit_drug_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID Kunjungan</th>
                            <th>Nama Obat</th>
                            <th>Dosis</th>
                            <th>Frekuensi</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $visit_drug_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['visit_id']) ?></td>
                                <td><?= htmlspecialchars($row['drug_name']) ?></td>
                                <td><?= htmlspecialchars($row['visit_drug_dose']) ?></td>
                                <td><?= htmlspecialchars($row['visit_drug_frequency']) ?></td>
                                <td><?= htmlspecialchars($row['visit_drug_qtysupplied']) ?></td>
                                <td>
                                    <!-- Tambahkan parameter role=vet ke tautan -->
                                    <a href="../models/visit_drug/update.php?visit_id=<?= $row['visit_id'] ?>&drug_id=<?= $row['drug_id'] ?>&role=vet" class="btn btn-edit">✏️ Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Tidak ada data obat kunjungan.</p>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>