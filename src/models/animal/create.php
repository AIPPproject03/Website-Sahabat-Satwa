<?php
// filepath: d:\AIPPROJECT03\TUGAS WEB\Website Sahabat Satwa\src\models\animal\create.php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_name = $_POST['animal_name'];
    $animal_born = $_POST['animal_born'];
    $owner_id = $_POST['owner_id'];
    $at_id = $_POST['at_id'];

    $sql = "INSERT INTO animal (animal_name, animal_born, owner_id, at_id) 
            VALUES ('$animal_name', '$animal_born', $owner_id, $at_id)";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$owners = $conn->query("SELECT owner_id, owner_givenname, owner_familyname FROM owners");
$animal_types = $conn->query("SELECT at_id, at_description FROM animal_type");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Hewan Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Hewan Baru</h1>
        <form method="POST">
            <label for="animal_name">Nama Hewan:</label>
            <input type="text" id="animal_name" name="animal_name" required>

            <label for="animal_born">Tanggal Lahir:</label>
            <input type="date" id="animal_born" name="animal_born" required>

            <label for="owner_id">Pemilik:</label>
            <select id="owner_id" name="owner_id" required>
                <?php while ($owner = $owners->fetch_assoc()): ?>
                    <option value="<?= $owner['owner_id'] ?>">
                        <?= $owner['owner_givenname'] . ' ' . $owner['owner_familyname'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="at_id">Jenis Hewan:</label>
            <select id="at_id" name="at_id" required>
                <?php while ($type = $animal_types->fetch_assoc()): ?>
                    <option value="<?= $type['at_id'] ?>">
                        <?= $type['at_description'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="btn btn-add">Simpan</button>
            <a href="index.php" class="btn btn-back">Kembali</a>
        </form>
    </div>
</body>

</html>