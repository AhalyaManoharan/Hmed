<?php include("master.php"); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_logged_in = isset($_SESSION['user_id']);
$user_name = '';
$user_image = 'images/user_default.jpg'; // Default profile image

if ($user_logged_in) {
    include("db_connect.php");
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];

    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $user_name = $user['name'];
        // Uncomment if you have a profile_pic column
        // if (!empty($user['profile_pic'])) {
        //     $user_image = 'uploads/profile/' . $user['profile_pic'];
        // }
    }
    $stmt->close();
}

// Determine dashboard link
$user_dashboard_link = '#';
if ($user_logged_in) {
    switch ($_SESSION['user_type']) {
        case 'student':
            $user_dashboard_link = 'dashboards/student/dashboard.php';
            break;
        case 'employer':
            $user_dashboard_link = 'dashboards/employer/dashboard.php';
            break;
        case 'college':
            $user_dashboard_link = 'dashboards/college/dashboard.php';
            break;
    }
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_logged_in = isset($_SESSION['user_id']);
$user_name = '';
$user_image = 'images/user_default.jpg'; // Default profile image

if ($user_logged_in) {
    include("db_connect.php");
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];

    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $user_name = $user['name'];
        // Uncomment if you have a profile_pic column
        // if (!empty($user['profile_pic'])) {
        //     $user_image = 'uploads/profile/' . $user['profile_pic'];
        // }
    }
    $stmt->close();
}

// Determine dashboard link
$user_dashboard_link = '#';
if ($user_logged_in) {
    switch ($_SESSION['user_type']) {
        case 'student':
            $user_dashboard_link = 'dashboards/student/dashboard.php';
            break;
        case 'employer':
            $user_dashboard_link = 'dashboards/employer/dashboard.php';
            break;
        case 'college':
            $user_dashboard_link = 'dashboards/college/dashboard.php';
            break;
    }
}
?>
<header id="utf-header-container-block"> 
  <div id="header">
    <div class="container"> 
      <div class="utf-left-side"> 
        <div id="logo"> 
          <a href="index.php"><img src="../assets/images/logo.png" alt=""></a> 
        </div>

        <nav id="navigation">
          <ul id="responsive">
            <li><a href="/hmed/index.php">Home</a></li>
           
            <li><a href="about-us.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
          </ul>
        </nav>
        <div class="clearfix"></div>                    
      </div>

      <div class="utf-right-side"> 
        <?php if (!$user_logged_in): ?>
  <div class="utf-header-widget-item"> 
    <a href="/hmed/login/index.php" class="log-in-button">
      <i class="icon-feather-log-in"></i> <span>Sign In</span>
    </a> 
  </div>
<?php endif; ?>


        <div class="utf-header-widget-item"> 
          <div class="utf-header-notifications user-menu">
            <div class="utf-header-notifications-trigger user-profile-title"> 
              <a href="<?php echo $user_dashboard_link; ?>">
                <div class="user-avatar status-online">
                  <img src="<?php echo $user_image; ?>" alt="User">
                </div>
                <div class="user-name">
                  <?php echo $user_logged_in ? "Hi, " . htmlspecialchars($user_name) . "!" : "Welcome Guest"; ?>
                </div>
              </a>
            </div>

            <?php if ($user_logged_in): ?>
              <div class="utf-header-notifications-dropdown-block"> 
                <ul class="utf-user-menu-dropdown-nav">
                  <?php if ($_SESSION['user_type'] == 'student'): ?>
                    <li><a href="dashboards/student/dashboard.php"><i class="icon-feather-user"></i> My Profile</a></li>
                  <?php elseif ($_SESSION['user_type'] == 'employer'): ?>
                    <li><a href="dashboards/employer/dashboard-jobs-post.php"><i class="icon-line-awesome-user-secret"></i> Post Job</a></li>
                    <li><a href="dashboards/employer/dashboard.php"><i class="icon-material-outline-group"></i> Manage Jobs</a></li>
                  <?php elseif ($_SESSION['user_type'] == 'college'): ?>
                    <li><a href="dashboards/college/dashboard.php"><i class="icon-material-outline-dashboard"></i> College Dashboard</a></li>
                  <?php endif; ?>
                  <li><a href="/Hmed/logout/logout.php"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>

                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <span class="mmenu-trigger">
          <button class="hamburger utf-hamburger-collapse-item" type="button"> 
            <span class="utf-hamburger-box-item"> <span class="utf-hamburger-inner-item"></span> </span> 
          </button>
        </span> 
      </div>
    </div>
  </div>
</header>


