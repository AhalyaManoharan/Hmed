<?php
// Enable full error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Force JSON output and clean any unexpected output
ob_clean();
header('Content-Type: application/json');

// Return JSON response
header('Content-Type: application/json');

session_start();
include("../../includes/db_connect.php");

// Step 0: Ensure only logged-in employers
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Step 1: Check employer plan and job count
$plan_check = $conn->prepare("SELECT plan_type FROM users WHERE id = ?");
$plan_check->bind_param("i", $user_id);
$plan_check->execute();
$plan_result = $plan_check->get_result();

if (!$plan_result || $plan_result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Employer not found']);
    exit;
}

$employer = $plan_result->fetch_assoc();
$plan_type = $employer['plan_type'] ?? 'free'; // Default to free if null

// Determine posting limit
$limit = match($plan_type) {
    'free'     => 1,
    'basic'    => 3,
    'standard' => 5,
    'premium'  => PHP_INT_MAX,
    default    => 0
};

// Count existing jobs
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM jobs WHERE employer_id = ?");
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$job_count = $count_result->fetch_assoc()['total'] ?? 0;

if ($job_count >= $limit) {
    echo json_encode(['status' => 'limit_reached', 'message' => 'Job post limit reached']);
    exit;
}

// Step 2: Collect POST data
$company_name     = trim($_POST['company_name'] ?? '');
$job_title        = trim($_POST['job_title'] ?? '');
$job_location     = trim($_POST['job_location'] ?? '');
$preferences      = trim($_POST['candidate_preferences'] ?? '');
$questions        = trim($_POST['ask_questions'] ?? '');
$job_description  = trim($_POST['job_description'] ?? '');
$communication    = trim($_POST['communication_preferences'] ?? '');

// Step 3: Validate required fields
if (empty($company_name) || empty($job_title) || empty($job_location)) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields are missing']);
    exit;
}

// Step 4: Insert job into DB
$insert = $conn->prepare("
    INSERT INTO jobs 
    (employer_id, company_name, job_title, job_location, candidate_preferences, ask_questions, job_description, communication_preferences, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$insert->bind_param(
    "isssssss",
    $user_id,
    $company_name,
    $job_title,
    $job_location,
    $preferences,
    $questions,
    $job_description,
    $communication
);

if ($insert->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Job posted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database insert failed: ' . $conn->error]);
}

$insert->close();
$conn->close();
