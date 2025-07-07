<?php
session_start();
include("../../includes/db_connect.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM student_employments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    echo "deleted";
} else {
    echo "Invalid ID";
}
?>
