<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $owner_givenname = $_POST['owner_givenname'];
    $owner_familyname = $_POST['owner_familyname'];
    $owner_address = $_POST['owner_address'];
    $owner_phone = $_POST['owner_phone'];

    $sql = "INSERT INTO owners (owner_givenname, owner_familyname, owner_address, owner_phone) 
            VALUES ('$owner_givenname', '$owner_familyname', '$owner_address', '$owner_phone')";
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
    <title>Tambah Pemilik Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Pemilik Baru</h1>
        <form method="POST">
            <label for="owner_givenname">Nama Depan:</label>
            <input type="text" id="owner_givenname" name="owner_givenname" required>

            <label for="owner_familyname">Nama Belakang:</label>
            <input type="text" id="owner_familyname" name="owner_familyname" required>

            <label for="owner_address">Alamat:</label>
            <input type="text" id="owner_address" name="owner_address" required>

            <label for="owner_phone">Telepon:</label>
            <input type="text" id="owner_phone" name="owner_phone" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>