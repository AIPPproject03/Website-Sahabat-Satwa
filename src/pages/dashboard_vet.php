<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran vet
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'vet') {
    header("Location: ../app.php");
    exit();
}
include '../connection/conn.php';

// Ambil vet_id dan data dokter dari session
$vet_id = $_SESSION['vet_id'];
$vet_data = $conn->query("SELECT vet_title, vet_givenname, vet_familyname, vet_phone, vet_employed, clinic_id, spec_id FROM vet WHERE vet_id = $vet_id")->fetch_assoc();

// Ambil data klinik dan spesialisasi dokter
$clinic_name = '';
if ($vet_data['clinic_id']) {
    $clinic_result = $conn->query("SELECT clinic_name FROM clinic WHERE clinic_id = {$vet_data['clinic_id']}");
    if ($clinic_result && $clinic_row = $clinic_result->fetch_assoc()) {
        $clinic_name = $clinic_row['clinic_name'];
    }
}

$specialization = 'Umum';
if ($vet_data['spec_id']) {
    $spec_result = $conn->query("SELECT spec_description FROM specialisation WHERE spec_id = {$vet_data['spec_id']}");
    if ($spec_result && $spec_row = $spec_result->fetch_assoc()) {
        $specialization = $spec_row['spec_description'];
    }
}

// Hitung statistik untuk dashboard dokter
$total_visits = $conn->query("SELECT COUNT(*) as count FROM visit WHERE vet_id = $vet_id")->fetch_assoc()['count'];
$today_visits = $conn->query("SELECT COUNT(*) as count FROM visit WHERE vet_id = $vet_id AND DATE(visit_date_time) = CURDATE()")->fetch_assoc()['count'];
$upcoming_visits = $conn->query("SELECT COUNT(*) as count FROM visit WHERE vet_id = $vet_id AND visit_date_time > NOW()")->fetch_assoc()['count'];
$total_animals = $conn->query("SELECT COUNT(DISTINCT animal_id) as count FROM visit WHERE vet_id = $vet_id")->fetch_assoc()['count'];

