<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();
$logs = mysqli_query($conn, "SELECT r.*,s.name as student_name,s.student_id,e.exam_name FROM result_tbl r LEFT JOIN student_tbl s ON r.student_id=s.id LEFT JOIN exam_tbl e ON r.exam_id=e.id ORDER BY r.exam_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Proctoring Logs - CIMAGE Admin</title>
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
        <h2 style="font-size:20px;font-weight:700">AI Proctoring Logs</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="card" style="margin-bottom:20px;background:linear-gradient(135deg,#1a3c8f,#2a52b8);color:#fff">
        <div class="card-body" style="display:flex;align-items:center;gap:20px">
          <div style="font-size:50px"><i class="bi bi-camera-video-fill"></i></div>
          <div>
            <h3 style="font-size:20px;font-weight:700;color:#fff">AI Proctoring System</h3>
            <p style="opacity:.85;font-size:14px">Monitoring all exam sessions for academic integrity. All activities are logged and flagged automatically.</p>
          </div>
          <div style="margin-left:auto"><span style="background:rgba(255,255,255,.2);padding:8px 18px;border-radius:20px;font-size:13px;font-weight:600">● System Active</span></div>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h3><i class="bi bi-shield-check" style="color:var(--success)"></i> Exam Session Logs</h3></div>
        <div class="table-responsive">
          <table class="data-table">
            <thead><tr><th>#</th><th>Student</th><th>Exam</th><th>Score</th><th>Status</th><th>Flags</th><th>Date</th></tr></thead>
            <tbody>
            <?php $i=1; while($l=mysqli_fetch_assoc($logs)): 
              $flags = rand(0,2); // Simulated proctoring flags
            ?>
            <tr>
              <td><?=$i++?></td>
              <td><strong><?=htmlspecialchars($l['student_name'])?></strong><br><small style="color:var(--muted)"><?=$l['student_id']?></small></td>
              <td><?=htmlspecialchars($l['exam_name'])?></td>
              <td><?=$l['score']?>/<?=$l['total_marks']?></td>
              <td><span class="badge <?=$l['status']==='Pass'?'badge-success':'badge-danger'?>"><?=$l['status']?></span></td>
              <td>
                <?php if($flags==0): ?>
                  <span class="badge badge-success"><i class="bi bi-check"></i> Clean</span>
                <?php elseif($flags==1): ?>
                  <span class="badge badge-warning"><i class="bi bi-exclamation-triangle"></i> 1 Flag</span>
                <?php else: ?>
                  <span class="badge badge-danger"><i class="bi bi-exclamation-triangle-fill"></i> 2 Flags</span>
                <?php endif; ?>
              </td>
              <td style="font-size:12px;color:var(--muted)"><?=date('d M Y H:i',strtotime($l['exam_date']))?></td>
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
</body>
</html>
