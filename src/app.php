<?php
include './connection/conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Satwa</title>
    <link rel="stylesheet" href="../public/assets/css/landing.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">Sahabat Satwa 🐾</a>
            <ul class="navbar-menu">
                <li><a href="#about">Tentang</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="pages/login.php">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-image">
            <img src="../public/img/hero-image.jpg" alt="Hewan Peliharaan">
        </div>
        <div class="hero-content">
            <h1>Selamat Datang di Sahabat Satwa</h1>
            <p>Platform pengelolaan data hewan, pemilik, dokter hewan, dan klinik yang mudah dan efisien.</p>
            <a href="pages/login.php" class="btn btn-primary">Login Sekarang</a>
        </div>
    </header>

    <!-- Tentang Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2>Tentang Sahabat Satwa</h2>
            <p>
                Sahabat Satwa adalah aplikasi berbasis web yang dirancang untuk membantu klinik hewan dalam mengelola data hewan, pemilik, dokter hewan, dan kunjungan dengan mudah dan efisien.
                Kami percaya bahwa setiap hewan layak mendapatkan perawatan terbaik.
            </p>
        </div>
    </section>

    <!-- Fitur Section -->
    <section id="features" class="features-section">
        <div class="container">
            <h2>Fitur Utama</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-image">
                        <img src="../public/img/animal.jpg" alt="Manajemen Hewan">
                    </div>
                    <h3>Manajemen Hewan</h3>
                    <p>Kelola data hewan peliharaan dengan mudah, termasuk nama, tanggal lahir, dan jenis hewan.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-image">
                        <img src="../public/img/owner.jpg" alt="Manajemen Pemilik">
                    </div>
                    <h3>Manajemen Pemilik</h3>
                    <p>Catat informasi pemilik hewan seperti nama, alamat, dan nomor telepon.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-image">
                        <img src="../public/img/vet.jpg" alt="Manajemen Dokter">
                    </div>
                    <h3>Manajemen Dokter</h3>
                    <p>Kelola data dokter hewan, termasuk spesialisasi dan klinik tempat bekerja.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-image">
                        <img src="../public/img/clinic.jpg" alt="Manajemen Klinik">
                    </div>
                    <h3>Manajemen Klinik</h3>
                    <p>Kelola data klinik hewan, termasuk nama, alamat, dan nomor telepon.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Sahabat Satwa. Semua Hak Dilindungi.</p>
            <p>Hubungi kami di <a href="mailto:info@sahabatsatwa.com">info@sahabatsatwa.com</a></p>
            <p>Ikuti kami di:
                <a href="https://facebook.com" target="_blank">Facebook</a> |
                <a href="https://instagram.com" target="_blank">Instagram</a> |
                <a href="https://twitter.com" target="_blank">Twitter</a>
            </p>
        </div>
    </footer>
</body>

</html>