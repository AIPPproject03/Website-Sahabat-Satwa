<?php
session_start();

// Hapus semua data sesi
session_unset();
session_destroy();

// Arahkan ke halaman utama
header("Location: ../app.php");
exit();
