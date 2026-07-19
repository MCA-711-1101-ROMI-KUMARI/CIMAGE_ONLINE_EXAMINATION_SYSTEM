<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
if (isAdminLoggedIn()) redirect('../admin/dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) { $error = 'Please fill in all fields.'; }
    else {
        $q = mysqli_query($conn, "SELECT * FROM admin_tbl WHERE email='$email' LIMIT 1");
        if ($row = mysqli_fetch_assoc($q)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                logActivity('admin', $row['id'], $row['name'], 'Admin Logged In');
                redirect('../admin/dashboard.php');
            } else { $error = 'Invalid email or password.'; }
        } else { $error = 'Invalid email or password.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - CIMAGE</title>
<link rel="stylesheet" href="../css/landing.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-left">
    <h2>Admin Portal</h2>
    <p>Manage exams, students, courses, and results from one powerful dashboard.</p>
    <div class="auth-feats">
      <div class="auth-feat"><i class="bi bi-people-fill"></i> <span>Manage All Students</span></div>
      <div class="auth-feat"><i class="bi bi-journal-text"></i> <span>Create &amp; Manage Exams</span></div>
      <div class="auth-feat"><i class="bi bi-bar-chart-fill"></i> <span>View Analytics &amp; Reports</span></div>
      <div class="auth-feat"><i class="bi bi-shield-lock-fill"></i> <span>AI Proctoring Logs</span></div>
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
      <h3>Admin Login</h3>
      <p class="auth-sub">Sign in to your admin account</p>
      <?php if ($error): ?><div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="admin@cimage.com" value="<?= htmlspecialchars($_POST['email'] ?? 'admin@cimage.com') ?>" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" value="admin123" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Login</button>
      </form>
      <div class="auth-link"><a href="student_login.php">← Student Login</a> &nbsp;|&nbsp; <a href="../index.php">Home</a></div>
    </div>
  </div>
</div>
</body>
</html>
