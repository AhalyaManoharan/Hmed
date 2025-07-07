    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include("../../includes/db_connect.php");

    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
        header("Location: ../../login/login.php?role=student");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Fetch student name and email from users table
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();
    $stmt->close();

    // Ensure a row exists in student_profiles
    $check = $conn->prepare("SELECT * FROM student_profiles WHERE user_id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $profileResult = $check->get_result();
    $profile = $profileResult->fetch_assoc();

    if (!$profile) {
        $insert = $conn->prepare("INSERT INTO student_profiles (user_id) VALUES (?)");
        $insert->bind_param("i", $user_id);
        $insert->execute();
        $insert->close();
        $profile = [];
    }
    $check->close();
    ?>

    <style>
    .section-box {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .edit-btn {
        float: right;
        color: #ea6b24;
        cursor: pointer;
    }
    </style>

    <div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
        <div class="section-box">
            <div class="section-title">Basic Details</div>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        </div>

        <?php
        $fields = [
            'resume_headline' => 'Resume Headline',
            'key_skills' => 'Key Skills',
            'profile_summary' => 'Profile Summary',
            'accomplishments' => 'Accomplishments',
            'career_profile' => 'Career Profile'
        ];

        foreach ($fields as $field => $label):
            $value = isset($profile[$field]) ? htmlspecialchars($profile[$field]) : '';
        ?>
        <div id="<?= $field ?>" class="section-box">
            <div class="section-title">
            <?= $label ?>
            <span class="edit-btn" onclick="enableEdit('<?= $field ?>')">Edit</span>
            </div>
            <form id="form_<?= $field ?>" onsubmit="return saveField('<?= $field ?>')">
            <div id="display_<?= $field ?>">
                <p><?= !empty($value) ? nl2br($value) : 'Please add your ' . strtolower($label) . '.' ?></p>
            </div>
            <div id="edit_<?= $field ?>" style="display:none;">
                <textarea name="<?= $field ?>" class="form-control" rows="3"><?= $value ?></textarea>
                <button type="submit" class="btn btn-sm btn-success mt-2">Save</button>
            </div>
            </form>
        </div>
        <?php endforeach; ?>

        <!-- Personal Details Section -->
        <div id="personal_details" class="section-box">
    <div class="section-title">
        Personal Details
        <span class="edit-btn" onclick="enableEdit('personal_details')">Edit</span>
    </div>
    <form id="form_personal_details" onsubmit="return saveField('personal_details')">
        <div id="display_personal_details">
        <p><?= !empty($profile['personal_details']) ? $profile['personal_details'] : 'Please add your personal details.' ?></p>
        </div>
        <div id="edit_personal_details" style="display:none;">
        <div class="row">
            <div class="col-md-4">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option>Male</option><option>Female</option><option>Other</option>
            </select>
            </div>
            <div class="col-md-4">
            <label>Marital Status</label>
            <select name="marital_status" class="form-control">
                <option>Single</option><option>Married</option><option>Single Parent</option>
            </select>
            </div>
            <div class="col-md-4">
            <label>Date of Birth</label>
            <input type="date" name="dob" class="form-control" />
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
            <label>Category</label>
            <input type="text" name="category" class="form-control" />
            </div>
            <div class="col-md-6">
            <label>Differently Abled</label>
            <select name="disability" class="form-control">
                <option>No</option><option>Yes</option>
            </select>
            </div>
        </div>
        <button type="submit" class="btn btn-sm btn-success mt-3">Save</button>
        </div>
    </form>
    </div>

    <!-- Resume Upload -->
    <div id="resume_upload" class="section-box">
    <div class="section-title">Resume
        <span id="resume_view_link" style="float:right;">
        <?php if (!empty($profile['resume_filename'])): ?>
            <a href="../../uploads/resumes/<?= $profile['resume_filename'] ?>" target="_blank">View</a>
        <?php endif; ?>
        </span>
    </div>
    <div id="resume_status">
        <?php if (!empty($profile['resume_filename'])): ?>
        <p><strong>Uploaded:</strong> <?= $profile['resume_filename'] ?></p>
        <?php else: ?>
        <p>No resume uploaded yet.</p>
        <?php endif; ?>
    </div>

    <form id="resumeForm" enctype="multipart/form-data">
        <div class="mb-2">
        <input type="file" name="resume" accept=".pdf,.doc,.docx" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Upload / Update Resume</button>
    </form>
    </div>

    <!-- Education Section -->
    <div class="section-box">
    <div class="section-title">Education 
        <a href="#" class="edit-btn" onclick="showEducationForm()">Add education</a>
    </div>
    <div id="education_list">
        <?php
        $edu_q = $conn->prepare("SELECT * FROM student_education WHERE user_id = ? ORDER BY FIELD(degree_level, 'Doctorate/PhD', 'Masters/Post-Graduation', 'Graduation', 'Class XII', 'Class X')");
        $edu_q->bind_param("i", $user_id);
        $edu_q->execute();
        $edu_r = $edu_q->get_result();
        while ($edu = $edu_r->fetch_assoc()):
        ?>
        <div class="mb-3">
        <strong><?= $edu['degree_level'] ?> <?= $edu['course'] ? 'in ' . $edu['course'] : '' ?></strong> <br>
        <?= $edu['institution'] ?>, <?= $edu['location'] ?> <br>
        <?= $edu['from_year'] . ' - ' . $edu['to_year'] ?> | <?= $edu['mode'] ?>
        <?php if (!empty($edu['project_title'])): ?>
        <div><strong>Project:</strong> <?= $edu['project_title'] ?></div>
        <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Education Form -->
    <form id="education_form" style="display:none;" onsubmit="return saveEducation()">
        <div class="row">
        <div class="col-md-4">
            <label>Degree Level</label>
            <select name="degree_level" class="form-control" required>
            <option value="">Select</option>
            <option>Doctorate/PhD</option>
            <option>Masters/Post-Graduation</option>
            <option>Graduation</option>
            <option>Class XII</option>
            <option>Class X</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>Course</label>
            <input type="text" name="course" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Institution</label>
            <input type="text" name="institution" class="form-control" required>
        </div>
        </div>
        <div class="row mt-2">
        <div class="col-md-4">
            <label>Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>From Year</label>
            <input type="number" name="from_year" class="form-control" min="1900" max="2099">
        </div>
        <div class="col-md-2">
            <label>To Year</label>
            <input type="number" name="to_year" class="form-control" min="1900" max="2099">
        </div>
        <div class="col-md-4">
            <label>Mode</label>
            <select name="mode" class="form-control">
            <option>Full Time</option>
            <option>Part Time</option>
            <option>Distance</option>
            </select>
        </div>
        </div>
        <div class="mt-2">
        <label>Project (if any)</label>
        <textarea name="project_title" class="form-control" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-sm btn-success mt-2">Save Education</button>
    </form>
    </div>

    <!-- Career Profile -->
    <div id="career_profile" class="section-box">
    <div class="section-title">
        Career Profile
        <span class="edit-btn" onclick="toggleCareerForm()">Add</span>
    </div>

    <div id="career_list">
        <?php
        $jobs = $conn->prepare("SELECT * FROM student_employments WHERE user_id = ?");
        $jobs->bind_param("i", $user_id);
        $jobs->execute();
        $jobsResult = $jobs->get_result();
        if ($jobsResult->num_rows > 0):
        while ($job = $jobsResult->fetch_assoc()):
        ?>
        <div class="border rounded p-3 mb-2" id="job_<?= $job['id'] ?>">
    <strong><?= htmlspecialchars($job['designation']) ?></strong> at <strong><?= htmlspecialchars($job['company_name']) ?></strong><br>
    <?= date('M Y', strtotime($job['start_date'])) ?> - <?= $job['end_date'] ? date('M Y', strtotime($job['end_date'])) : 'Present' ?><br>
    <small><?= nl2br(htmlspecialchars($job['description'])) ?></small>
    <div class="mt-2">
        <button class="btn btn-sm btn-secondary" onclick="editEmployment(<?= $job['id'] ?>)">Edit</button>
        <button class="btn btn-sm btn-danger" onclick="deleteEmployment(<?= $job['id'] ?>)">Delete</button>
    </div>
    </div>

        <?php endwhile; else: ?>
        <p>No employment records added yet.</p>
        <?php endif; $jobs->close(); ?>
    </div>

    <!-- Add/Edit Form -->
    <form id="careerForm" class="mt-3" style="display:none;" onsubmit="return saveCareerProfile(this)">
        <div class="row">
        <div class="col-md-6 mb-2">
            <label>Company Name</label>
            <input type="text" name="company_name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
            <label>Designation</label>
            <input type="text" name="designation" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
            <label>Start Date</label>
            <input type="month" name="start_date" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
            <label>End Date</label>
            <input type="month" name="end_date" class="form-control">
            <small>Leave blank if currently working</small>
        </div>
        <div class="col-12 mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        </div>
        <button type="submit" class="btn btn-sm btn-success">Save</button>
        <button type="button" class="btn btn-sm btn-secondary" onclick="toggleCareerForm()">Cancel</button>
    </form>
    </div>

        </div>
    </div>
    </div>

    <script>
    function enableEdit(field) {
    document.getElementById('display_' + field).style.display = 'none';
    document.getElementById('edit_' + field).style.display = 'block';
    }

    function saveField(field) {
    const form = document.getElementById('form_' + field);
    const formData = new FormData();

    formData.append('field', field);

    if (field === 'personal_details') {
        // Collect all personal detail fields
        formData.append('gender', form.querySelector('[name="gender"]').value);
        formData.append('marital_status', form.querySelector('[name="marital_status"]').value);
        formData.append('dob', form.querySelector('[name="dob"]').value);
        formData.append('category', form.querySelector('[name="category"]').value);
        formData.append('disability', form.querySelector('[name="disability"]').value);
    } else {
        // Other fields
        const value = form.querySelector('textarea').value;
        formData.append('value', value);
    }

    fetch('save_profile_field.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById('display_' + field).innerHTML = `<p>${data}</p>`;
        document.getElementById('edit_' + field).style.display = 'none';
        document.getElementById('display_' + field).style.display = 'block';
    });

    return false;
    }
    document.getElementById("resumeForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch("upload_resume.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        const [message, filename] = response.split("|");

        let statusHTML = `<div class="alert alert-success">${message}</div>`;
        if (filename) {
        statusHTML += `<p><strong>Uploaded:</strong> ${filename}</p>`;
        document.getElementById('resume_view_link').innerHTML = `<a href='../../uploads/resumes/${filename}' target='_blank'>View</a>`;
        }
        document.getElementById("resume_status").innerHTML = statusHTML;
    })
    .catch(() => {
        document.getElementById("resume_status").innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
    });
    });

    function showEducationForm() {
    document.getElementById('education_form').style.display = 'block';
    }

    function saveEducation() {
    const form = document.getElementById('education_form');
    const formData = new FormData(form);

    fetch("save_education.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById('education_list').innerHTML += data;
        form.reset();
        form.style.display = 'none';
    });

    return false;
    }

    </script>
    <script>
    function toggleCareerForm() {
    const form = document.getElementById("careerForm");
    form.style.display = form.style.display === "none" ? "block" : "none";
    }

    function saveCareerProfile(form) {
    const formData = new FormData(form);

    fetch('save_employment.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('career_list').innerHTML = html;
        toggleCareerForm();
        form.reset();
    });
    return false;
    }
    </script>
    <script>
    function editEmploymentInline(id) {
  const card = document.getElementById('job_' + id);
  const company = card.querySelector('.company_name').innerText.trim();
  const designation = card.querySelector('.designation').innerText.trim();
  const startDate = card.querySelector('.start_date').dataset.raw;
  const endDate = card.querySelector('.end_date').dataset.raw || '';
  const description = card.querySelector('.description').innerText.trim();

  // Save current HTML to restore on cancel
  card.dataset.originalHtml = card.innerHTML;

  // Inline edit form
  card.innerHTML = `
    <form onsubmit="return saveEmploymentInline(this, ${id})">
      <div class="row">
        <div class="col-md-6 mb-2">
          <label>Company Name</label>
          <input type="text" name="company_name" value="${company}" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
          <label>Designation</label>
          <input type="text" name="designation" value="${designation}" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
          <label>Start Date</label>
          <input type="month" name="start_date" value="${startDate}" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
          <label>End Date</label>
          <input type="month" name="end_date" value="${endDate}" class="form-control">
        </div>
        <div class="col-12 mb-2">
          <label>Description</label>
          <textarea name="description" class="form-control" rows="3">${description}</textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-sm btn-success">Save</button>
      <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEmploymentEdit(${id})">Cancel</button>
    </form>
  `;
}
function saveEmploymentInline(form, id) {
  const formData = new FormData(form);
  formData.append('id', id);
  formData.append('action', 'edit');

  fetch('save_employment.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(html => {
    // Only update the section, not the whole list
    const tempContainer = document.createElement('div');
    tempContainer.innerHTML = html;

    const updatedItem = tempContainer.querySelector(`#job_${id}`);
    if (updatedItem) {
      const card = document.getElementById('job_' + id);
      card.replaceWith(updatedItem);
    }
  });

  return false;
}
function cancelEmploymentEdit(id) {
  const card = document.getElementById('job_' + id);
  card.innerHTML = card.dataset.originalHtml;
}



    function deleteEmployment(id) {
    if (confirm("Are you sure you want to delete this entry?")) {
        const formData = new FormData();
        formData.append('id', id);

        fetch("delete_employment.php", {
        method: "POST",
        body: formData
        })
        .then(res => res.text())
        .then(result => {
        if (result === "deleted") {
            document.getElementById('job_' + id).remove();
        } else {
            alert("Failed to delete.");
        }
        });
    }
    }

    </script>
