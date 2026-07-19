<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();

if (isset($_GET['delete'])) {
    $id=(int)$_GET['delete'];
    mysqli_query($conn,"DELETE FROM course_tbl WHERE id=$id");
    $_SESSION['msg']='Course deleted.'; redirect('courses.php');
}

$msg=$_SESSION['msg']??''; unset($_SESSION['msg']);
$edit=null;
if (isset($_GET['edit'])) { $id=(int)$_GET['edit']; $edit=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM course_tbl WHERE id=$id")); }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=sanitize($_POST['course_name']??'');
    $code=sanitize($_POST['course_code']??'');
    $desc=sanitize($_POST['description']??'');
    $dur=sanitize($_POST['duration']??'');
    $status=sanitize($_POST['status']??'active');
    if ($name && $code) {
        if (isset($_POST['edit_id']) && $_POST['edit_id']) {
            $id=(int)$_POST['edit_id'];
            mysqli_query($conn,"UPDATE course_tbl SET course_name='$name',course_code='$code',description='$desc',duration='$dur',status='$status' WHERE id=$id");
            $_SESSION['msg']='Course updated successfully.';
        } else {
            mysqli_query($conn,"INSERT INTO course_tbl (course_name,course_code,description,duration,status) VALUES ('$name','$code','$desc','$dur','$status')");
            $_SESSION['msg']='Course added successfully.';
        }
        redirect('courses.php');
    }
}
$courses=mysqli_query($conn,"SELECT c.*,(SELECT COUNT(*) FROM student_tbl WHERE course_id=c.id) as stud_count,(SELECT COUNT(*) FROM exam_tbl WHERE course_id=c.id) as exam_count FROM course_tbl c ORDER BY c.course_code");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Courses - CIMAGE Admin</title>
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
        <h2 style="font-size:20px;font-weight:700">Manage Courses</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <?php if($msg): ?><div class="toast-wrap"><div class="toast success"><span>✓</span><span><?=htmlspecialchars($msg)?></span></div></div><?php endif; ?>
      <div class="grid-2">
        <!-- FORM -->
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-<?=$edit?'pencil':'plus-circle'?>-fill" style="color:var(--primary)"></i> <?=$edit?'Edit Course':'Add New Course'?></h3></div>
          <div class="card-body">
            <form method="POST">
              <?php if($edit): ?><input type="hidden" name="edit_id" value="<?=$edit['id']?>"><?php endif; ?>
              <div class="form-group">
                <label>Course Name *</label>
                <input type="text" name="course_name" class="form-control" value="<?=htmlspecialchars($edit['course_name']??'')?>" placeholder="e.g. Bachelor of Computer Applications" required>
              </div>
              <div class="form-group">
                <label>Course Code *</label>
                <input type="text" name="course_code" class="form-control" value="<?=htmlspecialchars($edit['course_code']??'')?>" placeholder="e.g. BCA" required>
              </div>
              <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" class="form-control" value="<?=htmlspecialchars($edit['duration']??'')?>" placeholder="e.g. 3 Years">
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Course description..."><?=htmlspecialchars($edit['description']??'')?></textarea>
              </div>
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                  <option value="active" <?=($edit&&$edit['status']==='active')?'selected':''?>>Active</option>
                  <option value="inactive" <?=($edit&&$edit['status']==='inactive')?'selected':''?>>Inactive</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> <?=$edit?'Update Course':'Add Course'?></button>
              <?php if($edit): ?><a href="courses.php" class="btn btn-outline" style="margin-left:8px">Cancel</a><?php endif; ?>
            </form>
          </div>
        </div>
        <!-- LIST -->
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-book-fill" style="color:var(--secondary)"></i> All Courses</h3></div>
          <div class="table-responsive">
            <table class="data-table">
              <thead><tr><th>Code</th><th>Course Name</th><th>Students</th><th>Exams</th><th>Status</th><th>Actions</th></tr></thead>
              <tbody>
              <?php while($c=mysqli_fetch_assoc($courses)): ?>
              <tr>
                <td><span class="badge badge-primary"><?=htmlspecialchars($c['course_code'])?></span></td>
                <td><strong><?=htmlspecialchars($c['course_name'])?></strong><br><small style="color:var(--muted)"><?=htmlspecialchars($c['duration'])?></small></td>
                <td><?=$c['stud_count']?></td>
                <td><?=$c['exam_count']?></td>
                <td><span class="badge <?=$c['status']==='active'?'badge-success':'badge-danger'?>"><?=$c['status']?></span></td>
                <td>
                  <a href="courses.php?edit=<?=$c['id']?>" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                  <a href="#" onclick="confirmDelete('courses.php?delete=<?=$c['id']?>','Delete this course?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
