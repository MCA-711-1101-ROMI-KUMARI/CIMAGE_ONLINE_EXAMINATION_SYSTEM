<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();
$id=(int)($_GET['id']??0);
$student=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM student_tbl WHERE id=$id"));
if(!$student){redirect('students.php');}
$msg='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=sanitize($_POST['name']??'');
    $email=sanitize($_POST['email']??'');
    $phone=sanitize($_POST['phone']??'');
    $cid=(int)($_POST['course_id']??0);
    $sem=sanitize($_POST['semester']??'');
    $gender=sanitize($_POST['gender']??'');
    $status=sanitize($_POST['status']??'active');
    mysqli_query($conn,"UPDATE student_tbl SET name='$name',email='$email',phone='$phone',course_id=$cid,semester='$sem',gender='$gender',status='$status' WHERE id=$id");
    if(!empty($_POST['new_password'])){
        $hash=password_hash($_POST['new_password'],PASSWORD_BCRYPT);
        mysqli_query($conn,"UPDATE student_tbl SET password='$hash' WHERE id=$id");
    }
    $_SESSION['msg']='Student updated successfully.';
    redirect('students.php');
}
$courses=mysqli_query($conn,"SELECT * FROM course_tbl WHERE status='active' ORDER BY course_code");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Student - CIMAGE Admin</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<div class="dashboard-wrapper">
  <?php include 'sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div style="display:flex;align-items:center;gap:14px">
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <h2 style="font-size:20px;font-weight:700">Edit Student</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="card" style="max-width:700px;margin:0 auto">
        <div class="card-header">
          <h3><i class="bi bi-pencil-fill" style="color:var(--primary)"></i> Edit: <?=htmlspecialchars($student['name'])?></h3>
          <a href="students.php" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="grid-2" style="gap:16px">
              <div class="form-group"><label>Full Name *</label><input type="text" name="name" class="form-control" value="<?=htmlspecialchars($student['name'])?>" required></div>
              <div class="form-group"><label>Email *</label><input type="email" name="email" class="form-control" value="<?=htmlspecialchars($student['email'])?>" required></div>
              <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?=htmlspecialchars($student['phone'])?>"></div>
              <div class="form-group"><label>Gender</label>
                <select name="gender" class="form-control">
                  <?php foreach(['Male','Female','Other'] as $g): ?>
                  <option value="<?=$g?>" <?=$student['gender']===$g?'selected':''?>><?=$g?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group"><label>Course</label>
                <select name="course_id" class="form-control">
                  <option value="">Select Course</option>
                  <?php while($c=mysqli_fetch_assoc($courses)): ?>
                  <option value="<?=$c['id']?>" <?=$student['course_id']==$c['id']?'selected':''?>><?=$c['course_code']?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-group"><label>Semester</label>
                <select name="semester" class="form-control">
                  <?php foreach(['1st','2nd','3rd','4th','5th','6th'] as $s): ?>
                  <option value="<?=$s?>" <?=$student['semester']===$s?'selected':''?>><?=$s?> Semester</option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group"><label>Status</label>
                <select name="status" class="form-control">
                  <option value="active" <?=$student['status']==='active'?'selected':''?>>Active</option>
                  <option value="inactive" <?=$student['status']==='inactive'?'selected':''?>>Inactive</option>
                </select>
              </div>
              <div class="form-group"><label>New Password <small style="color:var(--muted)">(leave blank to keep)</small></label><input type="password" name="new_password" class="form-control" placeholder="Enter new password"></div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Update Student</button>
              <a href="students.php" class="btn btn-outline">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
