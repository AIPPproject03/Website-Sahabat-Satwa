<?php
include './connection/conn.php';

// Query untuk mendapatkan jumlah total data
$total_clinics = $conn->query("SELECT COUNT(*) AS total FROM clinic")->fetch_assoc()['total'];
$total_vets = $conn->query("SELECT COUNT(*) AS total FROM vet")->fetch_assoc()['total'];
$total_visits = $conn->query("SELECT COUNT(*) AS total FROM visit")->fetch_assoc()['total'];
$total_animals = $conn->query("SELECT COUNT(*) AS total FROM animal")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Satwa</title>
    <link rel="stylesheet" href="../public/assets/css/landing.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">Sahabat Satwa 🐾</a>
            <ul class="navbar-menu">
                <li><a href="#about">Tentang</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#report">Laporan</a></li>
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

    <!-- Laporan Section -->
    <section id="report" class="report-section">
        <div class="container">
            <h2>Laporan Keseluruhan</h2>
            <div class="chart-container">
                <canvas id="reportChart"></canvas>
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

    <script>
        // Data untuk grafik
        const data = {
            labels: ['Klinik', 'Dokter Hewan', 'Kunjungan', 'Hewan'],
            datasets: [{
                label: 'Jumlah Data',
                data: [<?= $total_clinics ?>, <?= $total_vets ?>, <?= $total_visits ?>, <?= $total_animals ?>],
                backgroundColor: [
                    '#4caf50', // Hijau
                    '#2196f3', // Biru
                    '#ffc107', // Kuning
                    '#9c27b0' // Ungu
                ],
                borderRadius: 10, // Membuat sudut bar lebih bulat
                barThickness: 30 // Ketebalan bar
            }]
        };

        // Konfigurasi grafik
        const config = {
            type: 'bar', // Jenis grafik: bar
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false, // Membuat grafik lebih fleksibel
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda untuk tampilan minimalis
                    },
                    title: {
                        display: true,
                        text: 'Laporan Keseluruhan Sahabat Satwa',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        color: '#333'
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false // Hilangkan garis grid pada sumbu X
                        },
                        ticks: {
                            color: '#666', // Warna label sumbu X
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#e0e0e0', // Warna garis grid yang lembut
                            borderDash: [5, 5] // Garis putus-putus
                        },
                        ticks: {
                            color: '#666', // Warna label sumbu Y
                            font: {
                                size: 12
                            },
                            precision: 0 // Pastikan angka tidak memiliki desimal
                        }
                    }
                }
            }
        };

        // Render grafik
        const reportChart = new Chart(
            document.getElementById('reportChart'),
            config
        );
    </script>
</body>

</html>