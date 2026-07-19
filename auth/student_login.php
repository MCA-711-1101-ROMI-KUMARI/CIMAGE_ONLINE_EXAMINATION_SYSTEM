<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
if (isStudentLoggedIn()) redirect('../student/dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) { $error = 'Please fill in all fields.'; }
    else {
        $q = mysqli_query($conn, "SELECT * FROM student_tbl WHERE email='$email' AND status='active' LIMIT 1");
        if ($row = mysqli_fetch_assoc($q)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['student_id'] = $row['id'];
                $_SESSION['student_name'] = $row['name'];
                $_SESSION['student_email'] = $row['email'];
                $_SESSION['student_course'] = $row['course_id'];
                logActivity('student', $row['id'], $row['name'], 'Student Logged In');
                redirect('../student/dashboard.php');
            } else { $error = 'Invalid email or password.'; }
        } else { $error = 'No account found or account is inactive.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Login - CIMAGE</title>
<link rel="stylesheet" href="../css/landing.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-left">
    <h2>Student Portal</h2>
    <p>Access your exams, track your performance, and view your results all in one place.</p>
    <div class="auth-feats">
      <div class="auth-feat"><i class="bi bi-journal-check"></i> <span>Take Online Exams</span></div>
      <div class="auth-feat"><i class="bi bi-graph-up-arrow"></i> <span>Track Performance</span></div>
      <div class="auth-feat"><i class="bi bi-file-earmark-text"></i> <span>View &amp; Download Results</span></div>
      <div class="auth-feat"><i class="bi bi-clock-history"></i> <span>Exam History</span></div>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-box">
      <div class="auth-logo-row">
        <div class="auth-logo">
          <div class="shield">C</div>
          <h4>CIMAGE</h4>
          <small>Centre of Digital Technology and Entrepreneurship</small>
        </div>
      </div>
      <h3>Student Login</h3>
      <p class="auth-sub">Sign in to your student account</p>
      <?php if ($error): ?><div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="student1@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? 'student1@gmail.com') ?>" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" value="password" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Login</button>
      </form>
      <div class="divider">or</div>
      <div class="auth-link">Don't have an account? <a href="student_register.php">Register here</a></div>
      <div class="auth-link"><a href="../auth/admin_login.php">Admin Login</a> &nbsp;|&nbsp; <a href="../index.php">Home</a></div>
    </div>
  </div>
</div>
</body>
</html>
