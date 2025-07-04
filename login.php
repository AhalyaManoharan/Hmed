<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BlueFlame Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1506c4, #ea6b24);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-container {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      display: flex;
      flex-wrap: wrap;
      margin: 3rem auto;
      max-width: 900px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }
    .login-left {
      background: #1506c4;
      color: white;
      padding: 3rem;
      flex: 1 1 300px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .login-left h2 {
      margin-bottom: 1rem;
    }
    .login-left button {
      margin: 0.5rem 0;
      width: 200px;
    }
    .login-right {
      flex: 1 1 400px;
      padding: 3rem;
    }
    .form-control {
      border-radius: 30px;
      padding: 10px 20px;
      margin-bottom: 1rem;
      background-color: #f0f0f0;
    }
    .login-right h3 {
      color: #1506c4;
      margin-bottom: 1rem;
    }
    .btn-orange {
      background-color: #ea6b24;
      color: white;
      border-radius: 30px;
      padding: 10px 25px;
      border: none;
    }
    .btn-orange:hover {
      background-color: #d55d1c;
    }
  </style>
</head>
<body>

<div class="container login-container">
  <div class="login-left text-center">
    <img src="https://img.icons8.com/ios-filled/50/ffffff/fire-element.png" alt="Logo"/>
    <h2>Welcome Back!</h2>
    <p>Please choose your login type:</p>
    <button class="btn btn-light" onclick="selectRole('Student')">Student</button>
    <button class="btn btn-light" onclick="selectRole('Employer')">Employer</button>
    <button class="btn btn-light" onclick="selectRole('College')">College</button>
  </div>
  <div class="login-right">
    <h3 id="formTitle">Login</h3>
    <form id="loginForm">
      <input type="email" class="form-control" id="email" name="email" placeholder="Gmail ID" required>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
      <input type="hidden" id="role" name="role">
      <button type="submit" class="btn btn-orange w-100">Login</button>
    </form>
  </div>
</div>

<script>
  let currentRole = '';

  function selectRole(role) {
    currentRole = role;
    document.getElementById('formTitle').innerText = role + ' Login';
    document.getElementById('role').value = role;
  }

  document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!currentRole) {
      alert('Please select a role (Student / Employer / College)');
      return;
    }

    const formData = new FormData(this);
    const response = await fetch('login.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.text();
    alert(result);
  });
</script>

</body>
</html>
