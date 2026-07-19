<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid=(int)$_SESSION['student_id'];
$results=mysqli_query($conn,"SELECT r.*,e.exam_name,c.course_code FROM result_tbl r LEFT JOIN exam_tbl e ON r.exam_id=e.id LEFT JOIN course_tbl c ON e.course_id=c.id WHERE r.student_id=$sid ORDER BY r.exam_date DESC");
$avgPct=mysqli_fetch_row(mysqli_query($conn,"SELECT COALESCE(AVG(percentage),0) FROM result_tbl WHERE student_id=$sid"))[0];
$passCount=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM result_tbl WHERE student_id=$sid AND status='Pass'"))[0];
$total=mysqli_num_rows($results);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Results - CIMAGE</title>
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
        <h2 style="font-size:20px;font-weight:700">My Results</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['student_name'],0,1))?></div><span><?=$_SESSION['student_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="stats-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="stat-card blue"><div class="stat-icon"><i class="bi bi-journal-text"></i></div><div><h3><?=$total?></h3><p>Total Exams Taken</p></div></div>
        <div class="stat-card green"><div class="stat-icon"><i class="bi bi-check-circle"></i></div><div><h3><?=$passCount?></h3><p>Passed</p></div></div>
        <div class="stat-card orange"><div class="stat-icon"><i class="bi bi-graph-up"></i></div><div><h3><?=round($avgPct,1)?>%</h3><p>Average Score</p></div></div>
      </div>
      <div class="card">
        <div class="card-header"><h3><i class="bi bi-bar-chart-fill" style="color:var(--primary)"></i> All Results</h3></div>
        <div class="table-responsive">
          <table class="data-table">
            <thead><tr><th>#</th><th>Exam Name</th><th>Course</th><th>Score</th><th>Percentage</th><th>Grade</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            <?php $i=1; mysqli_data_seek($results,0); while($r=mysqli_fetch_assoc($results)): ?>
            <tr>
              <td><?=$i++?></td>
              <td><strong><?=htmlspecialchars($r['exam_name'])?></strong></td>
              <td><span class="badge badge-primary"><?=$r['course_code']?></span></td>
              <td><?=$r['correct_answers']?>/<?=$r['total_questions']?></td>
              <td>
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="width:80px;height:6px;background:#e9ecef;border-radius:4px">
                    <div style="width:<?=$r['percentage']?>%;height:100%;background:<?=$r['status']==='Pass'?'var(--success)':'var(--danger)'?>;border-radius:4px"></div>
                  </div>
                  <span style="font-size:13px;font-weight:600"><?=$r['percentage']?>%</span>
                </div>
              </td>
              <td><span class="badge badge-info"><?=$r['grade']?></span></td>
              <td><span class="badge <?=$r['status']==='Pass'?'badge-success':'badge-danger'?>"><?=$r['status']?></span></td>
              <td style="font-size:12px;color:var(--muted)"><?=date('d M Y',strtotime($r['exam_date']))?></td>
              <td><a href="view_result.php?id=<?=$r['id']?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i> View</a></td>
            </tr>
            <?php endwhile; ?>
            <?php if($total==0): ?><tr><td colspan="9" style="text-align:center;color:var(--muted);padding:40px">No results yet. <a href="my_exams.php" style="color:var(--primary)">Take an exam!</a></td></tr><?php endif; ?>
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
