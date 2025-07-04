<?php
session_start();
include '../includes/db_connect.php';

$email = trim($_POST['email']);
$password = trim($_POST['password']);
$user_type = trim($_POST['user_type']);

// Prepare the SQL query
$stmt = $conn->prepare("SELECT id, name, password, user_type FROM users WHERE email = ? AND user_type = ?");
$stmt->bind_param("ss", $email, $user_type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['flash_success'] = "Welcome back, " . $user['name'] . "!";

        // Redirect based on user type
        switch ($user['user_type']) {
            case 'student':
                header("Location: ../dashboards/student/dashboard.php");
                break;
            case 'employer':
                header("Location: ../dashboards/employer/dashboard.php");
                break;
            case 'college':
                header("Location: ../dashboards/college/dashboard.php");
                break;
            default:
                header("Location: ../login/login.php?role=$user_type&error=unknownrole");
        }
        exit;
    } else {
        // Incorrect password
        $_SESSION['login_error'] = "Incorrect email or password.";
        header("Location: ../login/login.php?role=$user_type");
        exit;
    }
} else {
    // User not registered
    $_SESSION['login_error'] = "No account found for this email.";
    header("Location: ../login/login.php?role=$user_type");
    exit;
} ?>
