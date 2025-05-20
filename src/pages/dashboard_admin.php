<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../app.php");
    exit();
}
include '../connection/conn.php';

// Mendapatkan statistik untuk dashboard
$total_animals = $conn->query("SELECT COUNT(*) as count FROM animal")->fetch_assoc()['count'];
$total_visits = $conn->query("SELECT COUNT(*) as count FROM visit")->fetch_assoc()['count'];
$total_owners = $conn->query("SELECT COUNT(*) as count FROM owners")->fetch_assoc()['count'];
$total_vets = $conn->query("SELECT COUNT(*) as count FROM vet")->fetch_assoc()['count'];
$total_clinics = $conn->query("SELECT COUNT(*) as count FROM clinic")->fetch_assoc()['count'];

// Menghitung kunjungan yang belum dibayar
$unpaid_visits = $conn->query("SELECT COUNT(*) as count FROM visit WHERE visit_status = 'Unpaid'")->fetch_assoc()['count'];

// Menghitung total pendapatan
$total_income = $conn->query("SELECT SUM(payment_amount) as total FROM payment")->fetch_assoc()['total'] ?? 0;

// Mendapatkan 5 kunjungan terbaru
$recent_visits = $conn->query("SELECT v.visit_id, v.visit_date_time, a.animal_name, CONCAT(o.owner_givenname, ' ', o.owner_familyname) as owner_name, v.visit_status 
                              FROM visit v 
                              JOIN animal a ON v.animal_id = a.animal_id 
                              JOIN owners o ON a.owner_id = o.owner_id 
                              ORDER BY v.visit_date_time DESC 
                              LIMIT 5");

// Mendapatkan stok obat yang rendah
$low_stock_meds = $conn->query("SELECT d.drug_name, i.quantity, c.clinic_name 
                               FROM inventory i 
                               JOIN drug d ON i.drug_id = d.drug_id 
                               JOIN clinic c ON i.clinic_id = c.clinic_id 
                               WHERE i.quantity <= 10 
                               LIMIT 5");

// Fungsi untuk mengambil daftar tabel dari folder models
function getModelTables($path)
{
    $tables = [];
    if (is_dir($path)) {
        $directories = scandir($path);
        foreach ($directories as $dir) {
            if ($dir !== '.' && $dir !== '..' && is_dir($path . '/' . $dir)) {
                $tables[] = $dir;
            }
        }
    }
    return $tables;
}

// Ambil daftar tabel dari folder models
$modelsPath = realpath(__DIR__ . '/../models');
$tables = getModelTables($modelsPath);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../public/assets/js/script.js" defer></script>
</head>

<body>
    <!-- Loader element yang akan hilang setelah halaman dimuat -->
    <div id="page-loader">
        <div class="loader-content">
            <div class="loader-icon">🐾</div>
            <div class="loader-text">Memuat Dashboard</div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Sahabat Satwa 🐾</h2>
            <div class="admin-profile">
                <div class="admin-avatar">
                    <span>A</span>
                </div>
                <div class="admin-info">
                    <p>Administrator</p>
                    <small><?= htmlspecialchars($_SESSION['username']) ?></small>
                </div>
            </div>
        </div>
        <div class="sidebar-menu-container">
            <h3>Menu Utama</h3>
            <ul class="sidebar-menu">
                <li class="active"><a href="dashboard_admin.php"><i class="icon">📊</i> Dashboard</a></li>
                <li><a href="#" class="toggle-tables"><i class="icon">📋</i> Data Master <span class="dropdown-icon">▼</span></a>
                    <ul class="submenu">
                        <?php foreach ($tables as $table): ?>
                            <li><a href="../models/<?= $table ?>/index.php"><?= ucfirst(str_replace('_', ' ', $table)) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li><a href="logout.php"><i class="icon">🚪</i> Logout</a></li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-title">
                <h1>Dashboard Admin</h1>
                <p>Statistik dan informasi penting</p>
            </div>
            <div class="header-actions">
                <div class="date-time">
                    <span id="current-date-time"></span>
                </div>
            </div>
        </header>

        <!-- Stat Cards Section -->
        <section class="stat-cards">
            <div class="stat-card animal-card">
                <div class="stat-icon">🐾</div>
                <div class="stat-details">
                    <h3>Hewan</h3>
                    <p class="stat-number"><?= $total_animals ?></p>
                </div>
            </div>
            <div class="stat-card owner-card">
                <div class="stat-icon">👤</div>
                <div class="stat-details">
                    <h3>Pemilik</h3>
                    <p class="stat-number"><?= $total_owners ?></p>
                </div>
            </div>
            <div class="stat-card vet-card">
                <div class="stat-icon">👨‍⚕️</div>
                <div class="stat-details">
                    <h3>Dokter Hewan</h3>
                    <p class="stat-number"><?= $total_vets ?></p>
                </div>
            </div>
            <div class="stat-card clinic-card">
                <div class="stat-icon">🏥</div>
                <div class="stat-details">
                    <h3>Klinik</h3>
                    <p class="stat-number"><?= $total_clinics ?></p>
                </div>
            </div>
            <div class="stat-card visit-card">
                <div class="stat-icon">📅</div>
                <div class="stat-details">
                    <h3>Kunjungan</h3>
                    <p class="stat-number"><?= $total_visits ?></p>
                </div>
            </div>
            <div class="stat-card income-card">
                <div class="stat-icon">💰</div>
                <div class="stat-details">
                    <h3>Pendapatan</h3>
                    <p class="stat-number">Rp <?= number_format($total_income, 0, ',', '.') ?></p>
                </div>
            </div>
        </section>

        <!-- Charts and Tables Sections -->
        <section class="dashboard-widgets">
            <div class="widget-row">
                <!-- Chart Widget -->
                <div class="widget chart-widget">
                    <div class="widget-header">
                        <h2>Statistik Kunjungan</h2>
                    </div>
                    <div class="widget-content">
                        <canvas id="visitChart"></canvas>
                    </div>
                </div>

                <!-- Low Stock Alert Widget -->
                <div class="widget stock-widget">
                    <div class="widget-header">
                        <h2>Stok Obat Rendah</h2>
                    </div>
                    <div class="widget-content">
                        <?php if ($low_stock_meds && $low_stock_meds->num_rows > 0): ?>
                            <table class="compact-table">
                                <thead>
                                    <tr>
                                        <th>Obat</th>
                                        <th>Klinik</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($med = $low_stock_meds->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($med['drug_name']) ?></td>
                                            <td><?= htmlspecialchars($med['clinic_name']) ?></td>
                                            <td class="<?= $med['quantity'] <= 5 ? 'critical-stock' : 'low-stock' ?>"><?= $med['quantity'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div class="widget-action">
                                <a href="../models/inventory/index.php" class="btn btn-sm">Lihat Semua</a>
                            </div>
                        <?php else: ?>
                            <p class="no-data">Tidak ada stok obat yang rendah</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="widget-row">
                <!-- Recent Visits Widget -->
                <div class="widget full-width">
                    <div class="widget-header">
                        <h2>Kunjungan Terbaru</h2>
                    </div>
                    <div class="widget-content">
                        <?php if ($recent_visits && $recent_visits->num_rows > 0): ?>
                            <table class="compact-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Hewan</th>
                                        <th>Pemilik</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($visit = $recent_visits->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($visit['visit_id']) ?></td>
                                            <td><?= htmlspecialchars($visit['visit_date_time']) ?></td>
                                            <td><?= htmlspecialchars($visit['animal_name']) ?></td>
                                            <td><?= htmlspecialchars($visit['owner_name']) ?></td>
                                            <td>
                                                <span class="status-badge <?= $visit['visit_status'] === 'Paid' ? 'status-paid' : 'status-unpaid' ?>">
                                                    <?= $visit['visit_status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="../models/visits/update.php?id=<?= $visit['visit_id'] ?>" class="btn btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div class="widget-action">
                                <a href="../models/visits/index.php" class="btn btn-sm">Lihat Semua</a>
                            </div>
                        <?php else: ?>
                            <p class="no-data">Tidak ada data kunjungan terbaru</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Script untuk menampilkan tanggal dan waktu saat ini
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('current-date-time').textContent = now.toLocaleDateString('id-ID', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 60000);

        // Menghilangkan loader setelah halaman dimuat
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
            }, 500);
        });

        // Toggle untuk submenu
        document.addEventListener('DOMContentLoaded', function() {
            const toggleTables = document.querySelector('.toggle-tables');
            const submenu = document.querySelector('.submenu');

            toggleTables.addEventListener('click', function(e) {
                e.preventDefault();
                submenu.classList.toggle('show');
                const dropdownIcon = this.querySelector('.dropdown-icon');
                dropdownIcon.textContent = submenu.classList.contains('show') ? '▲' : '▼';
            });

            // Chart untuk statistik kunjungan
            const ctx = document.getElementById('visitChart').getContext('2d');
            const visitChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Dibayar', 'Belum Dibayar'],
                    datasets: [{
                        data: [<?= $total_visits - $unpaid_visits ?>, <?= $unpaid_visits ?>],
                        backgroundColor: ['#4caf50', '#ff9800'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Status Pembayaran Kunjungan',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>