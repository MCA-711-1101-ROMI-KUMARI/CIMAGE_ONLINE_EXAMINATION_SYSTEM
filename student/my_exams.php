<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid=(int)$_SESSION['student_id'];
$student=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM student_tbl WHERE id=$sid"));
$exams=mysqli_query($conn,"SELECT e.*,c.course_code,c.course_name,(SELECT id FROM result_tbl WHERE student_id=$sid AND exam_id=e.id LIMIT 1) as result_id FROM exam_tbl e LEFT JOIN course_tbl c ON e.course_id=c.id WHERE e.course_id=".($student['course_id']??0)." AND e.status='active' ORDER BY e.exam_date");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Exams - CIMAGE</title>
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
        <h2 style="font-size:20px;font-weight:700">My Exams</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['student_name'],0,1))?></div><span><?=$_SESSION['student_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px">
        <?php $found=false; while($e=mysqli_fetch_assoc($exams)): $found=true; ?>
        <div class="card">
          <div style="background:linear-gradient(135deg,#1a3c8f,#2a52b8);padding:20px;color:#fff;border-radius:12px 12px 0 0">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
              <div>
                <span style="background:rgba(255,255,255,.2);padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600"><?=$e['course_code']?></span>
                <h3 style="font-size:18px;font-weight:700;margin-top:10px;color:#fff"><?=htmlspecialchars($e['exam_name'])?></h3>
              </div>
              <i class="bi bi-journal-text" style="font-size:32px;opacity:.4"></i>
            </div>
          </div>
          <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
              <?php foreach([['bi-question-circle','Questions',$e['total_questions']],['bi-clock','Duration',$e['duration'].' min'],['bi-award','Total Marks',$e['total_marks']],['bi-check-circle','Passing',$e['passing_marks'].'/'.$e['total_marks']]] as $info): ?>
              <div style="display:flex;align-items:center;gap:8px;font-size:13px">
                <i class="bi <?=$info[0]?>" style="color:var(--primary)"></i>
                <div><div style="color:var(--muted);font-size:11px"><?=$info[1]?></div><strong><?=$info[2]?></strong></div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php if($e['exam_date']): ?>
            <div style="background:#f0f4f8;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px">
              <i class="bi bi-calendar3" style="color:var(--secondary)"></i>
              <?=date('d M Y',strtotime($e['exam_date']))?> &nbsp;|&nbsp;
              <i class="bi bi-clock" style="color:var(--info)"></i>
              <?=$e['start_time']?$e['start_time']:'Flexible'?>
            </div>
            <?php endif; ?>
            <?php if($e['result_id']): ?>
              <div style="display:flex;gap:8px">
                <span class="badge badge-success" style="padding:8px 14px;font-size:13px"><i class="bi bi-check-circle"></i> Completed</span>
                <a href="view_result.php?id=<?=$e['result_id']?>" class="btn btn-info btn-sm">View Result</a>
              </div>
            <?php else: ?>
              <a href="start_exam.php?exam_id=<?=$e['id']?>" class="btn btn-primary" style="width:100%;justify-content:center" onclick="return confirm('Are you ready to start the exam?\n\nDuration: <?=$e['duration']?> minutes\nQuestions: <?=$e['total_questions']?>\n\nMake sure you are in a quiet place. The timer will start immediately.')">
                <i class="bi bi-play-circle-fill"></i> Start Exam
              </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endwhile; ?>
        <?php if(!$found): ?>
        <div class="card" style="grid-column:1/-1">
          <div class="card-body" style="text-align:center;padding:60px">
            <i class="bi bi-journal-x" style="font-size:48px;color:var(--muted);display:block;margin-bottom:16px"></i>
            <h3 style="color:var(--muted)">No Exams Available</h3>
            <p style="color:var(--muted);margin-top:8px">No active exams for your course right now. Check back later!</p>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
