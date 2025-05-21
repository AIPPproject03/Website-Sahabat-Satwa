<?php
session_start();
include '../connection/conn.php';

// Periksa apakah pengguna sudah login dan memiliki peran cashier
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'cashier') {
    header("Location: ../app.php");
    exit();
}

// Ambil pesan sukses atau error dari session
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;

// Hapus pesan dari session setelah ditampilkan
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Ambil cashier_id dan data kasir dari session
$cashier_id = $_SESSION['cashier_id'];
$cashier_data = $conn->query("SELECT cashier_name, cashier_username, clinic_id, is_active FROM cashier WHERE cashier_id = $cashier_id")->fetch_assoc();

// Ambil data klinik tempat kasir bekerja
$clinic_name = '';
if ($cashier_data['clinic_id']) {
    $clinic_result = $conn->query("SELECT clinic_name, clinic_address FROM clinic WHERE clinic_id = {$cashier_data['clinic_id']}");
    if ($clinic_result && $clinic_row = $clinic_result->fetch_assoc()) {
        $clinic_name = $clinic_row['clinic_name'];
        $clinic_address = $clinic_row['clinic_address'];
    }
}

// Ambil statistik untuk dashboard kasir
// Total pembayaran yang diproses
$total_payments = $conn->query("SELECT COUNT(*) as count FROM payment WHERE cashier_id = $cashier_id")->fetch_assoc()['count'];

// Total pembayaran hari ini
$today_payments = $conn->query("SELECT COUNT(*) as count FROM payment WHERE cashier_id = $cashier_id AND DATE(payment_date) = CURDATE()")->fetch_assoc()['count'];

// Total pendapatan yang diproses
$total_income = $conn->query("SELECT SUM(payment_amount) as total FROM payment WHERE cashier_id = $cashier_id")->fetch_assoc()['total'] ?? 0;

// Total pendapatan hari ini
$today_income = $conn->query("SELECT SUM(payment_amount) as total FROM payment WHERE cashier_id = $cashier_id AND DATE(payment_date) = CURDATE()")->fetch_assoc()['total'] ?? 0;

