<?php
session_start();
include '../../connection/conn.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../../pages/login.php");
    exit();
}

// Periksa role pengguna
$redirect_dashboard = ($_SESSION['role'] === 'cashier') ? "../../pages/dashboard_cashier.php" : "../../pages/dashboard_admin.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: $redirect_dashboard");
    exit();
}

// Ambil data dari form
$visit_id = $_POST['visit_id'];
$cashier_id = $_POST['cashier_id'];
$payment_amount = $_POST['payment_amount'];
$payment_method = $_POST['payment_method'];

// Validasi input
if (empty($visit_id) || empty($cashier_id) || empty($payment_amount) || empty($payment_method)) {
    $_SESSION['error_message'] = "Semua field harus diisi.";
    header("Location: $redirect_dashboard");
    exit();
}

// Periksa apakah kunjungan ada dan belum dibayar
$check_visit = $conn->query("SELECT visit_status FROM visit WHERE visit_id = $visit_id");
if ($check_visit && $check_visit->num_rows > 0) {
    $visit = $check_visit->fetch_assoc();
    if ($visit['visit_status'] == 'Paid') {
        // Kunjungan sudah dibayar
        $_SESSION['error_message'] = "Kunjungan ini sudah dibayar.";
        header("Location: $redirect_dashboard");
        exit();
    }
} else {
    // Kunjungan tidak ditemukan
    $_SESSION['error_message'] = "Kunjungan tidak ditemukan.";
    header("Location: $redirect_dashboard");
    exit();
}

// Gunakan stored procedure untuk memproses pembayaran
$stmt = $conn->prepare("CALL process_payment(?, ?, ?, ?)");
$stmt->bind_param("iiis", $visit_id, $cashier_id, $payment_amount, $payment_method);

if ($stmt->execute()) {
    // Berhasil memproses pembayaran
    $_SESSION['success_message'] = "Pembayaran berhasil diproses!";
    header("Location: $redirect_dashboard");
    exit();
} else {
    // Gagal memproses pembayaran
    $_SESSION['error_message'] = "Gagal memproses pembayaran: " . $conn->error;
    header("Location: $redirect_dashboard");
    exit();
}
