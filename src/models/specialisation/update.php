<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$specialisation = $conn->query("SELECT * FROM specialisation WHERE spec_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $spec_description = $_POST['spec_description'];

    $sql = "UPDATE specialisation SET spec_description = '$spec_description' WHERE spec_id = $id";
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
    <title>Edit Spesialisasi</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Spesialisasi</h1>
        <form method="POST">
            <label for="spec_description">Deskripsi Spesialisasi:</label>
            <input type="text" id="spec_description" name="spec_description" value="<?= $specialisation['spec_description'] ?>" required>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>