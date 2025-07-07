<?php
session_start();
include("../../includes/db_connect.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];

$id          = $_POST['id'] ?? null;
$company     = $_POST['company_name'] ?? '';
$designation = $_POST['designation'] ?? '';
$start       = $_POST['start_date'] ?? '';
$end         = $_POST['end_date'] ?? '';
$description = $_POST['description'] ?? '';
$action      = $_POST['action'] ?? 'add';

// Convert to full date
$start_date = $start ? $start . "-01" : null;
$end_date   = $end ? $end . "-01" : null;

// Add or Update
if ($action === 'edit' && $id) {
    $stmt = $conn->prepare("UPDATE student_employments SET company_name=?, designation=?, start_date=?, end_date=?, description=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssssi", $company, $designation, $start_date, $end_date, $description, $id, $user_id);
    $stmt->execute();
    $stmt->close();
} elseif ($action === 'add') {
    $stmt = $conn->prepare("INSERT INTO student_employments (user_id, company_name, designation, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $company, $designation, $start_date, $end_date, $description);
    $stmt->execute();
    $stmt->close();
}

// Return updated employment list HTML
$jobs = $conn->prepare("SELECT * FROM student_employments WHERE user_id = ? ORDER BY start_date DESC");
$jobs->bind_param("i", $user_id);
$jobs->execute();
$jobsResult = $jobs->get_result();

while ($job = $jobsResult->fetch_assoc()) {
    $jobId   = $job['id'];
    $start   = date('M Y', strtotime($job['start_date']));
    $end     = $job['end_date'] ? date('M Y', strtotime($job['end_date'])) : 'Present';
    $startR  = substr($job['start_date'], 0, 7);
    $endR    = $job['end_date'] ? substr($job['end_date'], 0, 7) : '';
    ?>
    <div class="border rounded p-3 mb-2" id="job_<?= $jobId ?>">
        <strong class="designation"><?= htmlspecialchars($job['designation']) ?></strong> at 
        <strong class="company_name"><?= htmlspecialchars($job['company_name']) ?></strong><br>
        <span class="start_date" data-raw="<?= $startR ?>"><?= $start ?></span> - 
        <span class="end_date" data-raw="<?= $endR ?>"><?= $end ?></span><br>
        <small class="description"><?= nl2br(htmlspecialchars($job['description'])) ?></small>
        <div class="mt-2">
            <button class="btn btn-sm btn-secondary" onclick="editEmploymentInline(<?= $jobId ?>)">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="deleteEmployment(<?= $jobId ?>)">Delete</button>
        </div>
    </div>
    <?php
}
$jobs->close();
?>

