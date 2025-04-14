<?php
session_start();
include '../connection/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $owner_givenname = $_POST['owner_givenname'];
    $owner_familyname = $_POST['owner_familyname'];
    $owner_address = $_POST['owner_address'];
    $owner_phone = $_POST['owner_phone'];

    try {
        // Buat user baru di database
        $create_user_sql = "CREATE USER '$username'@'localhost' IDENTIFIED BY '$password'";
        $conn->query($create_user_sql);

        // Berikan hak akses SELECT pada tabel-tabel yang diperlukan untuk role owner
        $grant_permissions_sql = "
            GRANT SELECT ON sahabatsatwa.visit TO '$username'@'localhost';
            GRANT SELECT ON sahabatsatwa.visit_drug TO '$username'@'localhost';
            GRANT SELECT ON sahabatsatwa.animal TO '$username'@'localhost';
            GRANT SELECT ON sahabatsatwa.vet TO '$username'@'localhost';
            GRANT SELECT ON sahabatsatwa.drug TO '$username'@'localhost';
        ";
        $conn->multi_query($grant_permissions_sql);
        while ($conn->next_result()) {;
        } // Membersihkan hasil multi_query

        // Berikan role owner ke user baru
        $grant_role_sql = "GRANT owner TO '$username'@'localhost'";
        $conn->query($grant_role_sql);

        // Set default role ke owner
        $set_default_role_sql = "SET DEFAULT ROLE owner FOR '$username'@'localhost'";
        $conn->query($set_default_role_sql);

        // Tambahkan data owner ke tabel owners
        $insert_owner_sql = "INSERT INTO owners (owner_givenname, owner_familyname, owner_address, owner_phone) 
                             VALUES ('$owner_givenname', '$owner_familyname', '$owner_address', '$owner_phone')";
        $conn->query($insert_owner_sql);

        // Redirect ke halaman login setelah berhasil mendaftar
        header("Location: login.php");
        exit();
    } catch (Exception $e) {
        $error_message = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sahabat Satwa</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Daftar Sebagai Owner</h1>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="owner_givenname">Nama Depan:</label>
            <input type="text" id="owner_givenname" name="owner_givenname" required>

            <label for="owner_familyname">Nama Belakang:</label>
            <input type="text" id="owner_familyname" name="owner_familyname" required>

            <label for="owner_address">Alamat:</label>
            <input type="text" id="owner_address" name="owner_address" required>

            <label for="owner_phone">Telepon:</label>
            <input type="text" id="owner_phone" name="owner_phone" required>

            <div class="actions">
                <button type="submit" class="btn btn-register">Daftar</button>
                <a href="login.php" class="btn btn-back">Kembali ke Login</a>
            </div>
        </form>
    </div>
</body>

</html>