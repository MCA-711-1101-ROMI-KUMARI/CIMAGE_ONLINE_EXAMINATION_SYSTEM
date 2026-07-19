<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();
$msg='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['change_password'])){
        $old=$_POST['old_password']??'';
        $new=$_POST['new_password']??'';
        $confirm=$_POST['confirm_password']??'';
        $admin=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM admin_tbl WHERE id=".(int)$_SESSION['admin_id']));
        if(!password_verify($old,$admin['password'])){$error='Current password is incorrect.';}
        elseif($new!==$confirm){$error='New passwords do not match.';}
        elseif(strlen($new)<6){$error='Password must be at least 6 characters.';}
        else{
            $hash=password_hash($new,PASSWORD_BCRYPT);
            mysqli_query($conn,"UPDATE admin_tbl SET password='$hash' WHERE id=".(int)$_SESSION['admin_id']);
            $msg='Password changed successfully.';
        }
    }
}
$admin=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM admin_tbl WHERE id=".(int)$_SESSION['admin_id']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings - CIMAGE Admin</title>
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
        <h2 style="font-size:20px;font-weight:700">Settings</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <?php if($msg): ?><div class="toast-wrap"><div class="toast success"><span>✓</span><span><?=htmlspecialchars($msg)?></span></div></div><?php endif; ?>
      <?php if($error): ?><div class="toast-wrap"><div class="toast error"><span>✕</span><span><?=htmlspecialchars($error)?></span></div></div><?php endif; ?>
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-person-circle" style="color:var(--primary)"></i> Admin Profile</h3></div>
          <div class="card-body">
            <div style="text-align:center;margin-bottom:24px">
              <div style="width:80px;height:80px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:800;margin:0 auto 12px"><?=strtoupper(substr($admin['name'],0,1))?></div>
              <h3><?=htmlspecialchars($admin['name'])?></h3>
              <p style="color:var(--muted);font-size:13px"><?=htmlspecialchars($admin['email'])?></p>
              <span class="badge badge-primary"><?=htmlspecialchars($admin['role'])?></span>
            </div>
            <div style="border-top:1px solid var(--border);padding-top:16px">
              <?php foreach([['Name',$admin['name']],['Email',$admin['email']],['Role',$admin['role']],['Joined',date('d M Y',strtotime($admin['created_at']))]] as $row): ?>
              <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);font-size:14px">
                <span style="color:var(--muted)"><?=$row[0]?></span>
                <strong><?=htmlspecialchars($row[1])?></strong>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-lock-fill" style="color:var(--warning)"></i> Change Password</h3></div>
          <div class="card-body">
            <form method="POST">
              <div class="form-group"><label>Current Password</label><input type="password" name="old_password" class="form-control" placeholder="Current password" required></div>
              <div class="form-group"><label>New Password</label><input type="password" name="new_password" class="form-control" placeholder="New password (min 6 chars)" required></div>
              <div class="form-group"><label>Confirm New Password</label><input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password" required></div>
              <button type="submit" name="change_password" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Update Password</button>
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
