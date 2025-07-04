<?php
require '../includes/db_connect.php';
session_start();

$email = $_SESSION['reset_email'];
$pass = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

if ($pass !== $confirm) {
    die("Passwords do not match.");
}

$hashed = password_hash($pass, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashed, $email);
$stmt->execute();

session_unset();
session_destroy();

header("Location: ../login/login.php?role=student&success=Password updated successfully.");
