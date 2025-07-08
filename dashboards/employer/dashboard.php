<?php
session_start();
include("../../includes/db_connect.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    header("Location: ../../login/login.php?role=employer");
    exit;
}
?>
<?php
include("../../includes/db_connect.php");

$userId = $_SESSION['user_id'];
$query = "SELECT name, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

$employerName = $userData['name'];
$profileImage = !empty($userData['profile_image']) ? $userData['profile_image'] : "images/user-avatar-placeholder.png";
?>
<?php
$profileImage = '';
if (!empty($user['profile_image'])) {
    $profileImage = '../../uploads/profile_images/' . $user['profile_image'];
} elseif (!empty($user['google_image_url'])) {
    $profileImage = $user['google_image_url']; // From Google login
} else {
    $profileImage = '../../assets/default-user.png'; // Fallback default
}
?>

<style>
.profile-img-container {
  position: relative;
  width: 80px;
  height: 80px;
  margin: auto;
}

.profile-img-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #1506c4;
}

.profile-img-container .edit-icon {
  position: absolute;bottom: -14px;
    right: -10px;

  background-color: #fff;
  border-radius: 50%;
  padding: 8px;
  cursor: pointer;
  box-shadow: 0 0 5px rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
}

.profile-img-container .edit-icon:hover {
  background-color: #ea6b24;
  color: white;
}
</style>



<?php include("../../includes/header.php"); ?>

<body>
<!-- Preloader Start -->
<div class="preloader">
    <div class="utf-preloader">
        <span></span>
        <span></span>
        <span></span>
		<span></span>		
    </div>
</div>
<!-- Preloader End -->

<!-- Wrapper -->
<div id="wrapper"> 

  <div class="clearfix"></div>
  <!-- Header Container / End --> 
  
  <!-- Dashboard Container -->
  <div class="utf-dashboard-container-aera"> 
    <!-- Dashboard Sidebar -->
    <div class="utf-dashboard-sidebar-item">
      <div class="utf-dashboard-sidebar-item-inner" data-simplebar>
        <div class="utf-dashboard-nav-container"> 
          <!-- Responsive Navigation Trigger --> 
          <a href="#" class="utf-dashboard-responsive-trigger-item"> <span class="hamburger utf-hamburger-collapse-item" > <span class="utf-hamburger-box-item"> <span class="utf-hamburger-inner-item"></span> </span> </span> <span class="trigger-title">Dashboard Navigation Menu</span> </a> 
          <!-- Navigation -->
		  <div class="utf-dashboard-nav">
			<div class="utf-dashboard-nav-inner">
			  <div class="dashboard-profile-box">
  <div class="text-center">
  <div class="section-title text-white">Profile Picture</div>

  <div class="profile-img-container">
    <img id="profileImagePreview" src="<?= $profileImage ?>" alt="Profile">
    <label for="profileImageInput" class="edit-icon">
      <i class="bi bi-pencil-fill"></i>
    </label>
    <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
  </div>

  <div id="profileUploadStatus" class="mt-2 text-success"></div>
</div>
  <div class="user-profile-text px-5">
    <span class="fullname"><?php echo htmlspecialchars($employerName); ?></span>
    <span class="user-role">employer</span>
  </div>
</div>

			  <div class="clearfix"></div>
              <ul>
                <li><a href="dashboard.html"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>				
				<li><a href="dashboard-manage-jobs.html"><i class="icon-material-outline-group"></i> Manage Jobs <span class="nav-tag">5</span></a></li>
				<li><a href="dashboard-manage-resume.html"><i class="icon-material-outline-supervisor-account"></i> Manage Resume</a></li>
				<li><a href="dashboard-bookmarks.html"><i class="icon-feather-heart"></i> Bookmarks Jobs</a></li>
				<li class="active"><a href="dashboard-my-profile.php"><i class="icon-feather-user"></i> My Profile</a></li>
                <li><a href="index-1.html"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>
              </ul>              
            </div>
          </div>          
        </div>
      </div>
    </div>
    <!-- Dashboard Sidebar / End --> 

    <!-- Dashboard Content -->
    <div class="utf-dashboard-content-container-aera" data-simplebar>
	  <div id="dashboard-titlebar" class="utf-dashboard-headline-item">
		<div class="row">
			<div class="col-xl-12">	
				<h3>My Profile</h3>
				<nav id="breadcrumbs">
					<ul>
					  <li><a href="index-1.html">Home</a></li>
					  <li>My Profile</li>
					</ul>
				</nav>
			</div>
		</div>		
      </div>
	  <div class="utf-dashboard-content-inner-aera">   
        <?php include("employer-post-job.php"); ?>
		
        <div class="utf-dashboard-footer-spacer-aera"></div>
        <div class="utf-small-footer margin-top-15">
          <div class="utf-small-footer-copyrights">Copyright &copy; 2021 All Rights Reserved.</div>
        </div>        
      </div>
    </div>    
	<!-- Dashboard Content End -->
  </div>
</div>
<!-- Wrapper / End --> 
<script>
document.getElementById("profileImageInput").addEventListener("change", function () {
  const fileInput = this;
  const formData = new FormData();
  formData.append("profile_image", fileInput.files[0]);

  fetch("upload_profile_image.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      document.getElementById("profileImagePreview").src = data.image_url + '?t=' + new Date().getTime();
      document.getElementById("profileUploadStatus").innerText = "Profile photo updated successfully!";
    } else {
      document.getElementById("profileUploadStatus").innerText = "Upload failed: " + data.message;
    }
  });
});
</script>

<!-- Scripts --> 
<script src="js/jquery-3.3.1.min.js"></script> 
<script src="js/jquery-migrate-3.0.0.min.js"></script> 
<script src="js/mmenu.min.js"></script> 
<script src="js/tippy.all.min.js"></script> 
<script src="js/simplebar.min.js"></script> 
<script src="js/bootstrap-slider.min.js"></script> 
<script src="js/bootstrap-select.min.js"></script> 
<script src="js/snackbar.js"></script> 
<script src="js/clipboard.min.js"></script> 
<script src="js/counterup.min.js"></script> 
<script src="js/magnific-popup.min.js"></script> 
<script src="js/slick.min.js"></script> 
<script src="js/custom_jquery.js"></script> 
</body>
</html>