// Query untuk mengambil kunjungan hari ini
$today_visits_data = $conn->query("
    SELECT 
        v.visit_id, 
        v.visit_date_time, 
        v.visit_notes, 
        v.visit_status,
        a.animal_name, 
        a.animal_born,
        at.at_description as animal_type,
        o.owner_givenname, 
        o.owner_familyname,
        o.owner_phone
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN animal_type at ON a.at_id = at.at_id
    JOIN owners o ON a.owner_id = o.owner_id
    WHERE v.vet_id = $vet_id 
    AND DATE(v.visit_date_time) = CURDATE()
    ORDER BY v.visit_date_time ASC
");

// Query untuk mengambil kunjungan mendatang
$upcoming_visits_data = $conn->query("
    SELECT 
        v.visit_id, 
        v.visit_date_time, 
        v.visit_notes, 
        v.visit_status,
        a.animal_name, 
        a.animal_born,
        at.at_description as animal_type,
        o.owner_givenname, 
        o.owner_familyname,
        o.owner_phone
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN animal_type at ON a.at_id = at.at_id
    JOIN owners o ON a.owner_id = o.owner_id
    WHERE v.vet_id = $vet_id 
    AND DATE(v.visit_date_time) > CURDATE()
    ORDER BY v.visit_date_time ASC
    LIMIT 5
");

// Query untuk mengambil riwayat kunjungan
$visit_history = $conn->query("
    SELECT 
        v.visit_id, 
        v.visit_date_time, 
        v.visit_notes, 
        v.visit_status,
        a.animal_name, 
        at.at_description as animal_type,
        o.owner_givenname, 
        o.owner_familyname
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN animal_type at ON a.at_id = at.at_id
    JOIN owners o ON a.owner_id = o.owner_id
    WHERE v.vet_id = $vet_id 
    AND DATE(v.visit_date_time) < CURDATE()
    ORDER BY v.visit_date_time DESC
    LIMIT 10
");

// Query untuk data obat yang sering digunakan
$common_drugs = $conn->query("
    SELECT 
        d.drug_name, 
        COUNT(vd.drug_id) as usage_count
    FROM visit_drug vd
    JOIN drug d ON vd.drug_id = d.drug_id
    JOIN visit v ON vd.visit_id = v.visit_id
    WHERE v.vet_id = $vet_id
    GROUP BY d.drug_id
    ORDER BY usage_count DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/vet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Sahabat Satwa 🐾</h2>
            <div class="vet-profile">
                <div class="vet-avatar">
                    <span><?= substr($vet_data['vet_givenname'], 0, 1) ?></span>
                </div>
                <div class="vet-info">
                    <p><?= $vet_data['vet_title'] . ' ' . $vet_data['vet_givenname'] . ' ' . $vet_data['vet_familyname'] ?></p>
                    <small><?= $specialization ?></small>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="dashboard_vet.php"><i class="icon">📊</i> Dashboard</a></li>
            <li><a href="../models/visits/create.php?role=vet"><i class="icon">➕</i> Tambah Kunjungan</a></li>
            <li><a href="../models/visit_drug/create.php?role=vet"><i class="icon">💊</i> Catat Obat</a></li>
            <li><a href="logout.php"><i class="icon">🚪</i> Logout</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><?= $clinic_name ?></p>
            <small>Bergabung sejak: <?= date('d M Y', strtotime($vet_data['vet_employed'])) ?></small>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-title">
                <h1>Dashboard Dokter</h1>
                <p>Selamat datang kembali, <?= $vet_data['vet_title'] . ' ' . $vet_data['vet_givenname'] ?></p>
            </div>
            <div class="header-actions">
                <div class="date-time">
                    <span id="current-date-time"></span>
                </div>
            </div>
        </header>

        <!-- Stat Cards -->
        <section class="stat-cards">
            <div class="stat-card">
                <div class="stat-icon visits-icon">📅</div>
                <div class="stat-details">
                    <h3>Total Kunjungan</h3>
                    <p class="stat-number"><?= $total_visits ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon today-icon">📋</div>
                <div class="stat-details">
                    <h3>Kunjungan Hari Ini</h3>
                    <p class="stat-number"><?= $today_visits ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon upcoming-icon">📆</div>
                <div class="stat-details">
                    <h3>Kunjungan Mendatang</h3>
                    <p class="stat-number"><?= $upcoming_visits ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon animal-icon">🐾</div>
                <div class="stat-details">
                    <h3>Hewan Ditangani</h3>
                    <p class="stat-number"><?= $total_animals ?></p>
                </div>
            </div>
        </section>

        <!-- Dashboard Content -->
        <section class="dashboard-widgets">
            <!-- Today's Appointments Widget -->
            <div class="widget">
                <div class="widget-header">
                    <h2>Kunjungan Hari Ini</h2>
                    <a href="../models/visits/create.php?role=vet" class="btn btn-sm">+ Tambah</a>
                </div>
                <div class="widget-content">
                    <?php if ($today_visits_data && $today_visits_data->num_rows > 0): ?>
                        <div class="appointment-list">
                            <?php while ($visit = $today_visits_data->fetch_assoc()): ?>
                                <div class="appointment-card">
                                    <div class="appointment-time">
                                        <span class="time"><?= date('H:i', strtotime($visit['visit_date_time'])) ?></span>
                                        <span class="date"><?= date('d M Y', strtotime($visit['visit_date_time'])) ?></span>
                                    </div>
                                    <div class="appointment-details">
                                        <h3><?= htmlspecialchars($visit['animal_name']) ?> (<?= htmlspecialchars($visit['animal_type']) ?>)</h3>
                                        <p class="owner">Pemilik: <?= htmlspecialchars($visit['owner_givenname'] . ' ' . $visit['owner_familyname']) ?> - <?= htmlspecialchars($visit['owner_phone']) ?></p>
                                        <p class="notes"><?= htmlspecialchars($visit['visit_notes']) ?></p>
                                        <div class="appointment-status <?= strtolower($visit['visit_status']) ?>">
                                            <?= $visit['visit_status'] ?>
                                        </div>
                                    </div>
                                    <div class="appointment-actions">
                                        <a href="../models/visits/update.php?id=<?= $visit['visit_id'] ?>&role=vet" class="btn btn-primary">Update</a>
                                        <a href="../models/visit_drug/create.php?visit_id=<?= $visit['visit_id'] ?>&role=vet" class="btn btn-secondary">Catat Obat</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-data">Tidak ada kunjungan terjadwal hari ini</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="widget-row">
                <!-- Upcoming Appointments -->
                <div class="widget half-width">
                    <div class="widget-header">
                        <h2>Kunjungan Mendatang</h2>
                    </div>
                    <div class="widget-content">
                        <?php if ($upcoming_visits_data && $upcoming_visits_data->num_rows > 0): ?>
                            <table class="compact-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Hewan</th>
                                        <th>Pemilik</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($visit = $upcoming_visits_data->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <strong><?= date('d M Y', strtotime($visit['visit_date_time'])) ?></strong><br>
                                                <small><?= date('H:i', strtotime($visit['visit_date_time'])) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($visit['animal_name']) ?><br><small><?= htmlspecialchars($visit['animal_type']) ?></small></td>
                                            <td><?= htmlspecialchars($visit['owner_givenname'] . ' ' . $visit['owner_familyname']) ?></td>
                                            <td>
                                                <a href="../models/visits/update.php?id=<?= $visit['visit_id'] ?>&role=vet" class="btn btn-sm">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="no-data">Tidak ada kunjungan mendatang</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Common Medications -->
                <div class="widget half-width">
                    <div class="widget-header">
                        <h2>Obat yang Sering Diresepkan</h2>
                    </div>
                    <div class="widget-content">
                        <?php if ($common_drugs && $common_drugs->num_rows > 0): ?>
                            <div class="chart-container">
                                <canvas id="drugChart"></canvas>
                            </div>
                        <?php else: ?>
                            <p class="no-data">Belum ada data obat yang diresepkan</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Visit History -->
            <div class="widget">
                <div class="widget-header">
                    <h2>Riwayat Kunjungan</h2>
                </div>
                <div class="widget-content">
                    <?php if ($visit_history && $visit_history->num_rows > 0): ?>
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Hewan</th>
                                    <th>Pemilik</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($visit = $visit_history->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($visit['visit_date_time'])) ?></td>
                                        <td><?= htmlspecialchars($visit['animal_name']) ?><br><small><?= htmlspecialchars($visit['animal_type']) ?></small></td>
                                        <td><?= htmlspecialchars($visit['owner_givenname'] . ' ' . $visit['owner_familyname']) ?></td>
                                        <td><?= htmlspecialchars(substr($visit['visit_notes'], 0, 50)) . (strlen($visit['visit_notes']) > 50 ? '...' : '') ?></td>
                                        <td><span class="status-badge <?= strtolower($visit['visit_status']) ?>"><?= $visit['visit_status'] ?></span></td>
                                        <td>
                                            <a href="../models/visits/update.php?id=<?= $visit['visit_id'] ?>&role=vet" class="btn btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">Belum ada riwayat kunjungan</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Update current date and time
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

        // Chart for drug usage
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($common_drugs && $common_drugs->num_rows > 0): ?>
                const ctx = document.getElementById('drugChart').getContext('2d');

                // Prepare data for chart
                const labels = [];
                const data = [];
                const backgroundColors = [
                    '#4caf50', '#2196f3', '#ff9800', '#e91e63', '#9c27b0'
                ];

                <?php
                $index = 0;
                while ($drug = $common_drugs->fetch_assoc()):
                ?>
                    labels.push('<?= $drug['drug_name'] ?>');
                    data.push(<?= $drug['usage_count'] ?>);
                <?php
                    $index++;
                endwhile;
                ?>

                const drugChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Penggunaan',
                            data: data,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>