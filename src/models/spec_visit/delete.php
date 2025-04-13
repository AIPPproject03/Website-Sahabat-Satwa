<?php
include '../../connection/conn.php';

$clinic_id = $_GET['clinic_id'];
$vet_id = $_GET['vet_id'];

$sql = "DELETE FROM spec_visit WHERE clinic_id = $clinic_id AND vet_id = $vet_id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
