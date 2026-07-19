<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid=(int)$_SESSION['student_id'];
$msg='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $old=$_POST['old_password']??'';
    $new=$_POST['new_password']??'';
    $confirm=$_POST['confirm_password']??'';
    $student=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM student_tbl WHERE id=$sid"));
    if(!password_verify($old,$student['password'])){$error='Current password is incorrect.';}
    elseif($new!==$confirm){$error='New passwords do not match.';}
    elseif(strlen($new)<6){$error='Password must be at least 6 characters.';}
    else{
        $hash=password_hash($new,PASSWORD_BCRYPT);
        mysqli_query($conn,"UPDATE student_tbl SET password='$hash' WHERE id=$sid");
        $msg='Password changed successfully!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password - CIMAGE</title>
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
        <h2 style="font-size:20px;font-weight:700">Change Password</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['student_name'],0,1))?></div><span><?=$_SESSION['student_name']?></span></div></div>
    </div>
    <div class="content-area">
      <?php if($msg): ?><div class="toast-wrap"><div class="toast success"><span>✓</span><span><?=htmlspecialchars($msg)?></span></div></div><?php endif; ?>
      <?php if($error): ?><div class="toast-wrap"><div class="toast error"><span>✕</span><span><?=htmlspecialchars($error)?></span></div></div><?php endif; ?>
      <div class="card" style="max-width:500px;margin:0 auto">
        <div class="card-header"><h3><i class="bi bi-lock-fill" style="color:var(--primary)"></i> Change Password</h3></div>
        <div class="card-body">
          <form method="POST">
            <div class="form-group"><label>Current Password</label><input type="password" name="old_password" class="form-control" placeholder="Enter current password" required></div>
            <div class="form-group"><label>New Password</label><input type="password" name="new_password" class="form-control" placeholder="New password (min 6 chars)" required></div>
            <div class="form-group"><label>Confirm New Password</label><input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password" required></div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-lock-fill"></i> Change Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
