<?php include("../includes/header.php"); ?>
<style>
  .portal-card {
    background-color: #1506c4;
    color: white;
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
    transition: 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .portal-card:hover {
    background-color: #ea6b24;
    transform: scale(1.05);
  }

  .portal-icon {
    font-size: 50px;
    margin-bottom: 15px;
    color: #fff;
  }

  @media (max-width: 768px) {
    .portal-card {
      margin-bottom: 20px;
    }
  }
</style>

<body>
  <div class="d-flex flex-column justify-content-center align-items-center" style="height: 600px;">
    <h2 class="mb-2">What do you want to do?</h2>
    <p class="text-muted mb-4">Click on one of the options below</p>

    <div class="container row justify-content-center">

      <!-- Employer Card -->
      <div class="col-md-4 col-sm-12 mb-3">
        <div class="portal-card" onclick="goToRegister('Employer')">

          <div class="portal-icon">🧑‍💼</div>
          <h3 class="font-white">I want to hire</h3>
          <small>Recruit</small>
        </div>
      </div>

      <!-- Student Card -->
      <div class="col-md-4 col-sm-12 mb-3">
        <div class="portal-card" onclick="goToRegister('student')">
          <div class="portal-icon">🎓</div>
          <h5>I want a job</h5>
          <small>Jobs</small>
        </div>
      </div>

      <!-- College Card -->
      <div class="col-md-4 col-sm-12 mb-3">
         <div class="portal-card" onclick="goToRegister('college')">
          <div class="portal-icon">🏫</div>
          <h5>College Portal</h5>
          <small>Manage Students</small>
        </div>
      </div>

    </div>
  </div>

 <script>
  function goToRegister(role) {
    window.location.href = `../register/register.php?role=${role}`;
  }
</script>


<?php include("../includes/footer.php"); ?>
