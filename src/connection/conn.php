<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sahabatsatwa";

if (isset($_SESSION['db_username']) && isset($_SESSION['db_password'])) {
    $username = $_SESSION['db_username'];
    $password = $_SESSION['db_password'];
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
