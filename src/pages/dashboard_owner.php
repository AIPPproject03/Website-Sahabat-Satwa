<?php
// filepath: d:\AIPPROJECT03\TUGAS WEB\Website Sahabat Satwa\src\pages\dashboard_owner.php
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran owner
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../app.php");
    exit();
}
include '../connection/conn.php';

// Ambil owner_id dari session
$owner_id = $_SESSION['owner_id'];

// Dapatkan data pemilik
$owner_data = $conn->query("SELECT * FROM owners WHERE owner_id = $owner_id")->fetch_assoc();

// Dapatkan semua hewan milik owner
$animals = $conn->query("
    SELECT 
        a.animal_id, 
        a.animal_name, 
        a.animal_born, 
        at.at_description 
    FROM animal a
    JOIN animal_type at ON a.at_id = at.at_id
    WHERE a.owner_id = $owner_id
    ORDER BY a.animal_name ASC
");

// Hitung jumlah hewan
$total_animals = $conn->query("SELECT COUNT(*) as count FROM animal WHERE owner_id = $owner_id")->fetch_assoc()['count'];

// Dapatkan kunjungan terbaru (terlepas dari status pembayaran)
$recent_visits = $conn->query("
    SELECT 
        v.visit_id, 
        v.visit_date_time, 
        v.visit_notes, 
        v.visit_status,
        a.animal_name, 
        a.animal_id,
        vet.vet_title,
        vet.vet_givenname, 
        vet.vet_familyname,
        c.clinic_name
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN vet ON v.vet_id = vet.vet_id
    JOIN clinic c ON vet.clinic_id = c.clinic_id
    WHERE a.owner_id = $owner_id
    ORDER BY v.visit_date_time DESC
    LIMIT 5
");

// Hitung jumlah kunjungan
$total_visits = $conn->query("
    SELECT COUNT(*) as count 
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    WHERE a.owner_id = $owner_id
")->fetch_assoc()['count'];

// Dapatkan kunjungan yang belum dibayar
$unpaid_visits = $conn->query("
    SELECT 
        v.visit_id, 
        v.visit_date_time, 
        v.visit_notes, 
        a.animal_name,
        vet.vet_givenname, 
        vet.vet_familyname,
        c.clinic_name,
        COALESCE(ROUND(SUM(d.price * vd.visit_drug_qtysupplied) + 150000), 150000) as total_amount
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN vet ON v.vet_id = vet.vet_id
    JOIN clinic c ON vet.clinic_id = c.clinic_id
    LEFT JOIN visit_drug vd ON v.visit_id = vd.visit_id
    LEFT JOIN drug d ON vd.drug_id = d.drug_id
    WHERE a.owner_id = $owner_id AND v.visit_status = 'Unpaid'
    GROUP BY v.visit_id, v.visit_date_time, v.visit_notes, a.animal_name, vet.vet_givenname, vet.vet_familyname, c.clinic_name
    ORDER BY v.visit_date_time DESC
");

// Dapatkan data pembayaran yang sudah dibayar (receipt)
$payments = $conn->query("
    SELECT 
        r.receipt_id,
        r.receipt_number,
        r.issue_date,
        r.total_amount,
        p.payment_method,
        p.payment_date,
        v.visit_id,
        v.visit_date_time,
        a.animal_name,
        vet.vet_title,
        vet.vet_givenname, 
        vet.vet_familyname,
        c.clinic_name
    FROM receipt r
    JOIN payment p ON r.payment_id = p.payment_id
    JOIN visit v ON p.visit_id = v.visit_id
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN vet ON v.vet_id = vet.vet_id
    JOIN clinic c ON vet.clinic_id = c.clinic_id
    WHERE a.owner_id = $owner_id
    ORDER BY r.issue_date DESC
");

// Dapatkan data riwayat obat
$medications = $conn->query("
    SELECT 
        vd.visit_id,
        v.visit_date_time,
        a.animal_name,
        d.drug_name,
        d.drug_usage,
        vd.visit_drug_dose,
        vd.visit_drug_frequency,
        vd.visit_drug_qtysupplied,
        d.price,
        (d.price * vd.visit_drug_qtysupplied) as total_price
    FROM visit_drug vd
    JOIN drug d ON vd.drug_id = d.drug_id
    JOIN visit v ON vd.visit_id = v.visit_id
    JOIN animal a ON v.animal_id = a.animal_id
    WHERE a.owner_id = $owner_id
    ORDER BY v.visit_date_time DESC
");

// Hitung total yang sudah dibayar
$total_paid = $conn->query("
    SELECT SUM(r.total_amount) as total
    FROM receipt r
    JOIN payment p ON r.payment_id = p.payment_id
    JOIN visit v ON p.visit_id = v.visit_id
    JOIN animal a ON v.animal_id = a.animal_id
    WHERE a.owner_id = $owner_id
")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/owner.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Sahabat Satwa 🐾</h2>
            <div class="owner-profile">
                <div class="owner-avatar">
                    <span><?= substr($owner_data['owner_givenname'], 0, 1) ?></span>
                </div>
                <div class="owner-info">
                    <p><?= $owner_data['owner_givenname'] . ' ' . $owner_data['owner_familyname'] ?></p>
                    <small>Pemilik Hewan</small>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="#dashboard"><i class="icon">📊</i> Dashboard</a></li>
            <li><a href="#animals"><i class="icon">🐾</i> Hewan Saya</a></li>
            <li><a href="#visits"><i class="icon">🩺</i> Riwayat Kunjungan</a></li>
            <li><a href="#medications"><i class="icon">💊</i> Riwayat Obat</a></li>
            <li><a href="#payments"><i class="icon">💰</i> Pembayaran</a></li>
            <li><a href="profile.php"><i class="icon">👤</i> Profil Saya</a></li>
            <li><a href="logout.php"><i class="icon">🚪</i> Logout</a></li>
        </ul>
        <div class="sidebar-footer">
            <p>Untuk konsultasi:</p>
            <small>+62 812-3456-7890</small>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-title">
                <h1>Dashboard Pemilik Hewan</h1>
                <p>Selamat datang, <?= $owner_data['owner_givenname'] ?>!</p>
            </div>
            <div class="header-actions">
                <div class="date-time">
                    <span id="current-date-time"></span>
                </div>
            </div>
        </header>

        <!-- Stat Cards -->
        <section class="stat-cards">
            <div class="stat-card animal-card">
                <div class="stat-icon">🐾</div>
                <div class="stat-details">
                    <h3>Hewan Saya</h3>
                    <p class="stat-number"><?= $total_animals ?></p>
                </div>
            </div>
            <div class="stat-card visit-card">
                <div class="stat-icon">🩺</div>
                <div class="stat-details">
                    <h3>Total Kunjungan</h3>
                    <p class="stat-number"><?= $total_visits ?></p>
                </div>
            </div>
            <div class="stat-card unpaid-card">
                <div class="stat-icon">📝</div>
                <div class="stat-details">
                    <h3>Belum Dibayar</h3>
                    <p class="stat-number"><?= $unpaid_visits->num_rows ?></p>
                </div>
            </div>
            <div class="stat-card payment-card">
                <div class="stat-icon">💰</div>
                <div class="stat-details">
                    <h3>Total Pembayaran</h3>
                    <p class="stat-number">Rp <?= number_format($total_paid, 0, ',', '.') ?></p>
                </div>
            </div>
        </section>

        <!-- Dashboard Content -->
        <section id="dashboard" class="dashboard-widgets">
            <!-- Hewan Milik Saya -->
            <div class="widget-row">
                <div class="widget half-width">
                    <div class="widget-header">
                        <h2>Hewan Saya</h2>
                    </div>
                    <div class="widget-content">
                        <?php if ($animals && $animals->num_rows > 0): ?>
                            <div class="pet-cards">
                                <?php while ($animal = $animals->fetch_assoc()): ?>
                                    <div class="pet-card">
                                        <div class="pet-icon">
                                            <?php
                                            $animalType = strtolower($animal['at_description']);
                                            if (strpos($animalType, 'kucing') !== false) {
                                                echo '🐱';
                                            } elseif (strpos($animalType, 'anjing') !== false) {
                                                echo '🐕';
                                            } elseif (strpos($animalType, 'burung') !== false) {
                                                echo '🦜';
                                            } elseif (strpos($animalType, 'ikan') !== false) {
                                                echo '🐠';
                                            } elseif (strpos($animalType, 'kelinci') !== false) {
                                                echo '🐰';
                                            } else {
                                                echo '🐾';
                                            }
                                            ?>
                                        </div>
                                        <div class="pet-details">
                                            <h3><?= htmlspecialchars($animal['animal_name']) ?></h3>
                                            <p><?= htmlspecialchars($animal['at_description']) ?></p>
                                            <p>Lahir: <?= date('d M Y', strtotime($animal['animal_born'])) ?></p>
                                            <p>Usia: <?= floor((time() - strtotime($animal['animal_born'])) / (60 * 60 * 24 * 365)) ?> tahun</p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data">Anda belum memiliki hewan terdaftar.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Kunjungan Terbaru -->
                <div class="widget half-width">
                    <div class="widget-header">
                        <h2>Kunjungan Terbaru</h2>
                    </div>
                    <div class="widget-content">
                        <?php if ($recent_visits && $recent_visits->num_rows > 0): ?>
                            <div class="visit-list">
                                <?php while ($visit = $recent_visits->fetch_assoc()): ?>
                                    <div class="visit-item">
                                        <div class="visit-date">
                                            <span class="date"><?= date('d', strtotime($visit['visit_date_time'])) ?></span>
                                            <span class="month"><?= date('M', strtotime($visit['visit_date_time'])) ?></span>
                                        </div>
                                        <div class="visit-info">
                                            <h3><?= htmlspecialchars($visit['animal_name']) ?></h3>
                                            <p><strong>Dokter:</strong> <?= htmlspecialchars($visit['vet_title'] . ' ' . $visit['vet_givenname'] . ' ' . $visit['vet_familyname']) ?></p>
                                            <p><strong>Klinik:</strong> <?= htmlspecialchars($visit['clinic_name']) ?></p>
                                            <p class="visit-notes"><?= htmlspecialchars($visit['visit_notes']) ?></p>
                                        </div>
                                        <div class="visit-status-badge <?= strtolower($visit['visit_status']) ?>">
                                            <?= $visit['visit_status'] ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data">Tidak ada kunjungan terbaru.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tagihan Belum Dibayar -->
            <div class="widget">
                <div class="widget-header">
                    <h2>Tagihan Belum Dibayar</h2>
                </div>
                <div class="widget-content">
                    <?php if ($unpaid_visits && $unpaid_visits->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Hewan</th>
                                    <th>Dokter</th>
                                    <th>Klinik</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($visit = $unpaid_visits->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($visit['visit_date_time'])) ?></td>
                                        <td><?= htmlspecialchars($visit['animal_name']) ?></td>
                                        <td><?= htmlspecialchars($visit['vet_givenname'] . ' ' . $visit['vet_familyname']) ?></td>
                                        <td><?= htmlspecialchars($visit['clinic_name']) ?></td>
                                        <td class="price">Rp <?= number_format($visit['total_amount'], 0, ',', '.') ?></td>
                                        <td>
                                            <a href="payment.php?visit_id=<?= $visit['visit_id'] ?>" class="btn btn-primary btn-sm">Bayar</a>
                                            <a href="visit_detail.php?id=<?= $visit['visit_id'] ?>" class="btn btn-secondary btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">Tidak ada tagihan yang belum dibayar.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Animals Section -->
        <section id="animals" class="section-hidden dashboard-section">
            <div class="section-header">
                <h2>Hewan Peliharaan Saya</h2>
            </div>
            <div class="section-content">
                <?php if ($animals && $animals->num_rows > 0):
                    // Reset pointer to start
                    $animals->data_seek(0);
                ?>
                    <div class="animal-grid">
                        <?php while ($animal = $animals->fetch_assoc()): ?>
                            <div class="animal-card">
                                <div class="animal-header">
                                    <h3><?= htmlspecialchars($animal['animal_name']) ?></h3>
                                    <span class="animal-type"><?= htmlspecialchars($animal['at_description']) ?></span>
                                </div>
                                <div class="animal-body">
                                    <div class="animal-image">
                                        <?php
                                        $animalType = strtolower($animal['at_description']);
                                        if (strpos($animalType, 'kucing') !== false) {
                                            echo '🐱';
                                        } elseif (strpos($animalType, 'anjing') !== false) {
                                            echo '🐕';
                                        } elseif (strpos($animalType, 'burung') !== false) {
                                            echo '🦜';
                                        } elseif (strpos($animalType, 'ikan') !== false) {
                                            echo '🐠';
                                        } elseif (strpos($animalType, 'kelinci') !== false) {
                                            echo '🐰';
                                        } else {
                                            echo '🐾';
                                        }
                                        ?>
                                    </div>
                                    <div class="animal-details">
                                        <p><strong>Tanggal Lahir:</strong> <?= date('d M Y', strtotime($animal['animal_born'])) ?></p>
                                        <p><strong>Usia:</strong> <?= floor((time() - strtotime($animal['animal_born'])) / (60 * 60 * 24 * 365)) ?> tahun</p>

                                        <?php
                                        // Dapatkan kunjungan terakhir untuk hewan ini
                                        $lastVisit = $conn->query("
                                            SELECT 
                                                v.visit_date_time, 
                                                v.visit_status,
                                                vet.vet_givenname, 
                                                vet.vet_familyname
                                            FROM visit v
                                            JOIN vet ON v.vet_id = vet.vet_id
                                            WHERE v.animal_id = {$animal['animal_id']}
                                            ORDER BY v.visit_date_time DESC
                                            LIMIT 1
                                        ")->fetch_assoc();

                                        if ($lastVisit) {
                                            echo "<p><strong>Kunjungan Terakhir:</strong> " . date('d M Y', strtotime($lastVisit['visit_date_time'])) . "</p>";
                                            echo "<p><strong>Dokter:</strong> " . htmlspecialchars($lastVisit['vet_givenname'] . ' ' . $lastVisit['vet_familyname']) . "</p>";
                                            echo "<p><strong>Status:</strong> " .
                                                '<span class="status-badge ' . strtolower($lastVisit['visit_status']) . '">' .
                                                $lastVisit['visit_status'] . '</span></p>';
                                        } else {
                                            echo "<p><strong>Kunjungan Terakhir:</strong> Tidak ada</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="animal-footer">
                                    <a href="animal_detail.php?id=<?= $animal['animal_id'] ?>" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="no-data">Anda belum memiliki hewan terdaftar.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Visits Section -->
        <section id="visits" class="section-hidden dashboard-section">
            <div class="section-header">
                <h2>Riwayat Kunjungan</h2>
            </div>
            <div class="section-content">
                <?php
                $all_visits = $conn->query("
                    SELECT 
                        v.visit_id, 
                        v.visit_date_time, 
                        v.visit_notes, 
                        v.visit_status,
                        a.animal_id,
                        a.animal_name, 
                        vet.vet_title,
                        vet.vet_givenname, 
                        vet.vet_familyname,
                        c.clinic_name,
                        COALESCE(ROUND(SUM(d.price * vd.visit_drug_qtysupplied) + 150000), 150000) as total_amount
                    FROM visit v
                    JOIN animal a ON v.animal_id = a.animal_id
                    JOIN vet ON v.vet_id = vet.vet_id
                    JOIN clinic c ON vet.clinic_id = c.clinic_id
                    LEFT JOIN visit_drug vd ON v.visit_id = vd.visit_id
                    LEFT JOIN drug d ON vd.drug_id = d.drug_id
                    WHERE a.owner_id = $owner_id
                    GROUP BY v.visit_id, v.visit_date_time, v.visit_notes, v.visit_status, a.animal_id, a.animal_name, 
                             vet.vet_title, vet.vet_givenname, vet.vet_familyname, c.clinic_name
                    ORDER BY v.visit_date_time DESC
                ");
                ?>

                <?php if ($all_visits && $all_visits->num_rows > 0): ?>
                    <div class="visits-timeline">
                        <?php while ($visit = $all_visits->fetch_assoc()): ?>
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <div class="date-circle">
                                        <span class="date-day"><?= date('d', strtotime($visit['visit_date_time'])) ?></span>
                                        <span class="date-month"><?= date('M', strtotime($visit['visit_date_time'])) ?></span>
                                    </div>
                                    <div class="date-year"><?= date('Y', strtotime($visit['visit_date_time'])) ?></div>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-card">
                                        <div class="timeline-header">
                                            <h3><?= htmlspecialchars($visit['animal_name']) ?></h3>
                                            <span class="status-badge <?= strtolower($visit['visit_status']) ?>"><?= $visit['visit_status'] ?></span>
                                        </div>
                                        <div class="timeline-body">
                                            <p><strong>Dokter:</strong> <?= htmlspecialchars($visit['vet_title'] . ' ' . $visit['vet_givenname'] . ' ' . $visit['vet_familyname']) ?></p>
                                            <p><strong>Klinik:</strong> <?= htmlspecialchars($visit['clinic_name']) ?></p>
                                            <p><strong>Catatan:</strong> <?= htmlspecialchars($visit['visit_notes']) ?></p>

                                            <?php
                                            // Dapatkan obat yang diberikan pada kunjungan ini
                                            $visit_drugs = $conn->query("
                                                SELECT 
                                                    d.drug_name,
                                                    vd.visit_drug_dose,
                                                    vd.visit_drug_frequency,
                                                    vd.visit_drug_qtysupplied,
                                                    d.price,
                                                    (d.price * vd.visit_drug_qtysupplied) as total_price
                                                FROM visit_drug vd
                                                JOIN drug d ON vd.drug_id = d.drug_id
                                                WHERE vd.visit_id = {$visit['visit_id']}
                                            ");

                                            if ($visit_drugs && $visit_drugs->num_rows > 0) {
                                                echo "<div class='medications-list'>";
                                                echo "<h4>Obat yang Diberikan:</h4>";
                                                echo "<ul>";
                                                while ($drug = $visit_drugs->fetch_assoc()) {
                                                    echo "<li>";
                                                    echo "<strong>" . htmlspecialchars($drug['drug_name']) . "</strong> - ";
                                                    echo htmlspecialchars($drug['visit_drug_dose']) . " ";
                                                    echo htmlspecialchars($drug['visit_drug_frequency']) . " ";
                                                    echo "(" . htmlspecialchars($drug['visit_drug_qtysupplied']) . " unit)";
                                                    echo " - Rp " . number_format($drug['total_price'], 0, ',', '.');
                                                    echo "</li>";
                                                }
                                                echo "</ul>";
                                                echo "</div>";
                                            }
                                            ?>

                                            <div class="visit-total">
                                                <p><strong>Biaya Konsultasi:</strong> Rp 150.000</p>
                                                <p><strong>Total Biaya:</strong> Rp <?= number_format($visit['total_amount'], 0, ',', '.') ?></p>
                                            </div>
                                        </div>
                                        <div class="timeline-footer">
                                            <?php if ($visit['visit_status'] === 'Unpaid'): ?>
                                                <a href="payment.php?visit_id=<?= $visit['visit_id'] ?>" class="btn btn-primary">Bayar Sekarang</a>
                                            <?php else: ?>
                                                <a href="receipt.php?visit_id=<?= $visit['visit_id'] ?>" class="btn btn-secondary">Lihat Kwitansi</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="no-data">Belum ada riwayat kunjungan.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Medications Section -->
        <section id="medications" class="section-hidden dashboard-section">
            <div class="section-header">
                <h2>Riwayat Obat</h2>
            </div>
            <div class="section-content">
                <?php if ($medications && $medications->num_rows > 0): ?>
                    <table class="data-table medications-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Hewan</th>
                                <th>Obat</th>
                                <th>Dosis</th>
                                <th>Frekuensi</th>
                                <th>Jumlah</th>
                                <th>Harga/Unit</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($med = $medications->fetch_assoc()): ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($med['visit_date_time'])) ?></td>
                                    <td><?= htmlspecialchars($med['animal_name']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($med['drug_name']) ?></strong>
                                        <div class="medication-usage"><?= htmlspecialchars($med['drug_usage']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($med['visit_drug_dose']) ?></td>
                                    <td><?= htmlspecialchars($med['visit_drug_frequency']) ?></td>
                                    <td><?= htmlspecialchars($med['visit_drug_qtysupplied']) ?> unit</td>
                                    <td class="price">Rp <?= number_format($med['price'], 0, ',', '.') ?></td>
                                    <td class="price">Rp <?= number_format($med['total_price'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Belum ada riwayat obat.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Payments Section -->
        <section id="payments" class="section-hidden dashboard-section">
            <div class="section-header">
                <h2>Riwayat Pembayaran</h2>
            </div>
            <div class="section-content">
                <?php if ($payments && $payments->num_rows > 0): ?>
                    <div class="receipts-list">
                        <?php while ($payment = $payments->fetch_assoc()): ?>
                            <div class="receipt-card">
                                <div class="receipt-header">
                                    <div class="receipt-number">
                                        <h3><?= htmlspecialchars($payment['receipt_number']) ?></h3>
                                        <span class="receipt-date"><?= date('d M Y', strtotime($payment['issue_date'])) ?></span>
                                    </div>
                                    <div class="receipt-amount">
                                        <span class="amount-label">Total</span>
                                        <span class="amount-value">Rp <?= number_format($payment['total_amount'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                                <div class="receipt-body">
                                    <div class="receipt-info">
                                        <p><strong>Kunjungan:</strong> <?= date('d M Y', strtotime($payment['visit_date_time'])) ?></p>
                                        <p><strong>Hewan:</strong> <?= htmlspecialchars($payment['animal_name']) ?></p>
                                        <p><strong>Dokter:</strong> <?= htmlspecialchars($payment['vet_title'] . ' ' . $payment['vet_givenname'] . ' ' . $payment['vet_familyname']) ?></p>
                                        <p><strong>Klinik:</strong> <?= htmlspecialchars($payment['clinic_name']) ?></p>
                                    </div>
                                    <div class="receipt-payment">
                                        <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($payment['payment_method']) ?></p>
                                        <p><strong>Tanggal Bayar:</strong> <?= date('d M Y', strtotime($payment['payment_date'])) ?></p>
                                    </div>
                                </div>
                                <div class="receipt-footer">
                                    <a href="receipt_detail.php?id=<?= $payment['receipt_id'] ?>" class="btn btn-primary">Lihat Detail</a>
                                    <a href="receipt_print.php?id=<?= $payment['receipt_id'] ?>" class="btn btn-secondary">Cetak Kwitansi</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="no-data">Belum ada riwayat pembayaran.</p>
                <?php endif; ?>
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

        // Navigation between sections
        document.addEventListener('DOMContentLoaded', function() {
            const menuLinks = document.querySelectorAll('.sidebar-menu a');
            const sections = document.querySelectorAll('.dashboard-section, .dashboard-widgets');

            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();

                        // Remove active class from all links
                        menuLinks.forEach(l => l.parentElement.classList.remove('active'));

                        // Add active class to clicked link
                        this.parentElement.classList.add('active');

                        // Hide all sections
                        sections.forEach(section => {
                            section.classList.add('section-hidden');
                        });

                        // Show the target section
                        const targetId = this.getAttribute('href');
                        if (targetId === '#dashboard') {
                            document.getElementById('dashboard').classList.remove('section-hidden');
                        } else {
                            document.querySelector(targetId).classList.remove('section-hidden');
                        }
                    }
                });
            });

            // Show dashboard by default
            document.getElementById('dashboard').classList.remove('section-hidden');
        });
    </script>
</body>

</html>