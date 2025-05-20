<?php
include '../../connection/conn.php';

$id = $_GET['id'];
$cashier = $conn->query("SELECT * FROM cashier WHERE cashier_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cashier_name = $_POST['cashier_name'];
    $cashier_username = $_POST['cashier_username'];
    $clinic_id = $_POST['clinic_id'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $sql = "UPDATE cashier 
            SET cashier_name = '$cashier_name', 
                cashier_username = '$cashier_username', 
                clinic_id = $clinic_id, 
                is_active = $is_active 
            WHERE cashier_id = $id";

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
    <title>Edit Kasir</title>
    <link rel="stylesheet" href="../../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Kasir</h1>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="cashier_name">Nama Kasir:</label>
            <input type="text" id="cashier_name" name="cashier_name" value="<?= $cashier['cashier_name'] ?>" required>

            <label for="cashier_username">Username:</label>
            <input type="text" id="cashier_username" name="cashier_username" value="<?= $cashier['cashier_username'] ?>" required>

            <label for="clinic_id">Klinik:</label>
            <select id="clinic_id" name="clinic_id" required>
                <?php while ($clinic = $clinics->fetch_assoc()): ?>
                    <option value="<?= $clinic['clinic_id'] ?>" <?= $clinic['clinic_id'] == $cashier['clinic_id'] ? 'selected' : '' ?>>
                        <?= $clinic['clinic_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="is_active">Status Aktif:</label>
            <input type="checkbox" id="is_active" name="is_active" <?= $cashier['is_active'] ? 'checked' : '' ?>>

            <div class="actions">
                <button type="submit" class="btn btn-add">Simpan</button>
                <a href="index.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>