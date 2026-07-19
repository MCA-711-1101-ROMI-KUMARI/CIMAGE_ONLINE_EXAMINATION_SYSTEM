<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
if (isStudentLoggedIn()) redirect('../student/dashboard.php');
$error = ''; $success = '';
$courses = mysqli_query($conn, "SELECT * FROM course_tbl WHERE status='active' ORDER BY course_code");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $course_id = (int)($_POST['course_id'] ?? 0);
    $semester = sanitize($_POST['semester'] ?? '');
    $gender = sanitize($_POST['gender'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$name||!$email||!$password||!$course_id) { $error='Please fill all required fields.'; }
    elseif ($password !== $confirm) { $error='Passwords do not match.'; }
    elseif (strlen($password) < 6) { $error='Password must be at least 6 characters.'; }
    else {
        $chk = mysqli_query($conn, "SELECT id FROM student_tbl WHERE email='$email'");
        if (mysqli_num_rows($chk) > 0) { $error='Email already registered.'; }
        else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sid = 'STU' . str_pad(rand(100,999), 3, '0', STR_PAD_LEFT) . rand(10,99);
            $ins = mysqli_query($conn, "INSERT INTO student_tbl (student_id,name,email,password,phone,course_id,semester,gender) VALUES ('$sid','$name','$email','$hash','$phone',$course_id,'$semester','$gender')");
            if ($ins) {
                logActivity('student', mysqli_insert_id($conn), $name, 'New Student Registered');
                $success = 'Registration successful! You can now <a href="student_login.php">login here</a>.';
            } else { $error='Registration failed. Please try again.'; }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Register - CIMAGE</title>
<link rel="stylesheet" href="../css/landing.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>.auth-right{padding:40px 60px}.auth-box{max-width:500px}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}</style>
</head>
<body>
<div class="auth-page">
  <div class="auth-left">
    <h2>Join CIMAGE</h2>
    <p>Register to access online examinations, track your progress, and get instant results.</p>
    <div class="auth-feats">
      <div class="auth-feat"><i class="bi bi-check-circle-fill"></i> <span>Free Registration</span></div>
      <div class="auth-feat"><i class="bi bi-check-circle-fill"></i> <span>Multiple Courses Available</span></div>
      <div class="auth-feat"><i class="bi bi-check-circle-fill"></i> <span>Instant Result Access</span></div>
      <div class="auth-feat"><i class="bi bi-check-circle-fill"></i> <span>Performance Analytics</span></div>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-box">
      <div class="auth-logo-row"><div class="auth-logo"><div class="shield">C</div><h4>CIMAGE</h4></div></div>
      <h3>Student Registration</h3>
      <p class="auth-sub">Create your student account</p>
      <?php if ($error): ?><div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> <?= $error ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= $success ?></div><?php endif; ?>
      <form method="POST">
        <div class="grid-2">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" class="form-control" placeholder="Your full name" required value="<?= htmlspecialchars($_POST['name']??'') ?>">
          </div>
          <div class="form-group">
            <label>Email Address *</label>
            <input type="email" name="email" class="form-control" placeholder="your@email.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="10-digit number" value="<?= htmlspecialchars($_POST['phone']??'') ?>">
          </div>
          <div class="form-group">
            <label>Gender</label>
            <select name="gender" class="form-control">
              <option value="">Select Gender</option>
              <option value="Male" <?= (($_POST['gender']??'')=='Male')?'selected':'' ?>>Male</option>
              <option value="Female" <?= (($_POST['gender']??'')=='Female')?'selected':'' ?>>Female</option>
              <option value="Other" <?= (($_POST['gender']??'')=='Other')?'selected':'' ?>>Other</option>
            </select>
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Course *</label>
            <select name="course_id" class="form-control" required>
              <option value="">Select Course</option>
              <?php mysqli_data_seek($courses,0); while($c=mysqli_fetch_assoc($courses)): ?>
              <option value="<?=$c['id']?>" <?= (($_POST['course_id']??0)==$c['id'])?'selected':'' ?>><?=$c['course_code']?> - <?=$c['course_name']?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Semester</label>
            <select name="semester" class="form-control">
              <option value="">Select Semester</option>
              <?php foreach(['1st','2nd','3rd','4th','5th','6th'] as $s): ?>
              <option value="<?=$s?>" <?= (($_POST['semester']??'')==$s)?'selected':'' ?>><?=$s?> Semester</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
          </div>
          <div class="form-group">
            <label>Confirm Password *</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Register Now</button>
      </form>
      <div class="auth-link">Already have an account? <a href="student_login.php">Login here</a></div>
    </div>
  </div>
</div>
</body>
</html>
