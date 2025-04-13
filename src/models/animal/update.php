<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$animal = $conn->query("SELECT * FROM animal WHERE animal_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_name = $_POST['animal_name'];
    $animal_born = $_POST['animal_born'];
    $owner_id = $_POST['owner_id'];
    $at_id = $_POST['at_id'];

    $sql = "UPDATE animal 
            SET animal_name = '$animal_name', animal_born = '$animal_born', owner_id = $owner_id, at_id = $at_id 
            WHERE animal_id = $id";
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
    <title>Edit Hewan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Hewan</h1>
        <form method="POST">
            <label for="animal_name">Nama Hewan:</label>
            <input type="text" id="animal_name" name="animal_name" value="<?= $animal['animal_name'] ?>" required>

            <label for="animal_born">Tanggal Lahir:</label>
            <input type="date" id="animal_born" name="animal_born" value="<?= $animal['animal_born'] ?>" required>

            <label for="owner_id">Pemilik:</label>
            <select id="owner_id" name="owner_id" required>
                <?php while ($owner = $owners->fetch_assoc()): ?>
                    <option value="<?= $owner['owner_id'] ?>" <?= $owner['owner_id'] == $animal['owner_id'] ? 'selected' : '' ?>>
                        <?= $owner['owner_givenname'] . ' ' . $owner['owner_familyname'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="at_id">Jenis Hewan:</label>
            <select id="at_id" name="at_id" required>
                <?php while ($type = $animal_types->fetch_assoc()): ?>
                    <option value="<?= $type['at_id'] ?>" <?= $type['at_id'] == $animal['at_id'] ? 'selected' : '' ?>>
                        <?= $type['at_description'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <div class="actions">
                <button type="submit" class="btn btn-add">
                    <span class="icon">💾</span> Simpan
                </button>
                <a href="index.php" class="btn btn-back">
                    <span class="icon">🔙</span> Kembali
                </a>
            </div>
        </form>
    </div>
</body>

</html>