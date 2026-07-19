<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();

$totalStudents = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM student_tbl"))[0];
$totalExams    = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM exam_tbl"))[0];
$totalQuestions= mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM question_tbl"))[0];
$totalResults  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM result_tbl"))[0];

$activities = mysqli_query($conn,"SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 6");
$recentStudents = mysqli_query($conn,"SELECT s.*,c.course_code FROM student_tbl s LEFT JOIN course_tbl c ON s.course_id=c.id ORDER BY s.created_at DESC LIMIT 5");
$courseStats = mysqli_query($conn,"SELECT c.course_code, COUNT(s.id) as total FROM course_tbl c LEFT JOIN student_tbl s ON c.id=s.course_id GROUP BY c.id ORDER BY total DESC");
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - CIMAGE</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div id="pageLoader" class="page-loader">
  <div class="loader-text">CIMAGE<br><span style="font-size:14px;font-weight:400;opacity:.7">Loading Dashboard...</span></div>
  <div class="loader-bar"><div class="loader-fill"></div></div>
</div>

<div class="dashboard-wrapper">
  <?php include 'sidebar.php'; ?>
  <div class="main-content">
    <!-- TOPBAR -->
    <div class="topbar">
      <div style="display:flex;align-items:center;gap:14px">
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <div>
          <h2 class="topbar-left" style="font-size:20px;font-weight:700;">Dashboard</h2>
          <p style="font-size:12px;color:var(--muted)">Welcome back, <?= htmlspecialchars($adminName) ?>!</p>
        </div>
      </div>
      <div class="topbar-right">
        <div style="font-size:13px;color:var(--muted)"><i class="bi bi-clock"></i> <span id="liveClock"></span></div>
        <div class="user-badge">
          <div class="user-avatar"><?= strtoupper(substr($adminName,0,1)) ?></div>
          <span><?= htmlspecialchars($adminName) ?></span>
          <small style="color:var(--muted);font-size:11px">Administrator</small>
        </div>
      </div>
    </div>

    <div class="content-area">
      <!-- STATS -->
      <div class="stats-grid">
        <div class="stat-card blue">
          <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
          <div><h3><?= number_format($totalStudents) ?></h3><p>Total Students</p></div>
        </div>
        <div class="stat-card orange">
          <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
          <div><h3><?= number_format($totalExams) ?></h3><p>Total Exams</p></div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon"><i class="bi bi-question-circle-fill"></i></div>
          <div><h3><?= number_format($totalQuestions) ?></h3><p>Total Questions</p></div>
        </div>
        <div class="stat-card red">
          <div class="stat-icon"><i class="bi bi-bar-chart-fill"></i></div>
          <div><h3><?= number_format($totalResults) ?></h3><p>Total Results</p></div>
        </div>
      </div>

      <!-- CHARTS -->
      <div class="charts-grid">
        <div class="card">
          <div class="card-header">
            <h3><i class="bi bi-graph-up" style="color:var(--primary)"></i> Exam Performance Overview</h3>
          </div>
          <div class="card-body"><canvas id="perfChart" height="100"></canvas></div>
        </div>
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-pie-chart-fill" style="color:var(--secondary)"></i> Top Courses</h3></div>
          <div class="card-body">
            <canvas id="courseChart" height="160"></canvas>
            <div style="margin-top:12px">
              <?php mysqli_data_seek($courseStats,0); while($c=mysqli_fetch_assoc($courseStats)): ?>
              <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                <span><?=$c['course_code']?></span><span class="badge badge-primary"><?=$c['total']?> students</span>
              </div>
              <?php endwhile; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- RECENT ACTIVITY + SYSTEM INFO -->
      <div class="grid-2">
        <div class="card">
          <div class="card-header">
            <h3><i class="bi bi-activity" style="color:var(--success)"></i> Recent Activity</h3>
          </div>
          <div class="table-responsive">
            <table class="data-table">
              <thead><tr><th>Action</th><th>User</th><th>Time</th></tr></thead>
              <tbody>
              <?php while($a=mysqli_fetch_assoc($activities)): ?>
              <tr>
                <td><?= htmlspecialchars($a['action']) ?></td>
                <td><?= htmlspecialchars($a['user_name']) ?></td>
                <td style="font-size:12px;color:var(--muted)"><?= date('d-m-Y H:i', strtotime($a['created_at'])) ?></td>
              </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-info-circle" style="color:var(--info)"></i> System Information</h3></div>
          <div class="card-body">
            <?php $sysInfo=[['Server Time',date('d-m-Y H:i:s')],['PHP Version',phpversion()],['Database','MySQL'],['System Status','Online']]; ?>
            <?php foreach($sysInfo as $si): ?>
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);font-size:14px">
              <span style="color:var(--muted)"><?=$si[0]?></span>
              <span style="font-weight:600;color:<?=$si[0]==='System Status'?'var(--success)':'var(--text)'?>"><?=$si[1]?></span>
            </div>
            <?php endforeach; ?>
            <div style="margin-top:16px">
              <a href="students.php" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;margin-bottom:8px"><i class="bi bi-people"></i> Manage Students</a>
              <a href="add_question.php" class="btn btn-success btn-sm" style="width:100%;justify-content:center"><i class="bi bi-plus-circle"></i> Add Question</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="../js/main.js"></script>
<script>
// Live Clock
setInterval(()=>{document.getElementById('liveClock').textContent=new Date().toLocaleTimeString();},1000);

// Performance Chart
new Chart(document.getElementById('perfChart'), {
  type:'line',
  data:{
    labels:['Jan','Feb','Mar','Apr','May','Jun'],
    datasets:[
      {label:'Exams Conducted',data:[5,8,6,12,10,15],borderColor:'#1a3c8f',backgroundColor:'rgba(26,60,143,.1)',tension:.4,fill:true},
      {label:'Students Appeared',data:[40,65,50,90,80,110],borderColor:'#f5a623',backgroundColor:'rgba(245,166,35,.08)',tension:.4,fill:true}
    ]
  },
  options:{responsive:true,plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true}}}
});

// Course Pie Chart
<?php
mysqli_data_seek($courseStats,0);
$labels=[]; $data=[];
while($c=mysqli_fetch_assoc($courseStats)){$labels[]="'".$c['course_code']."'";$data[]=$c['total'];}
?>
new Chart(document.getElementById('courseChart'),{
  type:'doughnut',
  data:{
    labels:[<?=implode(',',$labels)?>],
    datasets:[{data:[<?=implode(',',$data)?>],backgroundColor:['#e74c3c','#3498db','#f5a623','#9b59b6','#27ae60','#e67e22']}]
  },
  options:{responsive:true,plugins:{legend:{display:false}},cutout:'65%'}
});
</script>
</body>
</html>
