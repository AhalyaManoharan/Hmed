<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../includes/db_connect.php';
session_start();

$email = $_POST['email'];
$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

// Check if email exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp, $expiry, $email);
    $stmt->execute();

    // Setup PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gomathyjeyshree15@gmail.com';           // Your Gmail
        $mail->Password = 'ixig xkcg csve fibk';             // App password from Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('gomathyjeyshree15@gmail.com', 'Healthcare Job Portal');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "<p>Your OTP is: <strong>$otp</strong></p><p>This OTP expires in 10 minutes.</p>";

        $mail->send();

        $_SESSION['reset_email'] = $email;
        header("Location: ../login/verify_otp.php?success=OTP sent to your email.");
    } catch (Exception $e) {
        header("Location: ../login/forgot_password.php?error=Mailer Error: " . $mail->ErrorInfo);
    }
} else {
    header("Location: ../login/forgot_password.php?error=Email not found.");
}
