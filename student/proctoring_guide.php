<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Proctoring Guide - CIMAGE</title>
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
        <h2 style="font-size:20px;font-weight:700">AI Proctoring Guide</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['student_name'],0,1))?></div><span><?=$_SESSION['student_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="card" style="margin-bottom:20px;background:linear-gradient(135deg,#1a3c8f,#2a52b8);color:#fff">
        <div class="card-body" style="display:flex;align-items:center;gap:20px">
          <i class="bi bi-shield-check" style="font-size:52px;opacity:.8"></i>
          <div>
            <h3 style="font-size:20px;font-weight:700;color:#fff">CIMAGE AI Proctoring System</h3>
            <p style="opacity:.85;font-size:14px">Our AI ensures exam integrity while providing a comfortable experience for honest students.</p>
          </div>
        </div>
      </div>
      <div class="grid-3">
        <?php
        $rules=[
          ['bi-camera-video','Camera Required','Ensure your webcam is working and properly placed before starting the exam.','var(--primary)'],
          ['bi-eye','Face Detection','Keep your face visible throughout the exam. Looking away too often may trigger a flag.','var(--info)'],
          ['bi-person-check','Solo Appearance','Only one person should appear in the camera frame during the exam.','var(--success)'],
          ['bi-browser','Browser Stay','Do not switch browser tabs or minimize the window during the exam.','var(--warning)'],
          ['bi-volume-mute','Quiet Environment','Take the exam in a quiet, well-lit room. Background noise is monitored.','var(--secondary)'],
          ['bi-phone','No Mobile','Mobile phones or secondary devices should not be visible on camera.','var(--danger)'],
        ];
        foreach($rules as $r):
        ?>
        <div class="card">
          <div class="card-body" style="text-align:center;padding:30px 20px">
            <div style="width:64px;height:64px;border-radius:16px;background:<?=$r[3]?>;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
              <i class="bi <?=$r[0]?>" style="font-size:26px;color:#fff"></i>
            </div>
            <h3 style="font-size:15px;font-weight:700;margin-bottom:8px"><?=$r[1]?></h3>
            <p style="font-size:13px;color:var(--muted);line-height:1.6"><?=$r[2]?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="card mt-20">
        <div class="card-header"><h3><i class="bi bi-info-circle-fill" style="color:var(--info)"></i> Important Notes</h3></div>
        <div class="card-body">
          <ul style="font-size:14px;line-height:2.2;padding-left:20px;color:var(--text)">
            <li>The AI proctoring system is always active during live exams.</li>
            <li>Suspicious behavior is automatically flagged and reviewed by administrators.</li>
            <li>Multiple violations may result in exam cancellation.</li>
            <li>If you face technical issues, contact the exam administrator immediately.</li>
            <li>Ensure stable internet connection throughout the exam.</li>
            <li>All exam sessions are recorded and stored securely.</li>
          </ul>
          <div style="margin-top:20px">
            <a href="my_exams.php" class="btn btn-primary"><i class="bi bi-play-circle"></i> Go to My Exams</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
