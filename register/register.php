<?php
$role = $_GET['role'] ?? 'student';
$roleLabel = ucfirst($role);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $roleLabel; ?> Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom, #00c6ff, #0072ff);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .register-card {
      background-color: white;
      padding: 40px 30px;
      border-radius: 25px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .register-card h2 {
      font-weight: 700;
      font-size: 24px;
      margin-bottom: 20px;
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

    .btn-register {
      width: 100%;
      background: linear-gradient(to right, #00c6ff, #0072ff);
      border: none;
      color: white;
      border-radius: 50px;
      padding: 12px;
      font-weight: 600;
      margin-top: 15px;
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

    .toggle-password {
      position: absolute;
      right: 20px;
      top: 10px;
      cursor: pointer;
      color: #0072ff;
    }

    .position-relative {
      position: relative;
    }
  </style>
</head>
<body>

<div class="register-card">
  <h2><?php echo $roleLabel; ?> Registration</h2>

  <!-- ✅ Show Error or Success Messages -->
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php
        switch ($_GET['error']) {
          case 'email':
            echo 'Email ID is already registered.';
            break;
          case 'server':
            echo 'Something went wrong. Please try again later.';
            break;
          case 'mismatch':
            echo 'Passwords do not match.';
            break;
          default:
            echo 'Unknown error occurred.';
        }
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Registration successful! Please <a href="../login/login.php?role=<?php echo $role; ?>" class="alert-link">login</a>.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- ✅ Registration Form -->
  <form action="../process/register_process.php" method="POST">
    <input type="hidden" name="user_type" value="<?php echo $role; ?>">

    <div class="mb-3">
      <input type="text" class="form-control" name="name" placeholder="Full Name" required>
    </div>

    <div class="mb-3">
      <input type="email" class="form-control" name="email" placeholder="Email address" required>
    </div>

    <div class="mb-3 position-relative">
      <input type="password" class="form-control password" name="password" placeholder="Password" required>
      <i class="fas fa-eye toggle-password"></i>
    </div>

    <div class="mb-3 position-relative">
      <input type="password" class="form-control password" name="confirm_password" placeholder="Confirm Password" required>
      <i class="fas fa-eye toggle-password"></i>
    </div>

    <button type="submit" class="btn btn-register">Create <?php echo $roleLabel; ?> Account</button>

    <div class="back-link">
      Already have an account? <a href="../login/login.php?role=<?php echo $role; ?>">Login</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Password show/hide toggle
  document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", function () {
      const input = this.previousElementSibling;
      if (input.type === "password") {
        input.type = "text";
        this.classList.remove("fa-eye");
        this.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        this.classList.remove("fa-eye-slash");
        this.classList.add("fa-eye");
      }
    });
  });

  // Optional: Clean URL after error shown
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.pathname + "?role=<?php echo $role; ?>");
  }
</script>

</body>
</html>
