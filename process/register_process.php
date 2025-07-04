<?php
include '../includes/db_connect.php';

// Sanitize & fetch inputs
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);
$user_type = trim($_POST['user_type']);

// Validate required fields
if (!$name || !$email || !$password || !$confirm_password || !$user_type) {
  die("All fields are required.");
}

// Validate password match
if ($password !== $confirm_password) {
  header("Location: ../register/register.php?role=$user_type&error=mismatch");
  exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// ✅ Step 1: Check for duplicate email
$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$res = $checkStmt->get_result();

if ($res->num_rows > 0) {
  $res->free(); // Free the result
  $checkStmt->close(); // Close the statement
  header("Location: ../register/register.php?role=$user_type&error=email");
  exit;
}

$res->free();
$checkStmt->close(); // ✅ Always close before reusing

// ✅ Step 2: Insert new user
$insertStmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
$insertStmt->bind_param("ssss", $name, $email, $hashedPassword, $user_type);

if ($insertStmt->execute()) {
  $insertStmt->close();
  header("Location: ../login/login.php?role=$user_type&registered=1");
  exit;
} else {
  $insertStmt->close();
  header("Location: ../register/register.php?role=$user_type&error=server");
  exit;
}
?>
