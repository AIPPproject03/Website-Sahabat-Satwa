<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $at_description = $_POST['at_description'];

    $sql = "INSERT INTO animal_type (at_description) VALUES ('$at_description')";
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
    <title>Tambah Jenis Hewan</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Jenis Hewan</h1>
        <form method="POST">
            <label for="at_description">Jenis Hewan:</label>
            <input type="text" id="at_description" name="at_description" required>

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