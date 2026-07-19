<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid=(int)$_SESSION['student_id'];
$student=mysqli_fetch_assoc(mysqli_query($conn,"SELECT s.*,c.course_code,c.course_name FROM student_tbl s LEFT JOIN course_tbl c ON s.course_id=c.id WHERE s.id=$sid"));
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=sanitize($_POST['name']??'');
    $phone=sanitize($_POST['phone']??'');
    $gender=sanitize($_POST['gender']??'');
    $address=sanitize($_POST['address']??'');
    mysqli_query($conn,"UPDATE student_tbl SET name='$name',phone='$phone',gender='$gender',address='$address' WHERE id=$sid");
    $_SESSION['student_name']=$name;
    $msg='Profile updated successfully!';
    $student=mysqli_fetch_assoc(mysqli_query($conn,"SELECT s.*,c.course_code,c.course_name FROM student_tbl s LEFT JOIN course_tbl c ON s.course_id=c.id WHERE s.id=$sid"));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile - CIMAGE</title>
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
        <h2 style="font-size:20px;font-weight:700">My Profile</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($student['name'],0,1))?></div><span><?=$student['name']?></span></div></div>
    </div>
    <div class="content-area">
      <?php if($msg): ?><div class="toast-wrap"><div class="toast success"><span>✓</span><span><?=htmlspecialchars($msg)?></span></div></div><?php endif; ?>
      <div class="grid-2">
        <div class="card">
          <div class="card-body" style="text-align:center;padding:40px 24px">
            <div style="width:90px;height:90px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;margin:0 auto 16px"><?=strtoupper(substr($student['name'],0,1))?></div>
            <h3 style="font-size:20px;font-weight:700"><?=htmlspecialchars($student['name'])?></h3>
            <p style="color:var(--muted);font-size:14px"><?=htmlspecialchars($student['email'])?></p>
            <span class="badge badge-primary" style="margin-top:8px;font-size:13px"><?=$student['course_code']?></span>
            <div style="margin-top:24px;border-top:1px solid var(--border);padding-top:20px;text-align:left">
              <?php foreach([['Student ID',$student['student_id'],'bi-person-badge'],['Course',$student['course_code'].' - '.$student['course_name'],'bi-book'],['Semester',($student['semester']??'-').' Semester','bi-calendar'],['Gender',($student['gender']??'-'),'bi-gender-ambiguous'],['Joined',date('d M Y',strtotime($student['created_at'])),'bi-clock'],['Status',ucfirst($student['status']),'bi-circle-fill']] as $row): ?>
              <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)">
                <i class="bi <?=$row[2]?>" style="color:var(--primary);width:20px"></i>
                <div><div style="font-size:11px;color:var(--muted)"><?=$row[0]?></div><strong style="font-size:14px"><?=htmlspecialchars($row[1])?></strong></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-pencil-fill" style="color:var(--primary)"></i> Edit Profile</h3></div>
          <div class="card-body">
            <form method="POST">
              <div class="form-group"><label>Full Name</label><input type="text" name="name" class="form-control" value="<?=htmlspecialchars($student['name'])?>" required></div>
              <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?=htmlspecialchars($student['phone']?:'')?>"></div>
              <div class="form-group"><label>Gender</label>
                <select name="gender" class="form-control">
                  <?php foreach(['Male','Female','Other'] as $g): ?>
                  <option value="<?=$g?>" <?=($student['gender']===$g)?'selected':''?>><?=$g?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group"><label>Address</label><textarea name="address" class="form-control" rows="3"><?=htmlspecialchars($student['address']??'')?></textarea></div>
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Update Profile</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
