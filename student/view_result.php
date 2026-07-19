<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid=(int)$_SESSION['student_id'];
$rid=(int)($_GET['id']??0);
$result=mysqli_fetch_assoc(mysqli_query($conn,"SELECT r.*,e.exam_name,e.total_questions as exam_tq,c.course_code,c.course_name,s.name as student_name,s.student_id FROM result_tbl r LEFT JOIN exam_tbl e ON r.exam_id=e.id LEFT JOIN course_tbl c ON e.course_id=c.id LEFT JOIN student_tbl s ON r.student_id=s.id WHERE r.id=$rid AND r.student_id=$sid"));
if(!$result) redirect('results.php');
$isPass=$result['status']==='Pass';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Result - <?=htmlspecialchars($result['exam_name'])?></title>
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
        <div>
          <h2 style="font-size:20px;font-weight:700">Result Details</h2>
          <p style="font-size:12px;color:var(--muted)">Home / Results / <?=htmlspecialchars($result['exam_name'])?></p>
        </div>
      </div>
      <div class="topbar-right">
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Print</button>
        <div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['student_name'],0,1))?></div><span><?=$_SESSION['student_name']?></span></div>
      </div>
    </div>
    <div class="content-area">
      <!-- Result Header -->
      <div class="card mb-20" style="background:linear-gradient(135deg,#0f2660,#1a3c8f);color:#fff">
        <div class="card-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap">
          <div style="font-size:48px"><i class="bi bi-journal-check"></i></div>
          <div style="flex:1">
            <h2 style="font-size:22px;font-weight:800;color:#fff"><?=htmlspecialchars($result['exam_name'])?></h2>
            <p style="opacity:.8;margin-top:4px"><?=htmlspecialchars($result['course_code'])?> - <?=htmlspecialchars($result['course_name'])?></p>
            <p style="font-size:13px;opacity:.7;margin-top:4px"><i class="bi bi-calendar3"></i> <?=date('d-m-Y',strtotime($result['exam_date']))?></p>
          </div>
          <div style="text-align:right">
            <div style="font-size:14px;opacity:.8">Student ID</div>
            <div style="font-size:20px;font-weight:800;color:#f5a623"><?=htmlspecialchars($result['student_id'])?></div>
          </div>
        </div>
      </div>

      <div class="grid-2">
        <!-- Score Card -->
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-award" style="color:var(--primary)"></i> Score Summary</h3></div>
          <div class="card-body" style="text-align:center">
            <div class="result-circle <?=$isPass?'':'fail'?>">
              <div class="score-num"><?=$result['correct_answers']?>/<?=$result['total_questions']?></div>
              <div class="pct"><?=$result['percentage']?>%</div>
            </div>
            <span class="status-badge <?=$isPass?'pass':'fail'?>">
              <i class="bi bi-<?=$isPass?'check-circle':'x-circle'?>"></i>
              Status: <?=$result['status']?>
            </span>
            <div class="result-stats">
              <div class="result-stat"><div class="val"><?=$result['total_questions']?></div><div class="lbl">Total Questions</div></div>
              <div class="result-stat"><div class="val"><?=$result['attempted']?></div><div class="lbl">Attempted</div></div>
              <div class="result-stat" style="color:var(--success)"><div class="val"><?=$result['correct_answers']?></div><div class="lbl">Correct Answers</div></div>
              <div class="result-stat" style="color:var(--danger)"><div class="val"><?=$result['wrong_answers']?></div><div class="lbl">Wrong Answers</div></div>
              <div class="result-stat"><div class="val"><?=$result['score']?>/<?=$result['total_marks']?></div><div class="lbl">Score</div></div>
              <div class="result-stat"><div class="val"><?=$result['grade']?></div><div class="lbl">Grade</div></div>
              <div class="result-stat"><div class="val"><?=$result['percentage']?>%</div><div class="lbl">Percentage</div></div>
              <div class="result-stat"><div class="val">#<?=$result['rank_position']?></div><div class="lbl">Rank</div></div>
            </div>
          </div>
        </div>

        <!-- Performance Graph -->
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-bar-chart-fill" style="color:var(--info)"></i> Performance Graph</h3></div>
          <div class="card-body">
            <canvas id="resultChart" height="200"></canvas>
            <div style="margin-top:20px">
              <?php
              $allResults=mysqli_query($conn,"SELECT percentage FROM result_tbl WHERE student_id=$sid ORDER BY exam_date DESC LIMIT 5");
              $vals=[]; while($x=mysqli_fetch_row($allResults)) $vals[]=$x[0];
              $avg=count($vals)?round(array_sum($vals)/count($vals),1):0;
              $topPct=mysqli_fetch_row(mysqli_query($conn,"SELECT MAX(percentage) FROM result_tbl WHERE exam_id=".$result['exam_id']))[0];
              ?>
              <?php foreach([['Your Score',$result['percentage'].'%','var(--primary)'],['Average',$avg.'%','var(--warning)'],['Topper',$topPct.'%','var(--success)']] as $row): ?>
              <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);font-size:14px">
                <span style="color:var(--muted)"><?=$row[0]?></span>
                <strong style="color:<?=$row[2]?>"><?=$row[1]?></strong>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:20px;flex-wrap:wrap">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-download"></i> Download PDF</button>
        <a href="dashboard.php" class="btn btn-outline"><i class="bi bi-house"></i> Back to Dashboard</a>
        <a href="results.php" class="btn btn-info"><i class="bi bi-list"></i> All Results</a>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
<script>
new Chart(document.getElementById('resultChart'),{
  type:'bar',
  data:{
    labels:['You','Average','Topper'],
    datasets:[{
      data:[<?=$result['percentage']?>,<?=$avg?>,<?=$topPct?>],
      backgroundColor:['#1a3c8f','#f5a623','#27ae60'],
      borderRadius:8
    }]
  },
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,max:100}}}
});
</script>
</body>
</html>
