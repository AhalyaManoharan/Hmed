<?php
require '../includes/db_connect.php';

$email = $_POST['email'];
$role = $_POST['role'];

// Check if email exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

    // Save token in DB
    $update = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
    $update->bind_param("sss", $token, $expiry, $email);
    $update->execute();

    // Build reset link
    $resetLink = "http://yourdomain.com/login/reset_password.php?token=$token&role=$role";

    // Send email
    $subject = "Password Reset Link";
    $message = "Hello,\n\nClick the link below to reset your password:\n\n$resetLink\n\nThis link expires in 30 minutes.";
    $headers = "From: noreply@yourdomain.com";

    if (mail($email, $subject, $message, $headers)) {
        header("Location: ../login/forgot_password.php?role=$role&success=Reset link sent to your email.");
    } else {
        header("Location: ../login/forgot_password.php?role=$role&error=Failed to send email.");
    }
} else {
    header("Location: ../login/forgot_password.php?role=$role&error=Email not found.");
}
