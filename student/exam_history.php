<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid=(int)$_SESSION['student_id'];
$history=mysqli_query($conn,"SELECT r.*,e.exam_name,e.duration,c.course_code FROM result_tbl r LEFT JOIN exam_tbl e ON r.exam_id=e.id LEFT JOIN course_tbl c ON e.course_id=c.id WHERE r.student_id=$sid ORDER BY r.exam_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exam History - CIMAGE</title>
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
        <h2 style="font-size:20px;font-weight:700">Exam History</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['student_name'],0,1))?></div><span><?=$_SESSION['student_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="card">
        <div class="card-header"><h3><i class="bi bi-clock-history" style="color:var(--primary)"></i> All Exam Attempts</h3></div>
        <div class="table-responsive">
          <table class="data-table">
            <thead><tr><th>#</th><th>Exam Name</th><th>Course</th><th>Questions</th><th>Attempted</th><th>Correct</th><th>Score</th><th>Status</th><th>Date & Time</th><th>Action</th></tr></thead>
            <tbody>
            <?php $i=1; $found=false; while($r=mysqli_fetch_assoc($history)): $found=true; ?>
            <tr>
              <td><?=$i++?></td>
              <td><strong><?=htmlspecialchars($r['exam_name'])?></strong></td>
              <td><span class="badge badge-primary"><?=$r['course_code']?></span></td>
              <td><?=$r['total_questions']?></td>
              <td><?=$r['attempted']?></td>
              <td style="color:var(--success);font-weight:600"><?=$r['correct_answers']?></td>
              <td><strong><?=$r['percentage']?>%</strong></td>
              <td><span class="badge <?=$r['status']==='Pass'?'badge-success':'badge-danger'?>"><?=$r['status']?></span></td>
              <td style="font-size:12px;color:var(--muted)"><?=date('d M Y H:i',strtotime($r['exam_date']))?></td>
              <td><a href="view_result.php?id=<?=$r['id']?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> View</a></td>
            </tr>
            <?php endwhile; ?>
            <?php if(!$found): ?><tr><td colspan="10" style="text-align:center;color:var(--muted);padding:40px">No exam history found. <a href="my_exams.php" style="color:var(--primary)">Take an exam!</a></td></tr><?php endif; ?>
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
