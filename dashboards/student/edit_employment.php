<?php
session_start();
include("../../includes/db_connect.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$id = $_POST['id'] ?? null;
$company = $_POST['company_name'] ?? '';
$designation = $_POST['designation'] ?? '';
$start = $_POST['start_date'] ?? '';
$end = $_POST['end_date'] ?? '';
$description = $_POST['description'] ?? '';

// Validate
if (!$id || !$company || !$designation) {
    exit("Invalid data");
}

// Convert dates
$start_date = $start ? $start . "-01" : null;
$end_date = $end ? $end . "-01" : null;

// Update
$stmt = $conn->prepare("UPDATE student_employments SET company_name=?, designation=?, start_date=?, end_date=?, description=? WHERE id=? AND user_id=?");

if ($stmt === false) {
    exit("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssssi", $company, $designation, $start_date, $end_date, $description, $id, $user_id);

if (!$stmt->execute()) {
    exit("Execute failed: " . $stmt->error);
}

$stmt->close();
echo "success";
?>
