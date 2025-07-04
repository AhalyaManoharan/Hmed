<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
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

    .btn-verify {
      width: 100%;
      background: linear-gradient(to right, #00c6ff, #0072ff);
      border: none;
      color: white;
      border-radius: 50px;
      padding: 12px;
      font-weight: 600;
      margin-top: 15px;
    }

    .alert {
      font-size: 14px;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      color: #0072ff;
      text-decoration: none;
      font-weight: 500;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-card">
  <h2>Enter OTP</h2>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_GET['error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_GET['success']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <form action="../process/verify_otp_process.php" method="POST">
    <div class="mb-3">
      <input type="text" name="otp" class="form-control" required placeholder="Enter the OTP sent to your email">
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-verify">Verify OTP</button>
    </div>
  </form>

  <div class="back-link">
    Didn't receive OTP? <a href="../forgot_password/forgot_password.php">Resend</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
