<?php
include '../../connection/conn.php';

$visit_id = $_GET['visit_id'];
$drug_id = $_GET['drug_id'];

$sql = "DELETE FROM visit_drug WHERE visit_id = $visit_id AND drug_id = $drug_id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
