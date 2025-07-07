<?php
session_start();
include("../../includes/db_connect.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];

$degree_level = $_POST['degree_level'];
$course = $_POST['course'];
$institution = $_POST['institution'];
$location = $_POST['location'];
$from_year = $_POST['from_year'];
$to_year = $_POST['to_year'];
$mode = $_POST['mode'];
$project_title = $_POST['project_title'];

$stmt = $conn->prepare("INSERT INTO student_education 
(user_id, degree_level, course, institution, location, from_year, to_year, mode, project_title) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssiiss", $user_id, $degree_level, $course, $institution, $location, $from_year, $to_year, $mode, $project_title);
$stmt->execute();

// Echo HTML to add to education_list div
echo "<div class='mb-3'>
  <strong>$degree_level " . ($course ? "in $course" : "") . "</strong><br>
  $institution, $location<br>
  $from_year - $to_year | $mode" . 
  ($project_title ? "<div><strong>Project:</strong> $project_title</div>" : "") .
"</div>";

$stmt->close();
