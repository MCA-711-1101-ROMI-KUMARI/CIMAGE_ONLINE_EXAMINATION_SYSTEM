<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();
$courseStats=mysqli_query($conn,"SELECT c.course_code,c.course_name,COUNT(DISTINCT s.id) as students,COUNT(DISTINCT e.id) as exams,COUNT(DISTINCT r.id) as results,AVG(r.percentage) as avg_pct FROM course_tbl c LEFT JOIN student_tbl s ON c.id=s.course_id LEFT JOIN exam_tbl e ON c.id=e.course_id LEFT JOIN result_tbl r ON e.id=r.exam_id GROUP BY c.id ORDER BY students DESC");
$topStudents=mysqli_query($conn,"SELECT s.name,s.student_id,c.course_code,AVG(r.percentage) as avg_pct,COUNT(r.id) as exams_taken FROM student_tbl s LEFT JOIN result_tbl r ON s.id=r.student_id LEFT JOIN course_tbl c ON s.course_id=c.id GROUP BY s.id HAVING exams_taken>0 ORDER BY avg_pct DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports - CIMAGE Admin</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="dashboard-wrapper">
  <?php include 'sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div style="display:flex;align-items:center;gap:14px">
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <h2 style="font-size:20px;font-weight:700">Reports & Analytics</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-book-fill" style="color:var(--primary)"></i> Course-wise Report</h3></div>
          <div class="table-responsive">
            <table class="data-table">
              <thead><tr><th>Course</th><th>Students</th><th>Exams</th><th>Results</th><th>Avg %</th></tr></thead>
              <tbody>
              <?php while($c=mysqli_fetch_assoc($courseStats)): ?>
              <tr>
                <td><span class="badge badge-primary"><?=$c['course_code']?></span> <span style="font-size:13px"><?=$c['course_name']?></span></td>
                <td><?=$c['students']?></td>
                <td><?=$c['exams']?></td>
                <td><?=$c['results']?></td>
                <td><strong><?=round($c['avg_pct'],1)?>%</strong></td>
              </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-trophy-fill" style="color:var(--warning)"></i> Top Performing Students</h3></div>
          <div class="table-responsive">
            <table class="data-table">
              <thead><tr><th>Rank</th><th>Student</th><th>Course</th><th>Exams</th><th>Avg %</th></tr></thead>
              <tbody>
              <?php $i=1; while($s=mysqli_fetch_assoc($topStudents)): ?>
              <tr>
                <td><?php if($i<=3) echo "<span class='badge badge-warning'>#$i</span>"; else echo "#$i"; $i++; ?></td>
                <td><strong><?=htmlspecialchars($s['name'])?></strong><br><small style="color:var(--muted)"><?=$s['student_id']?></small></td>
                <td><span class="badge badge-primary"><?=$s['course_code']?></span></td>
                <td><?=$s['exams_taken']?></td>
                <td style="font-weight:700;color:<?=$s['avg_pct']>=75?'var(--success)':'var(--warning)'>"> <?=round($s['avg_pct'],1)?>%</td>
              </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="card mt-20">
        <div class="card-header"><h3><i class="bi bi-bar-chart-fill" style="color:var(--info)"></i> Performance Chart</h3></div>
        <div class="card-body"><canvas id="barChart" height="80"></canvas></div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
<script>
new Chart(document.getElementById('barChart'),{
  type:'bar',
  data:{
    labels:['BCA','BBA','B.COM','BSC.IT','MBA','MCA'],
    datasets:[{label:'Avg Score %',data:[75,68,72,65,80,77],backgroundColor:['#1a3c8f','#3498db','#f5a623','#9b59b6','#27ae60','#e67e22']}]
  },
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,max:100}}}
});
</script>
</body>
</html>
