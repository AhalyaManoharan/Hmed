
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post a Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .tab-pane { padding-top: 20px; }
    .nav-pills .nav-link.active { background-color: #1506c4; }
    .tab-content { border-left: 1px solid #ccc; padding-left: 30px; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="row">
    <div class="col-md-3">
      <h4>Post a Job <span class="badge bg-success">Free</span></h4>
      <ul class="nav flex-column nav-pills" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#details">Job Details</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#preferences">Candidate Preferences</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#questions">Ask Questions</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#description">Job Description</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#communication">Communication</a></li>
      </ul>
    </div>

    <div class="col-md-9">
      <form id="jobForm">
        <div class="tab-content">
          <div id="details" class="tab-pane fade show active">
            <div class="mb-3"><label>Company Name</label><input type="text" name="company_name" class="form-control" required></div>
            <div class="mb-3"><label>Job Title</label><input type="text" name="job_title" class="form-control" required></div>
            <div class="mb-3"><label>Job Location</label><input type="text" name="job_location" class="form-control"></div>
          </div>
          <div id="preferences" class="tab-pane fade">
            <div class="mb-3"><label>Candidate Preferences</label><textarea name="candidate_preferences" class="form-control" rows="4"></textarea></div>
          </div>
          <div id="questions" class="tab-pane fade">
            <div class="mb-3"><label>Ask Questions</label><textarea name="ask_questions" class="form-control" rows="4"></textarea></div>
          </div>
          <div id="description" class="tab-pane fade">
            <div class="mb-3"><label>Job Description</label><textarea name="job_description" class="form-control" rows="5"></textarea></div>
          </div>
          <div id="communication" class="tab-pane fade">
            <div class="mb-3"><label>Communication Preferences</label><textarea name="communication_preferences" class="form-control" rows="3"></textarea></div>
            <button type="submit" class="btn btn-primary">Submit Job</button>
          </div>
        </div>
      </form>
      <div id="responseMsg" class="mt-3"></div>
    </div>
  </div>
</div>

<?php include("modal_plans.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/job_upload.js"></script>
</body>
</html>
