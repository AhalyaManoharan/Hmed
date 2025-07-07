<?php
session_start();
include("../../includes/db_connect.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];
$field = $_POST['field'] ?? '';

$allowed_fields = [
    'resume_headline',
    'key_skills',
    'profile_summary',
    'accomplishments',
    'career_profile',
    'personal_details'
];

if (!in_array($field, $allowed_fields)) {
    echo "Invalid field.";
    exit;
}

if ($field === 'personal_details') {
    $gender = $_POST['gender'] ?? '';
    $marital = $_POST['marital_status'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $category = $_POST['category'] ?? '';
    $disability = $_POST['disability'] ?? '';

    $value = "Gender: $gender<br>Marital Status: $marital<br>Date of Birth: $dob<br>Category: $category<br>Differently Abled: $disability";
} else {
    $value = $_POST['value'] ?? '';
}

$sql = "UPDATE student_profiles SET `$field` = ?, updated_at = NOW() WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $value, $user_id);

if ($stmt->execute()) {
    echo nl2br(htmlspecialchars($value));
} else {
    echo "Update failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
