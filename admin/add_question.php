<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();

$msg=''; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $cid=(int)($_POST['course_id']??0);
    $eid=(int)($_POST['exam_id']??0);
    $qtype=sanitize($_POST['question_type']??'MCQ');
    $q=sanitize($_POST['question']??'');
    $a=sanitize($_POST['option_a']??'');
    $b=sanitize($_POST['option_b']??'');
    $c=sanitize($_POST['option_c']??'');
    $d=sanitize($_POST['option_d']??'');
    $ans=sanitize($_POST['correct_answer']??'');
    if(!$cid||!$eid||!$q||!$a||!$b||!$c||!$d||!$ans){$error='Please fill all required fields.';}
    else{
        mysqli_query($conn,"INSERT INTO question_tbl (course_id,exam_id,question_type,question,option_a,option_b,option_c,option_d,correct_answer) VALUES ($cid,$eid,'$qtype','$q','$a','$b','$c','$d','$ans')");
        logActivity('admin',$_SESSION['admin_id'],$_SESSION['admin_name'],'Question Added');
        $msg='Question added successfully!';
    }
}
$courses=mysqli_query($conn,"SELECT * FROM course_tbl WHERE status='active' ORDER BY course_code");
$sel_exam=isset($_GET['exam_id'])?(int)$_GET['exam_id']:0;
$sel_course=0;
if($sel_exam){$er=mysqli_fetch_assoc(mysqli_query($conn,"SELECT course_id FROM exam_tbl WHERE id=$sel_exam"));if($er)$sel_course=$er['course_id'];}
$exams=mysqli_query($conn,"SELECT e.*,c.course_code FROM exam_tbl e LEFT JOIN course_tbl c ON e.course_id=c.id WHERE e.status!='completed' ORDER BY e.exam_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Question - CIMAGE Admin</title>
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
        <h2 style="font-size:20px;font-weight:700">Add New Question</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-plus-circle-fill" style="color:var(--primary)"></i> Add Question</h3></div>
          <div class="card-body">
            <?php if($error): ?><div style="background:#f8d7da;color:#721c24;padding:12px;border-radius:8px;margin-bottom:16px;font-size:13px"><i class="bi bi-exclamation-circle"></i> <?=$error?></div><?php endif; ?>
            <?php if($msg): ?><div style="background:#d4edda;color:#155724;padding:12px;border-radius:8px;margin-bottom:16px;font-size:13px"><i class="bi bi-check-circle"></i> <?=$msg?></div><?php endif; ?>
            <form method="POST">
              <div class="grid-2" style="gap:14px">
                <div class="form-group">
                  <label>Course *</label>
                  <select name="course_id" id="courseSelect" class="form-control" required onchange="filterExams()">
                    <option value="">Select Course</option>
                    <?php while($c=mysqli_fetch_assoc($courses)): ?>
                    <option value="<?=$c['id']?>" <?=($sel_course==$c['id'])?'selected':''?>><?=$c['course_code']?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Exam *</label>
                  <select name="exam_id" id="examSelect" class="form-control" required>
                    <option value="">Select Exam</option>
                    <?php mysqli_data_seek($exams,0); while($e=mysqli_fetch_assoc($exams)): ?>
                    <option value="<?=$e['id']?>" data-course="<?=$e['course_id']?>" <?=($sel_exam==$e['id'])?'selected':''?>><?=$e['exam_name']?> (<?=$e['course_code']?>)</option>
                    <?php endwhile; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>Question Type *</label>
                <select name="question_type" class="form-control">
                  <option value="MCQ">MCQ</option>
                  <option value="True/False">True/False</option>
                </select>
              </div>
              <div class="form-group">
                <label>Question *</label>
                <textarea name="question" class="form-control" rows="3" placeholder="Enter your question here..." required><?=htmlspecialchars($_POST['question']??'')?></textarea>
              </div>
              <div class="grid-2" style="gap:14px">
                <div class="form-group">
                  <label>Option A *</label>
                  <input type="text" name="option_a" class="form-control" placeholder="Option A" value="<?=htmlspecialchars($_POST['option_a']??'')?>" required>
                </div>
                <div class="form-group">
                  <label>Option B *</label>
                  <input type="text" name="option_b" class="form-control" placeholder="Option B" value="<?=htmlspecialchars($_POST['option_b']??'')?>" required>
                </div>
                <div class="form-group">
                  <label>Option C *</label>
                  <input type="text" name="option_c" class="form-control" placeholder="Option C" value="<?=htmlspecialchars($_POST['option_c']??'')?>" required>
                </div>
                <div class="form-group">
                  <label>Option D *</label>
                  <input type="text" name="option_d" class="form-control" placeholder="Option D" value="<?=htmlspecialchars($_POST['option_d']??'')?>" required>
                </div>
              </div>
              <div class="form-group">
                <label>Correct Answer *</label>
                <select name="correct_answer" class="form-control" required>
                  <option value="">Select Correct Answer</option>
                  <?php foreach(['A','B','C','D'] as $opt): ?>
                  <option value="<?=$opt?>" <?=(($_POST['correct_answer']??'')==$opt)?'selected':''?>>Option <?=$opt?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Save Question</button>
                <button type="reset" class="btn btn-outline"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
              </div>
            </form>
          </div>
        </div>
        <div>
          <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3><i class="bi bi-lightbulb-fill" style="color:var(--warning)"></i> Question Guidelines</h3></div>
            <div class="card-body">
              <ul style="font-size:13px;line-height:2;padding-left:18px;color:var(--text)">
                <li>Choose the correct question type</li>
                <li>Enter all options clearly</li>
                <li>Select the correct answer</li>
                <li>Review before saving</li>
                <li>Avoid duplicate questions</li>
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h3><i class="bi bi-question-circle-fill" style="color:var(--info)"></i> Question Bank</h3></div>
            <div class="card-body">
              <a href="questions.php" class="btn btn-info btn-block"><i class="bi bi-eye"></i> View All Questions</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
<script>
function filterExams(){
  const cid=document.getElementById('courseSelect').value;
  document.querySelectorAll('#examSelect option').forEach(o=>{
    if(!o.value){o.style.display='';return;}
    o.style.display=(!cid||o.dataset.course===cid)?'':'none';
  });
  document.getElementById('examSelect').value='';
}
filterExams();
</script>
</body>
</html>
