<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM student_tbl WHERE id=$id");
    $_SESSION['msg'] = 'Student deleted successfully.';
    redirect('students.php');
}

// Toggle status
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $s = mysqli_fetch_assoc(mysqli_query($conn,"SELECT status FROM student_tbl WHERE id=$id"));
    $ns = $s['status']==='active'?'inactive':'active';
    mysqli_query($conn,"UPDATE student_tbl SET status='$ns' WHERE id=$id");
    redirect('students.php');
}

$msg = $_SESSION['msg'] ?? ''; unset($_SESSION['msg']);
$search = sanitize($_GET['search'] ?? '');
$where = $search ? "WHERE s.name LIKE '%$search%' OR s.email LIKE '%$search%' OR s.student_id LIKE '%$search%'" : '';
$students = mysqli_query($conn,"SELECT s.*,c.course_code FROM student_tbl s LEFT JOIN course_tbl c ON s.course_id=c.id $where ORDER BY s.created_at DESC");
$total = mysqli_num_rows($students);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Students - CIMAGE Admin</title>
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
        <div><h2 style="font-size:20px;font-weight:700">Manage Students</h2><p style="font-size:12px;color:var(--muted)">Total: <?=$total?> students</p></div>
      </div>
      <div class="topbar-right">
        <div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div>
      </div>
    </div>
    <div class="content-area">
      <?php if($msg): ?><div class="toast-wrap"><div class="toast success"><span>✓</span><span><?=htmlspecialchars($msg)?></span></div></div><?php endif; ?>
      <div class="card">
        <div class="card-header">
          <h3><i class="bi bi-people-fill" style="color:var(--primary)"></i> All Students</h3>
          <div style="display:flex;gap:10px;align-items:center">
            <form method="GET" style="display:flex;gap:8px">
              <input type="text" name="search" class="form-control" placeholder="Search students..." value="<?=htmlspecialchars($search)?>" style="width:220px;padding:8px 12px">
              <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
              <?php if($search): ?><a href="students.php" class="btn btn-outline btn-sm"><i class="bi bi-x"></i></a><?php endif; ?>
            </form>
          </div>
        </div>
        <div class="table-responsive">
          <table class="data-table" id="studentsTable">
            <thead>
              <tr><th>#</th><th>Student ID</th><th>Name</th><th>Email</th><th>Course</th><th>Semester</th><th>Gender</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php $i=1; while($s=mysqli_fetch_assoc($students)): ?>
            <tr>
              <td><?=$i++?></td>
              <td><span class="badge badge-info"><?=htmlspecialchars($s['student_id'])?></span></td>
              <td><strong><?=htmlspecialchars($s['name'])?></strong></td>
              <td style="font-size:13px;color:var(--muted)"><?=htmlspecialchars($s['email'])?></td>
              <td><span class="badge badge-primary"><?=$s['course_code']?:'N/A'?></span></td>
              <td><?=htmlspecialchars($s['semester'])?:'-'?></td>
              <td><?=htmlspecialchars($s['gender'])?:'-'?></td>
              <td>
                <a href="students.php?toggle=<?=$s['id']?>" class="badge <?=$s['status']==='active'?'badge-success':'badge-danger'?>" style="cursor:pointer">
                  <?=$s['status']?>
                </a>
              </td>
              <td style="font-size:12px;color:var(--muted)"><?=date('d M Y',strtotime($s['created_at']))?></td>
              <td>
                <a href="edit_student.php?id=<?=$s['id']?>" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                <a href="#" onclick="confirmDelete('students.php?delete=<?=$s['id']?>','Delete this student?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
            <?php endwhile; ?>
            <?php if($total==0): ?><tr><td colspan="10" style="text-align:center;color:var(--muted);padding:30px">No students found.</td></tr><?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
