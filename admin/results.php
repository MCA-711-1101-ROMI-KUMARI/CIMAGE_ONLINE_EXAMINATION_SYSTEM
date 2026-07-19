<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();
$results=mysqli_query($conn,"SELECT r.*,s.name as student_name,s.student_id,e.exam_name,c.course_code FROM result_tbl r LEFT JOIN student_tbl s ON r.student_id=s.id LEFT JOIN exam_tbl e ON r.exam_id=e.id LEFT JOIN course_tbl c ON e.course_id=c.id ORDER BY r.exam_date DESC");
$total=mysqli_num_rows($results);
$stats=mysqli_fetch_assoc(mysqli_query($conn,"SELECT AVG(percentage) as avg_pct, MAX(percentage) as max_pct, COUNT(CASE WHEN status='Pass' THEN 1 END) as pass_count FROM result_tbl"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Results - CIMAGE Admin</title>
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
        <h2 style="font-size:20px;font-weight:700">All Results</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="stats-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="stat-card blue"><div class="stat-icon"><i class="bi bi-bar-chart"></i></div><div><h3><?=$total?></h3><p>Total Results</p></div></div>
        <div class="stat-card green"><div class="stat-icon"><i class="bi bi-check-circle"></i></div><div><h3><?=$stats['pass_count']?></h3><p>Pass</p></div></div>
        <div class="stat-card orange"><div class="stat-icon"><i class="bi bi-percent"></i></div><div><h3><?=round($stats['avg_pct'],1)?>%</h3><p>Avg Score</p></div></div>
      </div>
      <div class="card">
        <div class="card-header">
          <h3><i class="bi bi-bar-chart-fill" style="color:var(--primary)"></i> Exam Results</h3>
          <div class="search-box"><i class="bi bi-search" style="color:var(--muted)"></i><input type="text" id="searchInput" placeholder="Search results..."></div>
        </div>
        <div class="table-responsive">
          <table class="data-table" id="resultsTable">
            <thead><tr><th>#</th><th>Student</th><th>Exam</th><th>Course</th><th>Score</th><th>Percentage</th><th>Correct</th><th>Wrong</th><th>Grade</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php $i=1; while($r=mysqli_fetch_assoc($results)): ?>
            <tr>
              <td><?=$i++?></td>
              <td><strong><?=htmlspecialchars($r['student_name'])?></strong><br><small style="color:var(--muted)"><?=htmlspecialchars($r['student_id'])?></small></td>
              <td style="font-size:13px"><?=htmlspecialchars($r['exam_name'])?></td>
              <td><span class="badge badge-primary"><?=htmlspecialchars($r['course_code'])?></span></td>
              <td><strong><?=$r['score']?>/<?=$r['total_marks']?></strong></td>
              <td>
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="flex:1;height:6px;background:#e9ecef;border-radius:4px"><div style="width:<?=$r['percentage']?>%;height:100%;background:<?=$r['status']==='Pass'?'var(--success)':'var(--danger)'?>;border-radius:4px"></div></div>
                  <span style="font-size:12px;font-weight:600"><?=$r['percentage']?>%</span>
                </div>
              </td>
              <td style="color:var(--success);font-weight:600"><?=$r['correct_answers']?></td>
              <td style="color:var(--danger);font-weight:600"><?=$r['wrong_answers']?></td>
              <td><span class="badge badge-info"><?=$r['grade']?></span></td>
              <td><span class="badge <?=$r['status']==='Pass'?'badge-success':'badge-danger'?>"><?=$r['status']?></span></td>
              <td style="font-size:12px;color:var(--muted)"><?=date('d M Y',strtotime($r['exam_date']))?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
<script>
document.getElementById('searchInput').addEventListener('keyup',function(){
  const v=this.value.toLowerCase();
  document.querySelectorAll('#resultsTable tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(v)?'':'none'});
});
</script>
</body>
</html>