// Kunjungan yang belum dibayar
$unpaid_visits = $conn->query("
    SELECT 
        v.visit_id, 
        v.visit_date_time, 
        a.animal_name, 
        o.owner_givenname, 
        o.owner_familyname,
        o.owner_phone,
        calculate_total_payment(v.visit_id) as total_amount,
        DATEDIFF(CURDATE(), v.visit_date_time) as days_outstanding
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN owners o ON a.owner_id = o.owner_id
    JOIN vet ON v.vet_id = vet.vet_id
    WHERE v.visit_status = 'Unpaid' 
    AND vet.clinic_id = {$cashier_data['clinic_id']}
    ORDER BY v.visit_date_time DESC
");

// 5 pembayaran terbaru
$recent_payments = $conn->query("
    SELECT 
        p.payment_id,
        p.payment_date,
        p.payment_amount,
        p.payment_method,
        v.visit_id,
        a.animal_name,
        o.owner_givenname,
        o.owner_familyname,
        r.receipt_number
    FROM payment p
    JOIN visit v ON p.visit_id = v.visit_id
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN owners o ON a.owner_id = o.owner_id
    LEFT JOIN receipt r ON p.payment_id = r.payment_id
    WHERE p.cashier_id = $cashier_id
    ORDER BY p.payment_date DESC
    LIMIT 5
");


// Metode pembayaran populer (untuk chart)
$payment_methods = $conn->query("
    SELECT 
        payment_method, 
        COUNT(*) as count 
    FROM payment 
    WHERE cashier_id = $cashier_id 
    GROUP BY payment_method 
    ORDER BY count DESC
");

// Pendapatan per bulan (untuk chart)
$monthly_income = $conn->query("
    SELECT 
        DATE_FORMAT(payment_date, '%Y-%m') as month,
        SUM(payment_amount) as total
    FROM payment
    WHERE cashier_id = $cashier_id
    AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
    ORDER BY month ASC
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/cashier.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Sahabat Satwa 🐾</h2>
            <div class="cashier-profile">
                <div class="cashier-avatar">
                    <span><?= substr($cashier_data['cashier_name'], 0, 1) ?></span>
                </div>
                <div class="cashier-info">
                    <p><?= $cashier_data['cashier_name'] ?></p>
                    <small>Kasir</small>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="dashboard_cashier.php"><i class="icon">📊</i> Dashboard</a></li>
            <li><a href="../models/payment/create_quick.php"><i class="icon">💰</i> Proses Pembayaran</a></li>
            <li><a href="#pending-payments"><i class="icon">⏱️</i> Pembayaran Tertunda</a></li>
            <li><a href="../models/payment/index.php?role=cashier"><i class="icon">💼</i> Riwayat Pembayaran</a></li>
            <li><a href="../models/receipt/index.php?role=cashier"><i class="icon">🧾</i> Daftar Kwitansi</a></li>
            <li><a href="logout.php"><i class="icon">🚪</i> Logout</a></li>
        </ul>
        <div class="sidebar-footer">
            <p><?= $clinic_name ?></p>
            <small><?= $clinic_address ?></small>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-title">
                <h1>Dashboard Kasir</h1>
                <p>Selamat datang, <?= $cashier_data['cashier_name'] ?>!</p>
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
                <div class="stat-icon payments-icon">💰</div>
                <div class="stat-details">
                    <h3>Total Pembayaran</h3>
                    <p class="stat-number"><?= $total_payments ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon today-icon">📅</div>
                <div class="stat-details">
                    <h3>Pembayaran Hari Ini</h3>
                    <p class="stat-number"><?= $today_payments ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon income-icon">💵</div>
                <div class="stat-details">
                    <h3>Total Pendapatan</h3>
                    <p class="stat-number">Rp <?= number_format($total_income, 0, ',', '.') ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon today-income-icon">📊</div>
                <div class="stat-details">
                    <h3>Pendapatan Hari Ini</h3>
                    <p class="stat-number">Rp <?= number_format($today_income, 0, ',', '.') ?></p>
                </div>
            </div>
        </section>

        <!-- Dashboard Content -->
        <section class="dashboard-widgets">
            <div class="widget-row">
                <!-- Quick Payment Entry -->
                <div class="widget half-width">
                    <div class="widget-header">
                        <h2>Proses Pembayaran Cepat</h2>
                    </div>
                    <div class="widget-content quick-payment">
                        <form action="../models/payment/process_quick.php" method="POST" class="payment-form">
                            <div class="form-group">
                                <label for="visit_id">ID Kunjungan:</label>
                                <input type="text" id="visit_id" name="visit_id" placeholder="Masukkan ID Kunjungan" required>
                                <button type="button" id="check-button" class="btn btn-secondary">Cek</button>
                            </div>

                            <div id="visit-details" class="hidden">
                                <div class="visit-info">
                                    <p><strong>Pasien:</strong> <span id="animal-name">-</span></p>
                                    <p><strong>Pemilik:</strong> <span id="owner-name">-</span></p>
                                    <p><strong>Tanggal Kunjungan:</strong> <span id="visit-date">-</span></p>
                                    <p><strong>Total Bayar:</strong> <span id="total-amount">-</span></p>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Metode Pembayaran:</label>
                                    <select id="payment_method" name="payment_method" required>
                                        <option value="Cash">Tunai</option>
                                        <option value="Credit Card">Kartu Kredit</option>
                                        <option value="Debit Card">Kartu Debit</option>
                                        <option value="Transfer">Transfer Bank</option>
                                    </select>
                                </div>

                                <input type="hidden" id="payment_amount" name="payment_amount">
                                <input type="hidden" name="cashier_id" value="<?= $cashier_id ?>">

                                <div class="actions">
                                    <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Methods Chart -->
                <div class="widget half-width">
                    <div class="widget-header">
                        <h2>Metode Pembayaran</h2>
                    </div>
                    <div class="widget-content">
                        <div class="chart-container">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-row">
                <!-- Monthly Income Chart -->
                <div class="widget">
                    <div class="widget-header">
                        <h2>Pendapatan Bulanan</h2>
                    </div>
                    <div class="widget-content">
                        <div class="chart-container">
                            <canvas id="monthlyIncomeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unpaid Visits Section -->
            <div class="widget" id="pending-payments">
                <div class="widget-header">
                    <h2>Pembayaran Tertunda</h2>
                    <span class="badge"><?= $unpaid_visits->num_rows ?></span>
                </div>
                <div class="widget-content">
                    <?php if ($unpaid_visits && $unpaid_visits->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Hewan</th>
                                    <th>Pemilik</th>
                                    <th>Kontak</th>
                                    <th>Total</th>
                                    <th>Outstanding</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($visit = $unpaid_visits->fetch_assoc()): ?>
                                    <tr class="<?= $visit['days_outstanding'] > 30 ? 'overdue' : '' ?>">
                                        <td><?= $visit['visit_id'] ?></td>
                                        <td><?= date('d M Y', strtotime($visit['visit_date_time'])) ?></td>
                                        <td><?= htmlspecialchars($visit['animal_name']) ?></td>
                                        <td><?= htmlspecialchars($visit['owner_givenname'] . ' ' . $visit['owner_familyname']) ?></td>
                                        <td><?= htmlspecialchars($visit['owner_phone']) ?></td>
                                        <td>Rp <?= number_format($visit['total_amount'], 0, ',', '.') ?></td>
                                        <td>
                                            <?= $visit['days_outstanding'] ?> hari
                                            <?php if ($visit['days_outstanding'] > 30): ?>
                                                <span class="overdue-badge">Terlambat</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="../models/payment/create.php?visit_id=<?= $visit['visit_id'] ?>&role=cashier" class="btn btn-primary btn-sm">Proses</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">Tidak ada pembayaran tertunda.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="widget">
                <div class="widget-header">
                    <h2>Pembayaran Terbaru</h2>
                </div>
                <div class="widget-content">
                    <?php if ($recent_payments && $recent_payments->num_rows > 0): ?>
                        <div class="payment-cards">
                            <?php while ($payment = $recent_payments->fetch_assoc()): ?>
                                <div class="payment-card">
                                    <div class="payment-header">
                                        <div class="payment-date">
                                            <?= date('d M Y', strtotime($payment['payment_date'])) ?>
                                        </div>
                                        <div class="payment-amount">
                                            Rp <?= number_format($payment['payment_amount'], 0, ',', '.') ?>
                                        </div>
                                    </div>
                                    <div class="payment-details">
                                        <p><strong>ID Pembayaran:</strong> <?= $payment['payment_id'] ?></p>
                                        <p><strong>Kunjungan ID:</strong> <?= $payment['visit_id'] ?></p>
                                        <p><strong>Pasien:</strong> <?= htmlspecialchars($payment['animal_name']) ?></p>
                                        <p><strong>Pemilik:</strong> <?= htmlspecialchars($payment['owner_givenname'] . ' ' . $payment['owner_familyname']) ?></p>
                                        <p><strong>Metode:</strong> <?= $payment['payment_method'] ?></p>
                                        <?php if ($payment['receipt_number']): ?>
                                            <p><strong>No. Kwitansi:</strong> <?= $payment['receipt_number'] ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="payment-actions">
                                        <?php if ($payment['receipt_number']): ?>
                                            <a href="../models/receipt/print.php?receipt_number=<?= $payment['receipt_number'] ?>" class="btn btn-primary">Cetak Kwitansi</a>
                                        <?php else: ?>
                                            <a href="../models/receipt/create.php?payment_id=<?= $payment['payment_id'] ?>&role=cashier" class="btn btn-secondary">Buat Kwitansi</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="widget-action">
                            <a href="../models/payment/index.php?role=cashier" class="btn">Lihat Semua Pembayaran</a>
                        </div>
                    <?php else: ?>
                        <p class="no-data">Belum ada riwayat pembayaran.</p>
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

        // Check visit details
        document.getElementById('check-button').addEventListener('click', function() {
            const visitId = document.getElementById('visit_id').value;
            if (!visitId) return;

            // Fetch visit details
            fetch(`../api/get_visit_details.php?visit_id=${visitId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    document.getElementById('animal-name').textContent = data.animal_name;
                    document.getElementById('owner-name').textContent = data.owner_name;
                    document.getElementById('visit-date').textContent = data.visit_date;
                    document.getElementById('total-amount').textContent = `Rp ${data.total_amount}`;
                    document.getElementById('payment_amount').value = data.total_amount_raw;

                    document.getElementById('visit-details').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data kunjungan');
                });
        });

        // Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Payment Method Chart
            const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');

            // Prepare data for payment method chart
            const methodLabels = [];
            const methodData = [];
            const methodColors = [
                '#4caf50', '#2196f3', '#ff9800', '#e91e63'
            ];

            <?php
            $index = 0;
            if ($payment_methods) {
                while ($method = $payment_methods->fetch_assoc()):
            ?>
                    methodLabels.push('<?= $method['payment_method'] ?>');
                    methodData.push(<?= $method['count'] ?>);
            <?php
                    $index++;
                endwhile;
            }
            ?>

            const paymentMethodChart = new Chart(paymentMethodCtx, {
                type: 'doughnut',
                data: {
                    labels: methodLabels,
                    datasets: [{
                        data: methodData,
                        backgroundColor: methodColors,
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
                            text: 'Distribusi Metode Pembayaran',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });

            // Monthly Income Chart
            const monthlyIncomeCtx = document.getElementById('monthlyIncomeChart').getContext('2d');

            // Prepare data for monthly income chart
            const monthLabels = [];
            const incomeData = [];

            <?php
            if ($monthly_income) {
                while ($month = $monthly_income->fetch_assoc()):
                    $month_name = date('M Y', strtotime($month['month'] . '-01'));
            ?>
                    monthLabels.push('<?= $month_name ?>');
                    incomeData.push(<?= $month['total'] ?>);
            <?php
                endwhile;
            }
            ?>

            const monthlyIncomeChart = new Chart(monthlyIncomeCtx, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: incomeData,
                        backgroundColor: '#4caf50',
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
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Pendapatan Bulanan (6 Bulan Terakhir)',
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