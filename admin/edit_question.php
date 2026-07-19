<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();
$id=(int)($_GET['id']??0);
$q=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM question_tbl WHERE id=$id"));
if(!$q){redirect('questions.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    $cid=(int)($_POST['course_id']??0);
    $eid=(int)($_POST['exam_id']??0);
    $qtype=sanitize($_POST['question_type']??'MCQ');
    $question=sanitize($_POST['question']??'');
    $a=sanitize($_POST['option_a']??'');
    $b=sanitize($_POST['option_b']??'');
    $c_opt=sanitize($_POST['option_c']??'');
    $d=sanitize($_POST['option_d']??'');
    $ans=sanitize($_POST['correct_answer']??'');
    mysqli_query($conn,"UPDATE question_tbl SET course_id=$cid,exam_id=$eid,question_type='$qtype',question='$question',option_a='$a',option_b='$b',option_c='$c_opt',option_d='$d',correct_answer='$ans' WHERE id=$id");
    $_SESSION['msg']='Question updated successfully.'; redirect('questions.php');
}
$courses=mysqli_query($conn,"SELECT * FROM course_tbl WHERE status='active' ORDER BY course_code");
$exams=mysqli_query($conn,"SELECT e.*,c.course_code FROM exam_tbl e LEFT JOIN course_tbl c ON e.course_id=c.id ORDER BY e.exam_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Question - CIMAGE Admin</title>
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
        <h2 style="font-size:20px;font-weight:700">Edit Question</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="card" style="max-width:700px;margin:0 auto">
        <div class="card-header">
          <h3><i class="bi bi-pencil-fill" style="color:var(--primary)"></i> Edit Question</h3>
          <a href="questions.php" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="grid-2" style="gap:14px">
              <div class="form-group"><label>Course *</label>
                <select name="course_id" class="form-control" required>
                  <?php while($c=mysqli_fetch_assoc($courses)): ?>
                  <option value="<?=$c['id']?>" <?=$q['course_id']==$c['id']?'selected':''?>><?=$c['course_code']?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-group"><label>Exam *</label>
                <select name="exam_id" class="form-control" required>
                  <?php while($e=mysqli_fetch_assoc($exams)): ?>
                  <option value="<?=$e['id']?>" <?=$q['exam_id']==$e['id']?'selected':''?>><?=$e['exam_name']?> (<?=$e['course_code']?>)</option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
            <div class="form-group"><label>Question Type</label>
              <select name="question_type" class="form-control">
                <option value="MCQ" <?=$q['question_type']==='MCQ'?'selected':''?>>MCQ</option>
                <option value="True/False" <?=$q['question_type']==='True/False'?'selected':''?>>True/False</option>
              </select>
            </div>
            <div class="form-group"><label>Question *</label><textarea name="question" class="form-control" rows="3" required><?=htmlspecialchars($q['question'])?></textarea></div>
            <div class="grid-2" style="gap:14px">
              <div class="form-group"><label>Option A *</label><input type="text" name="option_a" class="form-control" value="<?=htmlspecialchars($q['option_a'])?>" required></div>
              <div class="form-group"><label>Option B *</label><input type="text" name="option_b" class="form-control" value="<?=htmlspecialchars($q['option_b'])?>" required></div>
              <div class="form-group"><label>Option C *</label><input type="text" name="option_c" class="form-control" value="<?=htmlspecialchars($q['option_c'])?>" required></div>
              <div class="form-group"><label>Option D *</label><input type="text" name="option_d" class="form-control" value="<?=htmlspecialchars($q['option_d'])?>" required></div>
            </div>
            <div class="form-group"><label>Correct Answer *</label>
              <select name="correct_answer" class="form-control" required>
                <?php foreach(['A','B','C','D'] as $opt): ?>
                <option value="<?=$opt?>" <?=$q['correct_answer']===$opt?'selected':''?>>Option <?=$opt?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div style="display:flex;gap:10px">
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Update Question</button>
              <a href="questions.php" class="btn btn-outline">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
