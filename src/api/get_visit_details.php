<?php
// filepath: d:\AIPPROJECT03\TUGAS WEB\Website Sahabat Satwa\src\api\get_visit_details.php
header('Content-Type: application/json');
include '../connection/conn.php';

if (!isset($_GET['visit_id']) || empty($_GET['visit_id'])) {
    echo json_encode(['error' => 'ID Kunjungan tidak valid']);
    exit;
}

$visit_id = $_GET['visit_id'];

// Query untuk mendapatkan detail kunjungan
$query = "
    SELECT 
        v.visit_id, 
        v.visit_date_time, 
        v.visit_status,
        a.animal_name, 
        CONCAT(o.owner_givenname, ' ', o.owner_familyname) as owner_name,
        calculate_total_payment(v.visit_id) as total_amount
    FROM visit v
    JOIN animal a ON v.animal_id = a.animal_id
    JOIN owners o ON a.owner_id = o.owner_id
    WHERE v.visit_id = $visit_id AND v.visit_status = 'Unpaid'
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $visit = $result->fetch_assoc();

    // Format tanggal
    $visit['visit_date'] = date('d M Y', strtotime($visit['visit_date_time']));

    // Format total amount untuk tampilan
    $visit['total_amount_raw'] = $visit['total_amount'];
    $visit['total_amount'] = number_format($visit['total_amount'], 0, ',', '.');

    echo json_encode($visit);
} else {
    echo json_encode(['error' => 'Kunjungan tidak ditemukan atau sudah dibayar']);
}
