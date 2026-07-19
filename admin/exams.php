<?php
require_once '../includes/functions.php';
require_once '../config/db.php';
requireAdmin();

if(isset($_GET['delete'])){$id=(int)$_GET['delete'];mysqli_query($conn,"DELETE FROM exam_tbl WHERE id=$id");$_SESSION['msg']='Exam deleted.';redirect('exams.php');}
if(isset($_GET['toggle'])){$id=(int)$_GET['toggle'];$s=mysqli_fetch_assoc(mysqli_query($conn,"SELECT status FROM exam_tbl WHERE id=$id"));$ns=$s['status']==='active'?'inactive':'active';mysqli_query($conn,"UPDATE exam_tbl SET status='$ns' WHERE id=$id");redirect('exams.php');}

$msg=$_SESSION['msg']??'';unset($_SESSION['msg']);
$edit=null;
if(isset($_GET['edit'])){$id=(int)$_GET['edit'];$edit=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM exam_tbl WHERE id=$id"));}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=sanitize($_POST['exam_name']??'');
    $cid=(int)($_POST['course_id']??0);
    $tq=(int)($_POST['total_questions']??30);
    $dur=(int)($_POST['duration']??60);
    $tm=(int)($_POST['total_marks']??100);
    $pm=(int)($_POST['passing_marks']??40);
    $date=sanitize($_POST['exam_date']??'');
    $st=sanitize($_POST['start_time']??'');
    $et=sanitize($_POST['end_time']??'');
    $status=sanitize($_POST['status']??'active');
    if($name&&$cid){
        if(isset($_POST['edit_id'])&&$_POST['edit_id']){
            $id=(int)$_POST['edit_id'];
            mysqli_query($conn,"UPDATE exam_tbl SET exam_name='$name',course_id=$cid,total_questions=$tq,duration=$dur,total_marks=$tm,passing_marks=$pm,exam_date='$date',start_time='$st',end_time='$et',status='$status' WHERE id=$id");
            $_SESSION['msg']='Exam updated.';
        }else{
            mysqli_query($conn,"INSERT INTO exam_tbl (exam_name,course_id,total_questions,duration,total_marks,passing_marks,exam_date,start_time,end_time,status) VALUES ('$name',$cid,$tq,$dur,$tm,$pm,'$date','$st','$et','$status')");
            logActivity('admin',$_SESSION['admin_id'],$_SESSION['admin_name'],"Exam Created: $name");
            $_SESSION['msg']='Exam added successfully.';
        }
        redirect('exams.php');
    }
}
$courses=mysqli_query($conn,"SELECT * FROM course_tbl WHERE status='active' ORDER BY course_code");
$exams=mysqli_query($conn,"SELECT e.*,c.course_code,(SELECT COUNT(*) FROM question_tbl WHERE exam_id=e.id) as q_count FROM exam_tbl e LEFT JOIN course_tbl c ON e.course_id=c.id ORDER BY e.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Exams - CIMAGE Admin</title>
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
        <h2 style="font-size:20px;font-weight:700">Manage Exams</h2>
      </div>
      <div class="topbar-right"><div class="user-badge"><div class="user-avatar"><?=strtoupper(substr($_SESSION['admin_name'],0,1))?></div><span><?=$_SESSION['admin_name']?></span></div></div>
    </div>
    <div class="content-area">
      <?php if($msg): ?><div class="toast-wrap"><div class="toast success"><span>✓</span><span><?=htmlspecialchars($msg)?></span></div></div><?php endif; ?>
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-journal-plus" style="color:var(--primary)"></i> <?=$edit?'Edit Exam':'Add New Exam'?></h3></div>
          <div class="card-body">
            <form method="POST">
              <?php if($edit): ?><input type="hidden" name="edit_id" value="<?=$edit['id']?>"><?php endif; ?>
              <div class="form-group"><label>Exam Name *</label><input type="text" name="exam_name" class="form-control" value="<?=htmlspecialchars($edit['exam_name']??'')?>" placeholder="e.g. Database Management" required></div>
              <div class="form-group"><label>Course *</label>
                <select name="course_id" class="form-control" required>
                  <option value="">Select Course</option>
                  <?php while($c=mysqli_fetch_assoc($courses)): ?>
                  <option value="<?=$c['id']?>" <?=($edit&&$edit['course_id']==$c['id'])?'selected':''?>><?=$c['course_code']?> - <?=$c['course_name']?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="grid-2" style="gap:12px">
                <div class="form-group"><label>Total Questions</label><input type="number" name="total_questions" class="form-control" value="<?=$edit['total_questions']??30?>" min="1"></div>
                <div class="form-group"><label>Duration (min)</label><input type="number" name="duration" class="form-control" value="<?=$edit['duration']??60?>" min="1"></div>
              </div>
              <div class="grid-2" style="gap:12px">
                <div class="form-group"><label>Total Marks</label><input type="number" name="total_marks" class="form-control" value="<?=$edit['total_marks']??100?>"></div>
                <div class="form-group"><label>Passing Marks</label><input type="number" name="passing_marks" class="form-control" value="<?=$edit['passing_marks']??40?>"></div>
              </div>
              <div class="form-group"><label>Exam Date</label><input type="date" name="exam_date" class="form-control" value="<?=$edit['exam_date']??''?>"></div>
              <div class="grid-2" style="gap:12px">
                <div class="form-group"><label>Start Time</label><input type="time" name="start_time" class="form-control" value="<?=$edit['start_time']??''?>"></div>
                <div class="form-group"><label>End Time</label><input type="time" name="end_time" class="form-control" value="<?=$edit['end_time']??''?>"></div>
              </div>
              <div class="form-group"><label>Status</label>
                <select name="status" class="form-control">
                  <option value="active" <?=($edit&&$edit['status']==='active')?'selected':''?>>Active</option>
                  <option value="inactive" <?=($edit&&$edit['status']==='inactive')?'selected':''?>>Inactive</option>
                  <option value="completed" <?=($edit&&$edit['status']==='completed')?'selected':''?>>Completed</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> <?=$edit?'Update Exam':'Add Exam'?></button>
              <?php if($edit): ?><a href="exams.php" class="btn btn-outline" style="margin-left:8px">Cancel</a><?php endif; ?>
            </form>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><h3><i class="bi bi-journal-text" style="color:var(--secondary)"></i> All Exams</h3></div>
          <div class="table-responsive">
            <table class="data-table">
              <thead><tr><th>#</th><th>Exam Name</th><th>Course</th><th>Questions</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead>
              <tbody>
              <?php $i=1; while($e=mysqli_fetch_assoc($exams)): ?>
              <tr>
                <td><?=$i++?></td>
                <td><strong><?=htmlspecialchars($e['exam_name'])?></strong><br><small style="color:var(--muted)"><?=$e['exam_date']?date('d M Y',strtotime($e['exam_date'])):''?></small></td>
                <td><span class="badge badge-primary"><?=$e['course_code']?></span></td>
                <td><?=$e['q_count']?>/<?=$e['total_questions']?></td>
                <td><?=$e['duration']?> min</td>
                <td><a href="exams.php?toggle=<?=$e['id']?>" class="badge <?=$e['status']==='active'?'badge-success':($e['status']==='completed'?'badge-info':'badge-danger')?>" style="cursor:pointer"><?=$e['status']?></a></td>
                <td>
                  <a href="exams.php?edit=<?=$e['id']?>" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                  <a href="add_question.php?exam_id=<?=$e['id']?>" class="btn btn-success btn-sm"><i class="bi bi-plus"></i></a>
                  <a href="#" onclick="confirmDelete('exams.php?delete=<?=$e['id']?>','Delete exam?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
