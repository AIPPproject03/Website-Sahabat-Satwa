<?php
session_start();
include '../connection/conn.php';

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Login dengan kredensial pengguna
        $conn->change_user($username, $password, 'sahabatsatwa');

        // Query untuk mendapatkan peran pengguna
        $sql = "SELECT CURRENT_ROLE() AS role";
        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $role = strtolower($row['role']); // Ambil peran pengguna

            // Simpan informasi login ke session
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Simpan kredensial pengguna ke session
            $_SESSION['db_username'] = $username;
            $_SESSION['db_password'] = $password;

            // Periksa role pengguna
            if ($role === 'admin') {
                // Admin langsung diarahkan ke dashboard admin
                header("Location: dashboard_admin.php");
            } elseif ($role === 'owner') {
                // Cek username di tabel owners
                $owner_sql = "SELECT owner_id FROM owners WHERE owner_username = '$username'";
                $owner_result = $conn->query($owner_sql);

                if ($owner_result && $owner_row = $owner_result->fetch_assoc()) {
                    $_SESSION['owner_id'] = $owner_row['owner_id']; // Simpan owner_id ke session
                    header("Location: dashboard_owner.php");
                } else {
                    $error_message = "Username tidak ditemukan di tabel owners.";
                }
            } elseif ($role === 'vet') {
                // Cek username di tabel vet
                $vet_sql = "SELECT vet_id FROM vet WHERE vet_username = '$username'";
                $vet_result = $conn->query($vet_sql);

                if ($vet_result && $vet_row = $vet_result->fetch_assoc()) {
                    $_SESSION['vet_id'] = $vet_row['vet_id']; // Simpan vet_id ke session
                    header("Location: dashboard_vet.php");
                } else {
                    $error_message = "Username tidak ditemukan di tabel vet.";
                }
            } elseif ($role === 'cashier') {
                // Cek username di tabel cashier
                $cashier_sql = "SELECT cashier_id FROM cashier WHERE cashier_username = '$username' AND is_active = 1";
                $cashier_result = $conn->query($cashier_sql);

                if ($cashier_result && $cashier_row = $cashier_result->fetch_assoc()) {
                    $_SESSION['cashier_id'] = $cashier_row['cashier_id']; // Simpan cashier_id ke session
                    header("Location: dashboard_cashier.php");
                } else {
                    $error_message = "Username tidak ditemukan di tabel cashier atau akun tidak aktif.";
                }
            } else {
                $error_message = "Peran tidak dikenali.";
            }
            exit();
        } else {
            $error_message = "Gagal mendapatkan peran pengguna.";
        }
    } catch (Exception $e) {
        // Tangani kesalahan login
        $error_message = "Username atau password salah.";
        // Pesan Error Sebenarnya untuk debugging
        $error_message = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <div class="actions">
                <button type="submit" class="btn btn-login">Login</button>
                <a href="../app.php" class="btn btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>