<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CIMAGE - Online Examination System</title>
<link rel="stylesheet" href="css/landing.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

<!-- HEADER -->
<header class="site-header">
  <div class="logo">
    <div class="logo-shield">C</div>
    <div class="logo-text">
      <h1>CIMAGE</h1>
      <p>Centre of Digital Technology<br>and Entrepreneurship</p>
    </div>
  </div>
  <nav class="site-nav">
    <a href="#">Home</a>
    <a href="#">About Us</a>
    <a href="#">Courses</a>
    <a href="#">Contact Us</a>
  </nav>
  <div class="nav-btns">
    <button class="btn-stu" onclick="window.location.href='auth/student_login.php'">
      <i class="bi bi-person-fill"></i> Student Login
    </button>
    <button class="btn-adm" onclick="window.location.href='auth/admin_login.php'">
      <i class="bi bi-shield-lock-fill"></i> Admin Login
    </button>
  </div>
</header>

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <p class="welcome-tag">WELCOME TO</p>
    <h1 class="hero-name">CIMAGE</h1>
    <p class="hero-subtitle">Centre of Digital Technology<br>and Entrepreneurship</p>
    <p class="hero-exam-title">Online Examination System</p>
    <p class="hero-desc">Empowering Students Through Digital Learning &amp; Assessment</p>
    <div class="course-tags">
      <span class="ctag c-bca">BCA</span>
      <span class="ctag c-bba">BBA</span>
      <span class="ctag c-bcom">B.COM</span>
      <span class="ctag c-bscit">BSC.IT</span>
      <span class="ctag c-mba">MBA</span>
      <span class="ctag c-mca">MCA</span>
    </div>
  </div>
  <div class="hero-img-side">
    <div class="hero-img-wrap">
      <div class="building-placeholder">
        <i class="bi bi-building"></i>
        <h3>CIMAGE</h3>
        <p>Centre of Digital Technology</p>
      </div>
      <div class="img-badge">CIMAGE</div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features-section">
  <div class="features-grid">
    <div class="feat-card">
      <div class="feat-icon"><i class="bi bi-shield-check"></i></div>
      <div>
        <h3>Secure Exams</h3>
        <p>AI Proctoring &amp; Anti-Cheat Technology for trusted examination experience.</p>
      </div>
    </div>
    <div class="feat-card">
      <div class="feat-icon"><i class="bi bi-graph-up"></i></div>
      <div>
        <h3>Instant Results</h3>
        <p>Get immediate results and detailed analytics after every exam submission.</p>
      </div>
    </div>
    <div class="feat-card">
      <div class="feat-icon"><i class="bi bi-award"></i></div>
      <div>
        <h3>Trusted System</h3>
        <p>Reliable, Transparent &amp; Student Friendly examination platform.</p>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
  <p>&copy; 2024 CIMAGE Centre of Digital Technology and Entrepreneurship. All Rights Reserved.</p>
</footer>

</body>
</html>
