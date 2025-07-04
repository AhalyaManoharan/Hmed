<?php
session_start(); // Required for session-based alert messages

$role = $_GET['role'] ?? 'student';
$roleLabel = ucfirst($role);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $roleLabel; ?> Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom, #00c6ff, #0072ff);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-card {
      background-color: white;
      padding: 40px 30px;
      border-radius: 25px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 380px;
    }

    .login-card h2 {
      font-weight: 700;
      font-size: 24px;
      margin-bottom: 30px;
      color: #2d2d2d;
      text-align: center;
    }

    .form-control {
      border-radius: 50px;
      padding: 10px 20px;
      background-color: #f0f0f0;
      border: none;
    }

    .form-control::placeholder {
      color: #aaa;
      font-size: 14px;
    }

    .btn-login {
      width: 100%;
      background: linear-gradient(to right, #00c6ff, #0072ff);
      border: none;
      color: white;
      border-radius: 50px;
      padding: 12px;
      font-weight: 600;
      margin-top: 15px;
    }

    .form-check-label,
    .forgot-pass,
    .signup-link {
      font-size: 14px;
    }

    .forgot-pass {
      float: right;
      text-decoration: none;
      color: #0072ff;
    }

    .signup-link {
      text-align: center;
      margin-top: 20px;
    }

    .signup-link a {
      color: #0072ff;
      text-decoration: none;
      font-weight: 500;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-card">
  <h2><?php echo $roleLabel; ?> Login</h2>

  <?php if (isset($_SESSION['login_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <form action="../process/login_process.php" method="POST">
    <input type="hidden" name="user_type" value="<?php echo $role; ?>">

    <div class="mb-3">
      <input type="email" class="form-control" name="email" placeholder="Email address" required>
    </div>

    <div class="mb-3">
      <input type="password" class="form-control" name="password" placeholder="Password" required>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="rememberMe">
        <label class="form-check-label" for="rememberMe">
          Remember me
        </label>
      </div>
      <a href="forgot_password.php?role=<?php echo $role; ?>" class="forgot-pass">Forgot password?</a>
    </div>

    <button type="submit" class="btn btn-login">Login as <?php echo $roleLabel; ?></button>

    <div class="signup-link">
      Don’t have an account? <a href="../register/register.php?role=<?php echo $role; ?>">Sign up</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
