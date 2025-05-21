<?php
// filepath: d:\AIPPROJECT03\TUGAS WEB\Website Sahabat Satwa\src\models\payment\process_quick.php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../../pages/dashboard_cashier.php");
    exit();
}

$visit_id = $_POST['visit_id'];
$cashier_id = $_POST['cashier_id'];
$payment_amount = $_POST['payment_amount'];
$payment_method = $_POST['payment_method'];

// Periksa apakah kunjungan ada dan belum dibayar
$check_visit = $conn->query("SELECT visit_status FROM visit WHERE visit_id = $visit_id");
if ($check_visit && $check_visit->num_rows > 0) {
    $visit = $check_visit->fetch_assoc();
    if ($visit['visit_status'] == 'Paid') {
        // Kunjungan sudah dibayar
        $_SESSION['error_message'] = "Kunjungan ini sudah dibayar.";
        header("Location: ../../pages/dashboard_cashier.php");
        exit();
    }
} else {
    // Kunjungan tidak ditemukan
    $_SESSION['error_message'] = "Kunjungan tidak ditemukan.";
    header("Location: ../../pages/dashboard_cashier.php");
    exit();
}

// Gunakan stored procedure untuk memproses pembayaran
$stmt = $conn->prepare("CALL process_payment(?, ?, ?, ?)");
$stmt->bind_param("iiis", $visit_id, $cashier_id, $payment_amount, $payment_method);

if ($stmt->execute()) {
    // Berhasil memproses pembayaran
    $_SESSION['success_message'] = "Pembayaran berhasil diproses!";
    header("Location: ../../pages/dashboard_cashier.php");
    exit();
} else {
    // Gagal memproses pembayaran
    $_SESSION['error_message'] = "Gagal memproses pembayaran: " . $conn->error;
    header("Location: ../../pages/dashboard_cashier.php");
    exit();
}
