<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$owner = $conn->query("SELECT * FROM owners WHERE owner_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $owner_givenname = $_POST['owner_givenname'];
    $owner_familyname = $_POST['owner_familyname'];
    $owner_address = $_POST['owner_address'];
    $owner_phone = $_POST['owner_phone'];

    $sql = "UPDATE owners 
            SET owner_givenname = '$owner_givenname', owner_familyname = '$owner_familyname', 
                owner_address = '$owner_address', owner_phone = '$owner_phone' 
            WHERE owner_id = $id";
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
    <title>Edit Pemilik</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Pemilik</h1>
        <form method="POST">
            <label for="owner_givenname">Nama Depan:</label>
            <input type="text" id="owner_givenname" name="owner_givenname" value="<?= $owner['owner_givenname'] ?>" required>

            <label for="owner_familyname">Nama Belakang:</label>
            <input type="text" id="owner_familyname" name="owner_familyname" value="<?= $owner['owner_familyname'] ?>" required>

            <label for="owner_address">Alamat:</label>
            <input type="text" id="owner_address" name="owner_address" value="<?= $owner['owner_address'] ?>" required>

            <label for="owner_phone">Telepon:</label>
            <input type="text" id="owner_phone" name="owner_phone" value="<?= $owner['owner_phone'] ?>" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>