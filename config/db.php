<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cimage_exam_db');
define('SITE_NAME', 'CIMAGE Online Examination System');
define('BASE_URL', 'http://localhost/cimage_exam_system/');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("<div style='font-family:sans-serif;padding:40px;text-align:center;background:#f8d7da;color:#721c24;border-radius:8px;margin:40px auto;max-width:600px;'>
        <h2>&#9888; Database Connection Failed</h2>
        <p><strong>Error:</strong> ".mysqli_connect_error()."</p>
        <p>Please ensure XAMPP is running and the database <strong>cimage_exam_db</strong> is imported.</p>
    </div>");
}
mysqli_set_charset($conn, "utf8mb4");
?>
