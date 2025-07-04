<?php
require '../includes/db_connect.php';
session_start();

$email = $_SESSION['reset_email'];
$entered_otp = $_POST['otp'];

$sql = "SELECT otp_code, otp_expiry FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result && $result['otp_code'] === $entered_otp && strtotime($result['otp_expiry']) > time()) {
    $_SESSION['otp_verified'] = true;
    header("Location: ../login/reset_password.php");
} else {
    header("Location: ../login/verify_otp.php?error=Invalid or expired OTP.");
}
