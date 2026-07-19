<?php
$current = basename($_SERVER['PHP_SELF']);
function navLink($href, $icon, $label, $current) {
    $file = basename($href);
    $active = ($current === $file) ? 'active' : '';
    echo "<a href='$href' class='$active'><i class='bi $icon'></i> $label</a>";
}
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">C</div>
    <div class="logo-text">
      <h4>CIMAGE</h4>
      <small>Centre of Digital Technology<br>& Entrepreneurship</small>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Main</div>
    <?php navLink('dashboard.php','bi-speedometer2','Dashboard',$current); ?>
    <div class="nav-section">Management</div>
    <?php navLink('students.php','bi-people-fill','Manage Students',$current); ?>
    <?php navLink('courses.php','bi-book-fill','Manage Courses',$current); ?>
    <?php navLink('exams.php','bi-journal-text','Manage Exams',$current); ?>
    <?php navLink('questions.php','bi-question-circle-fill','Question Bank',$current); ?>
    <?php navLink('add_question.php','bi-plus-circle-fill','Add Question',$current); ?>
    <div class="nav-section">Reports</div>
    <?php navLink('results.php','bi-bar-chart-fill','Results',$current); ?>
    <?php navLink('reports.php','bi-file-earmark-text-fill','Reports',$current); ?>
    <?php navLink('proctoring.php','bi-camera-video-fill','AI Proctoring Logs',$current); ?>
    <div class="nav-section">Account</div>
    <?php navLink('settings.php','bi-gear-fill','Settings',$current); ?>
    <div class="logout-link">
      <?php navLink('../auth/logout.php','bi-box-arrow-right','Logout',$current); ?>
    </div>
  </nav>
</aside>
