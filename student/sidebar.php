<?php
$current = basename($_SERVER['PHP_SELF']);
function sNavLink($href, $icon, $label, $current) {
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
    <?php sNavLink('dashboard.php','bi-speedometer2','Dashboard',$current); ?>
    <div class="nav-section">Exams</div>
    <?php sNavLink('my_exams.php','bi-journal-text','My Exams',$current); ?>
    <?php sNavLink('exam_history.php','bi-clock-history','Exam History',$current); ?>
    <?php sNavLink('results.php','bi-bar-chart-fill','Results',$current); ?>
    <div class="nav-section">Account</div>
    <?php sNavLink('profile.php','bi-person-circle','Profile',$current); ?>
    <?php sNavLink('change_password.php','bi-lock-fill','Change Password',$current); ?>
    <?php sNavLink('proctoring_guide.php','bi-camera-video','AI Proctoring Guide',$current); ?>
    <div class="logout-link">
      <?php sNavLink('../auth/logout.php','bi-box-arrow-right','Logout',$current); ?>
    </div>
  </nav>
</aside>
