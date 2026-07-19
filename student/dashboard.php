<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid = (int)$_SESSION['student_id'];
$student = mysqli_fetch_assoc(mysqli_query($conn,"SELECT s.*,c.course_code,c.course_name FROM student_tbl s LEFT JOIN course_tbl c ON s.course_id=c.id WHERE s.id=$sid"));

// Stats
$totalExams = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM exam_tbl WHERE course_id=".($student['course_id']??0)." AND status='active'"))[0];
$completedExams = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM result_tbl WHERE student_id=$sid"))[0];
$avgScore = mysqli_fetch_row(mysqli_query($conn,"SELECT COALESCE(AVG(percentage),0) FROM result_tbl WHERE student_id=$sid"))[0];
$rank = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*)+1 FROM (SELECT student_id,AVG(percentage) as avg FROM result_tbl GROUP BY student_id HAVING avg > (SELECT COALESCE(AVG(percentage),0) FROM result_tbl WHERE student_id=$sid)) t"))[0];
$totalStudents = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM student_tbl"))[0];
$recentResults = mysqli_query($conn,"SELECT r.*,e.exam_name,c.course_code FROM result_tbl r LEFT JOIN exam_tbl e ON r.exam_id=e.id LEFT JOIN course_tbl c ON e.course_id=c.id WHERE r.student_id=$sid ORDER BY r.exam_date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard - CIMAGE</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div id="pageLoader" class="page-loader">
  <div class="loader-text">CIMAGE<br><span style="font-size:14px;font-weight:400;opacity:.7">Loading...</span></div>
  <div class="loader-bar"><div class="loader-fill"></div></div>
</div>
<div class="dashboard-wrapper">
  <?php include 'sidebar.php'; ?>
  <div class="main-content">
    <div class="topbar">
      <div style="display:flex;align-items:center;gap:14px">
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <div>
          <h2 style="font-size:20px;font-weight:700">Welcome, <?=htmlspecialchars($student['name'])?>!</h2>
          <p style="font-size:12px;color:var(--muted)">Ready to learn and achieve!</p>
        </div>
      </div>
      <div class="topbar-right">
        <div class="user-badge">
          <div class="user-avatar"><?=strtoupper(substr($student['name'],0,1))?></div>
          <span><?=htmlspecialchars($student['name'])?></span>
          <small style="color:var(--muted);font-size:11px"><?=$student['course_code']?></small>
        </div>
      </div>
    </div>
    <div class="content-area">
      <!-- STATS -->
      <div class="stats-grid">
        <div class="stat-card blue">
          <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
          <div><h3><?=$totalExams?></h3><p>Upcoming Exams</p></div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
          <div><h3><?=$completedExams?></h3><p>Completed Exams</p></div>
        </div>
        <div class="stat-card orange">
          <div class="stat-icon"><i class="bi bi-percent"></i></div>
          <div><h3><?=round($avgScore,1)?>%</h3><p>Average Score</p></div>
        </div>
        <div class="stat-card purple">
          <div class="stat-icon"><i class="bi bi-trophy"></i></div>
          <div><h3><?=$rank?>/<?=$totalStudents?></h3><p>Rank</p></div>
        </div>
      </div>

      <!-- QUICK ACTIONS -->
      <div class="card mb-20">
        <div class="card-body" style="display:flex;gap:12px;flex-wrap:wrap">
          <a href="my_exams.php" class="btn btn-primary"><i class="bi bi-play-circle"></i> Start Exam</a>
          <a href="results.php" class="btn btn-success"><i class="bi bi-bar-chart"></i> My Results</a>
          <a href="exam_history.php" class="btn btn-info"><i class="bi bi-clock-history"></i> Exam History</a>
          <a href="profile.php" class="btn btn-warning"><i class="bi bi-person"></i> View Profile</a>
        </div>
      </div>

      <div class="grid-2">
        <!-- RECENT EXAMS -->
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-clock-history" style="color:var(--primary)"></i> Recent Exams</h3></div>
          <div class="table-responsive">
            <table class="data-table">
              <thead><tr><th>Exam Name</th><th>Course</th><th>Date</th><th>Status</th></tr></thead>
              <tbody>
              <?php
              mysqli_data_seek($recentResults,0);
              if(mysqli_num_rows($recentResults)>0):
              while($r=mysqli_fetch_assoc($recentResults)):
              ?>
              <tr>
                <td><a href="view_result.php?id=<?=$r['id']?>" style="color:var(--primary);font-weight:600"><?=htmlspecialchars($r['exam_name'])?></a></td>
                <td><span class="badge badge-primary"><?=$r['course_code']?></span></td>
                <td style="font-size:12px;color:var(--muted)"><?=date('d-m-Y',strtotime($r['exam_date']))?></td>
                <td><span class="badge <?=$r['status']==='Pass'?'badge-success':'badge-danger'?>"><?=$r['status']?></span></td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:30px">No exams taken yet. <a href="my_exams.php" style="color:var(--primary)">Start your first exam!</a></td></tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PERFORMANCE CHART -->
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-bar-chart" style="color:var(--success)"></i> Performance Overview</h3></div>
          <div class="card-body"><canvas id="perfChart" height="200"></canvas></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
<script>
<?php
$chartLabels=[]; $chartData=[];
$res=mysqli_query($conn,"SELECT e.exam_name,r.percentage FROM result_tbl r LEFT JOIN exam_tbl e ON r.exam_id=e.id WHERE r.student_id=$sid ORDER BY r.exam_date DESC LIMIT 5");
while($row=mysqli_fetch_assoc($res)){$chartLabels[]="'".substr($row['exam_name'],0,12)."'";$chartData[]=$row['percentage'];}
if(empty($chartData)){$chartLabels=["'No Data'"]; $chartData=[0];}
?>
new Chart(document.getElementById('perfChart'),{
  type:'bar',
  data:{
    labels:[<?=implode(',',$chartLabels)?>],
    datasets:[{label:'Score %',data:[<?=implode(',',$chartData)?>],backgroundColor:'rgba(26,60,143,.75)',borderRadius:6}]
  },
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,max:100}}}
});
</script>
</body>
</html>
