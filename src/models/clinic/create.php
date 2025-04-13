<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clinic_name = $_POST['clinic_name'];
    $clinic_address = $_POST['clinic_address'];
    $clinic_phone = $_POST['clinic_phone'];

    $sql = "INSERT INTO clinic (clinic_name, clinic_address, clinic_phone) 
            VALUES ('$clinic_name', '$clinic_address', '$clinic_phone')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Klinik Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Klinik Baru</h1>
        <form method="POST">
            <label for="clinic_name">Nama Klinik:</label>
            <input type="text" id="clinic_name" name="clinic_name" required>

            <label for="clinic_address">Alamat:</label>
            <input type="text" id="clinic_address" name="clinic_address" required>

            <label for="clinic_phone">Telepon:</label>
            <input type="text" id="clinic_phone" name="clinic_phone" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>