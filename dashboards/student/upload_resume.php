<?php
session_start();
include("../../includes/db_connect.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];
$response = [];

if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $originalName = basename($_FILES['resume']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx'];

    if (!in_array($ext, $allowed)) {
        echo "Only PDF, DOC, and DOCX files are allowed.";
        exit;
    }

    $folder = "../../uploads/resumes/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $newName = $user_id . "_" . time() . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $originalName);
    $targetFile = $folder . $newName;

    if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetFile)) {
        // Save filename to DB
        $stmt = $conn->prepare("UPDATE student_profiles SET resume_filename = ?, updated_at = NOW() WHERE user_id = ?");
        $stmt->bind_param("si", $newName, $user_id);
        if ($stmt->execute()) {
            echo "Resume uploaded successfully.|$newName";
        } else {
            echo "Failed to update database.";
        }
    } else {
        echo "Failed to move uploaded file.";
    }
} else {
    echo "No file uploaded.";
}
?>
