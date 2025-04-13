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

            // Arahkan ke dashboard berdasarkan peran
            if ($role === 'admin') {
                header("Location: dashboard_admin.php");
            } elseif ($role === 'vet') {
                header("Location: dashboard_vet.php");
            } elseif ($role === 'owner') {
                header("Location: dashboard_owner.php");
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