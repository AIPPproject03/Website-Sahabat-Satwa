<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../app.php");
    exit();
}
include '../connection/conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <script src="../../public/assets/js/script.js" defer></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="" class="navbar-brand">Sahabat Satwa 🐾</a>
            <ul class="navbar-menu">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section id="tables" class="tables-section">
        <div class="container">
            <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            <p>Silakan pilih tabel yang ingin Anda kelola:</p>
            <div class="menu-container">
                <div class="menu-item">
                    <a href="../models/animal/index.php">
                        Animal
                        <div class="popup-info">
                            Tabel ini berisi data hewan, termasuk nama, tanggal lahir, pemilik, dan jenis hewan.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/animal_type/index.php">
                        Animal Type
                        <div class="popup-info">
                            Tabel ini berisi jenis-jenis hewan seperti kucing, anjing, ikan, dan lainnya.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/clinic/index.php">
                        Clinic
                        <div class="popup-info">
                            Tabel ini berisi data klinik hewan, termasuk nama, alamat, dan nomor telepon.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/drug/index.php">
                        Drug
                        <div class="popup-info">
                            Tabel ini berisi data obat-obatan, termasuk nama obat dan penggunaannya.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/owners/index.php">
                        Owners
                        <div class="popup-info">
                            Tabel ini berisi data pemilik hewan, termasuk nama, alamat, dan nomor telepon.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/specialisation/index.php">
                        Specialisation
                        <div class="popup-info">
                            Tabel ini berisi data spesialisasi dokter hewan, seperti onkologi, kardiologi, dan lainnya.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/spec_visit/index.php">
                        Spec Visit
                        <div class="popup-info">
                            Tabel ini berisi data kunjungan spesialis ke klinik tertentu.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/vet/index.php">
                        Vet
                        <div class="popup-info">
                            Tabel ini berisi data dokter hewan, termasuk nama, spesialisasi, dan klinik tempat bekerja.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/visits/index.php">
                        Visit
                        <div class="popup-info">
                            Tabel ini berisi data kunjungan hewan ke klinik, termasuk catatan dan dokter yang menangani.
                        </div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../models/visit_drug/index.php">
                        Visit Drug
                        <div class="popup-info">
                            Tabel ini berisi data obat yang diberikan selama kunjungan hewan ke klinik.
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

</body>

</html>