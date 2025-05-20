<?php
include '../../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cashier_name = $_POST['cashier_name'];
    $cashier_username = $_POST['cashier_username'];
    $cashier_password = password_hash($_POST['cashier_password'], PASSWORD_BCRYPT);
    $clinic_id = $_POST['clinic_id'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $sql = "INSERT INTO cashier (cashier_name, cashier_username, cashier_password, clinic_id, is_active) 
            VALUES ('$cashier_name', '$cashier_username', '$cashier_password', $clinic_id, $is_active)";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data klinik
$clinics = $conn->query("SELECT clinic_id, clinic_name FROM clinic ORDER BY clinic_name ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kasir Baru</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Kasir Baru</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="cashier_name">Nama Kasir:</label>
            <input type="text" id="cashier_name" name="cashier_name" required>

            <label for="cashier_username">Username:</label>
            <input type="text" id="cashier_username" name="cashier_username" required>

            <label for="cashier_password">Password:</label>
            <input type="password" id="cashier_password" name="cashier_password" required>

            <label for="clinic_id">Klinik:</label>
            <select id="clinic_id" name="clinic_id" required>
                <?php while ($clinic = $clinics->fetch_assoc()): ?>
                    <option value="<?= $clinic['clinic_id'] ?>"><?= $clinic['clinic_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="is_active">Status Aktif:</label>
            <input type="checkbox" id="is_active" name="is_active" checked>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>