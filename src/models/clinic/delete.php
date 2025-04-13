<?php
include '../../connection/conn.php';

$id = $_GET['id'];

$sql = "DELETE FROM clinic WHERE clinic_id = $id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
