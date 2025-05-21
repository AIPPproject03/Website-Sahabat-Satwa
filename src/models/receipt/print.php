<?php
// filepath: d:\AIPPROJECT03\TUGAS WEB\Website Sahabat Satwa\src\models\receipt\print.php
include '../../connection/conn.php';

// Periksa apakah receipt_number diberikan melalui URL
if (!isset($_GET['receipt_number']) || empty($_GET['receipt_number'])) {
    die("Nomor kwitansi tidak ditemukan.");
}

$receipt_number = $conn->real_escape_string($_GET['receipt_number']);

// Ambil data kwitansi dari database berdasarkan receipt_number
$sql = "
    SELECT 
        r.receipt_id,
        r.receipt_number,
        r.issue_date,
        r.total_amount,
        p.payment_id,
        p.payment_date,
        p.payment_method,
        v.visit_id,
        v.visit_date_time,
        a.animal_name,
        o.owner_givenname,
        o.owner_familyname,
        vet.vet_title,
        vet.vet_givenname,
        vet.vet_familyname,
        c.clinic_name,
        c.clinic_address
    FROM receipt r
    JOIN payment p ON r.payment_id = p.payment_id
    JOIN visit v ON p.visit_id = v.visit_id
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN owners o ON a.owner_id = o.owner_id
    JOIN vet ON v.vet_id = vet.vet_id
    JOIN clinic c ON vet.clinic_id = c.clinic_id
    WHERE r.receipt_number = '$receipt_number'
";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Kwitansi dengan nomor tersebut tidak ditemukan.");
}

$receipt = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kwitansi</title>
    <link rel="stylesheet" href="../../../public/assets/css/print.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .receipt-header p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .receipt-details {
            margin-bottom: 20px;
        }

        .receipt-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-details th,
        .receipt-details td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
        }

        .btn-print {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-print:hover {
            background-color: #45a049;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Kwitansi Pembayaran</h1>
            <p><?= htmlspecialchars($receipt['clinic_name']) ?></p>
            <p><?= htmlspecialchars($receipt['clinic_address']) ?></p>
        </div>

        <div class="receipt-details">
            <table>
                <tr>
                    <th>Nomor Kwitansi:</th>
                    <td><?= htmlspecialchars($receipt['receipt_number']) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Terbit:</th>
                    <td><?= date('d M Y', strtotime($receipt['issue_date'])) ?></td>
                </tr>
                <tr>
                    <th>Total Pembayaran:</th>
                    <td>Rp <?= number_format($receipt['total_amount'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Metode Pembayaran:</th>
                    <td><?= htmlspecialchars($receipt['payment_method']) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Pembayaran:</th>
                    <td><?= date('d M Y', strtotime($receipt['payment_date'])) ?></td>
                </tr>
                <tr>
                    <th>Nama Hewan:</th>
                    <td><?= htmlspecialchars($receipt['animal_name']) ?></td>
                </tr>
                <tr>
                    <th>Pemilik:</th>
                    <td><?= htmlspecialchars($receipt['owner_givenname'] . ' ' . $receipt['owner_familyname']) ?></td>
                </tr>
                <tr>
                    <th>Dokter:</th>
                    <td><?= htmlspecialchars($receipt['vet_title'] . ' ' . $receipt['vet_givenname'] . ' ' . $receipt['vet_familyname']) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Kunjungan:</th>
                    <td><?= date('d M Y', strtotime($receipt['visit_date_time'])) ?></td>
                </tr>
            </table>
        </div>

        <div class="receipt-footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <a href="#" class="btn-print" onclick="window.print()">Cetak Kwitansi</a>
        </div>
    </div>
</body>

</html>