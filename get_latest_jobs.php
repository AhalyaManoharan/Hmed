<?php
include("includes/db_connect.php");

$sql = "SELECT * FROM jobs ORDER BY created_at DESC LIMIT 6";
$result = $conn->query($sql);

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}
header('Content-Type: application/json');
echo json_encode($jobs);
?>
