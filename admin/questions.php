<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();
if(isset($_GET['delete'])){$id=(int)$_GET['delete'];mysqli_query($conn,"DELETE FROM question_tbl WHERE id=$id");$_SESSION['msg']='Question deleted.';redirect('questions.php');}
$msg=$_SESSION['msg']??'';unset($_SESSION['msg']);
$filter_exam=isset($_GET['exam_id'])?(int)$_GET['exam_id']:0;
$filter_course=isset($_GET['course_id'])?(int)$_GET['course_id']:0;
$where='WHERE 1';
if($filter_exam) $where.=" AND q.exam_id=$filter_exam";
if($filter_course) $where.=" AND q.course_id=$filter_course";
$questions=mysqli_query($conn,"SELECT q.*,c.course_code,e.exam_name FROM question_tbl q LEFT JOIN course_tbl c ON q.course_id=c.id LEFT JOIN exam_tbl e ON q.exam_id=e.id $where ORDER BY q.created_at DESC");
$total=mysqli_num_rows($questions);
$courses=mysqli_query($conn,"SELECT * FROM course_tbl WHERE status='active' ORDER BY course_code");
$exams=mysqli_query($conn,"SELECT * FROM exam_tbl ORDER BY exam_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Question Bank - CIMAGE Admin</title>
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
        <div><h2 style="font-size:20px;font-weight:700">Question Bank</h2><p style="font-size:12px;color:var(--muted)"><?=$total?> questions</p></div>
      </div>
      <div class="topbar-right">
        <a href="add_question.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Add Question</a>
        <div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div>
      </div>
    </div>
    <div class="content-area">
      <?php if($msg): ?><div class="toast-wrap"><div class="toast success"><span>✓</span><span><?=htmlspecialchars($msg)?></span></div></div><?php endif; ?>
      <div class="card">
        <div class="card-header">
          <h3><i class="bi bi-question-circle-fill" style="color:var(--primary)"></i> All Questions</h3>
          <form method="GET" style="display:flex;gap:8px;align-items:center">
            <select name="course_id" class="form-control" style="width:130px;padding:7px 10px;font-size:13px" onchange="this.form.submit()">
              <option value="">All Courses</option>
              <?php while($c=mysqli_fetch_assoc($courses)): ?>
              <option value="<?=$c['id']?>" <?=$filter_course==$c['id']?'selected':''?>><?=$c['course_code']?></option>
              <?php endwhile; ?>
            </select>
            <select name="exam_id" class="form-control" style="width:180px;padding:7px 10px;font-size:13px" onchange="this.form.submit()">
              <option value="">All Exams</option>
              <?php while($e=mysqli_fetch_assoc($exams)): ?>
              <option value="<?=$e['id']?>" <?=$filter_exam==$e['id']?'selected':''?>><?=$e['exam_name']?></option>
              <?php endwhile; ?>
            </select>
            <?php if($filter_exam||$filter_course): ?><a href="questions.php" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
          </form>
        </div>
        <div class="table-responsive">
          <table class="data-table">
            <thead><tr><th>#</th><th>Question</th><th>Course</th><th>Exam</th><th>A</th><th>B</th><th>C</th><th>D</th><th>Answer</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $i=1; while($q=mysqli_fetch_assoc($questions)): ?>
            <tr>
              <td><?=$i++?></td>
              <td style="max-width:250px"><strong><?=htmlspecialchars(substr($q['question'],0,70)).(strlen($q['question'])>70?'...':'')?></strong></td>
              <td><span class="badge badge-primary"><?=htmlspecialchars($q['course_code'])?></span></td>
              <td style="font-size:12px;color:var(--muted)"><?=htmlspecialchars(substr($q['exam_name'],0,25))?></td>
              <td style="font-size:12px"><?=htmlspecialchars(substr($q['option_a'],0,20))?></td>
              <td style="font-size:12px"><?=htmlspecialchars(substr($q['option_b'],0,20))?></td>
              <td style="font-size:12px"><?=htmlspecialchars(substr($q['option_c'],0,20))?></td>
              <td style="font-size:12px"><?=htmlspecialchars(substr($q['option_d'],0,20))?></td>
              <td><span class="badge badge-success"><?=$q['correct_answer']?></span></td>
              <td>
                <a href="edit_question.php?id=<?=$q['id']?>" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                <a href="#" onclick="confirmDelete('questions.php?delete=<?=$q['id']?>','Delete this question?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
            <?php endwhile; ?>
            <?php if($total==0): ?><tr><td colspan="10" style="text-align:center;color:var(--muted);padding:30px">No questions found.</td></tr><?php endif; ?>
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
