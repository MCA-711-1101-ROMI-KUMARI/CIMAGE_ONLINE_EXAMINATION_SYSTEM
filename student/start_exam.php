<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireStudent();
$sid=(int)$_SESSION['student_id'];
$exam_id=(int)($_GET['exam_id']??0);
if(!$exam_id) redirect('my_exams.php');

$exam=mysqli_fetch_assoc(mysqli_query($conn,"SELECT e.*,c.course_code FROM exam_tbl e LEFT JOIN course_tbl c ON e.course_id=c.id WHERE e.id=$exam_id AND e.status='active'"));
if(!$exam) { echo "<script>alert('Exam not found or inactive.');window.location='my_exams.php';</script>"; exit; }

// Check already submitted
$done=mysqli_fetch_row(mysqli_query($conn,"SELECT id FROM result_tbl WHERE student_id=$sid AND exam_id=$exam_id"));
if($done) redirect('view_result.php?id='.$done[0]);

// Load questions
$questions=mysqli_query($conn,"SELECT * FROM question_tbl WHERE exam_id=$exam_id ORDER BY RAND()");
$total=mysqli_num_rows($questions);
if($total==0) { echo "<script>alert('No questions available for this exam.');window.location='my_exams.php';</script>"; exit; }
$qList=[];
while($q=mysqli_fetch_assoc($questions)) $qList[]=$q;

// HANDLE SUBMIT
if($_SERVER['REQUEST_METHOD']==='POST'){
    $answers_json=$_POST['answers_json']??'{}';
    $answers=json_decode($answers_json,true);
    if(!is_array($answers)) $answers=[];

    $correct=0; $wrong=0; $attempted=count($answers);
    foreach($qList as $i=>$q){
        $num=$i+1;
        if(isset($answers[$num])){
            if($answers[$num]===$q['correct_answer']) $correct++; else $wrong++;
        }
    }
    $score=$correct;
    $totalMarks=$exam['total_marks'];
    $pct=round(($correct/$total)*100,2);
    $grade=getGrade($pct);
    $status=$pct>=$exam['passing_marks']?'Pass':'Fail';
    $rankPos=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*)+1 FROM (SELECT student_id,AVG(percentage) as avg FROM result_tbl GROUP BY student_id HAVING avg>$pct) t"))[0];
    $ans_escaped=mysqli_real_escape_string($conn,$answers_json);

    mysqli_query($conn,"INSERT INTO result_tbl (student_id,exam_id,total_questions,attempted,correct_answers,wrong_answers,score,total_marks,percentage,grade,status,rank_position,answers) VALUES ($sid,$exam_id,$total,$attempted,$correct,$wrong,$score,$totalMarks,$pct,'$grade','$status',$rankPos,'$ans_escaped')");
    $result_id=mysqli_insert_id($conn);
    logActivity('student',$sid,$_SESSION['student_name'],'Exam Submitted: '.$exam['exam_name']);
    redirect('view_result.php?id='.$result_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($exam['exam_name'])?> - CIMAGE Exam</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body{background:#f0f4f8}
.exam-topbar{background:#1a3c8f;color:#fff;padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.exam-title{font-size:16px;font-weight:700}.exam-meta{font-size:13px;opacity:.8}
</style>
</head>
<body>
<div class="exam-topbar">
  <div>
    <div class="exam-title"><?=htmlspecialchars($exam['exam_name'])?> - <?=$exam['course_code']?></div>
    <div class="exam-meta">Question <span id="currentQNum">1</span> of <?=$total?></div>
  </div>
  <button onclick="submitExam()" class="btn btn-danger btn-sm"><i class="bi bi-send-fill"></i> Submit Exam</button>
</div>

<form method="POST" id="examForm">
  <input type="hidden" name="answers_json" id="answers_json" value="{}">
  <div style="max-width:1200px;margin:24px auto;padding:0 20px">
    <div class="exam-page-wrap">
      <!-- QUESTION AREA -->
      <div>
        <?php foreach($qList as $i=>$q): $num=$i+1; ?>
        <div class="card question-block" id="q_block_<?=$num?>" style="display:<?=$num===1?'block':'none'?>">
          <div class="card-body">
            <p class="question-number">Question <?=$num?> of <?=$total?></p>
            <p class="question-text"><?=htmlspecialchars($q['question'])?></p>
            <div class="option-list">
              <?php foreach(['A'=>$q['option_a'],'B'=>$q['option_b'],'C'=>$q['option_c'],'D'=>$q['option_d']] as $opt=>$val): ?>
              <div class="option-item" data-opt="<?=$opt?>" onclick="selectAnswer(<?=$num?>,'<?=$opt?>')">
                <div class="option-badge"><?=$opt?></div>
                <span><?=htmlspecialchars($val)?></span>
              </div>
              <?php endforeach; ?>
            </div>
            <div style="display:flex;align-items:center;gap:8px;margin-top:18px">
              <input type="checkbox" id="mark_<?=$num?>" onchange="if(this.checked)marked[<?=$num?>]=true;else delete marked[<?=$num?>];updatePalette()">
              <label for="mark_<?=$num?>" style="font-size:13px;cursor:pointer;color:var(--muted)">Mark for Review</label>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <div class="exam-nav-btns">
          <button type="button" id="prevBtn" class="btn btn-outline" onclick="prevQuestion()" disabled><i class="bi bi-chevron-left"></i> Previous</button>
          <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextQuestion()">Next <i class="bi bi-chevron-right"></i></button>
        </div>
      </div>

      <!-- SIDEBAR -->
      <div>
        <div class="card" style="position:sticky;top:80px">
          <div class="card-body" style="padding:16px">
            <!-- TIMER -->
            <div class="timer-box" id="timerBox">
              <div class="timer-label"><i class="bi bi-clock"></i> Time Left</div>
              <div class="timer-display" id="timerDisplay">00:00</div>
            </div>
            <!-- PALETTE -->
            <div class="nav-palette">
              <h4>Question Navigation</h4>
              <div class="palette-grid" id="paletteGrid"></div>
              <div class="legend-grid">
                <div class="legend-item"><div class="legend-dot" style="background:var(--success)"></div><span>Answered</span></div>
                <div class="legend-item"><div class="legend-dot" style="background:#dee2e6"></div><span>Not Answered</span></div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--warning)"></div><span>Marked for Review</span></div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--primary)"></div><span>Current</span></div>
              </div>
            </div>
            <button type="button" onclick="submitExam()" class="btn btn-danger" style="width:100%;justify-content:center;margin-top:8px"><i class="bi bi-send-fill"></i> Submit Exam</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<script src="../js/exam.js"></script>
<script>
document.addEventListener('DOMContentLoaded',()=>{
  initExam(<?=$exam['duration']?>,<?=$total?>);
});
</script>
</body>
</html>
