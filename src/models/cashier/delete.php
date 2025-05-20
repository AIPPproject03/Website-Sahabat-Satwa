<?php
include '../../connection/conn.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "DELETE FROM cashier WHERE cashier_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data kasir untuk konfirmasi
$cashier = $conn->query("SELECT cashier_name FROM cashier WHERE cashier_id = $id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Kasir</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Hapus Kasir</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <p>Apakah Anda yakin ingin menghapus kasir <strong><?= $cashier['cashier_name'] ?></strong>?</p>

        <form method="POST">
            <div class="actions">
                <button type="submit" class="btn btn-delete">Hapus</button>
                <a href="index.php" class="btn btn-back">Batal</a>
            </div>
        </form>
    </div>
</body>

</html>