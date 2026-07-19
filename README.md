# End Semester Project Documentation 📄

## Project Declaration

This document certifies that the following work is submitted as an **End Semester Project** for academic evaluation.

**Project Developed By:**
**Romi Kumari**<br/>
**Enrollment No.: 24326711012**<br/>
**Guided By : Dr. Amit Kumar Shukla Sir**<br/>
**Project Development Time : 31st March 2026 To 30th June 2026 (4 months)**

**Institution:**
**CIMAGE Center Of Digital Technology & Entrepreneurship, Patna**

**University Affiliation:**
**Aryabhatta Knowledge University, Patna**

**Program:**
**Master of Computer Applications (MCA)**

---

## Project Description

This project has been developed as part of the academic requirements for the attainment of the **Master of Computer Applications (MCA)** degree.

The project demonstrates the conceptual implementation of an **Online Examination System using Core PHP, MySQL, HTML, CSS, and JavaScript** for educational purposes.

---

## Technology Stack & Deployment

The project has been developed using the following technologies:

* **Frontend:** HTML5, CSS3, JavaScript
* **Backend:** Core PHP
* **Database:** MySQL
* **Server:** Apache (XAMPP/WAMP)
* **Charts:** Chart.js
* **Icons:** Bootstrap Icons

The system follows a full-stack web application architecture utilizing Core PHP for server-side logic, MySQL for data management, and Apache (via XAMPP/WAMP) for deployment and testing purposes.

---

## Disclaimer

This project is **strictly intended for academic demonstration purposes only**.

* Not developed for **retail use**
* Not intended for **distribution**
* Not permitted for **commercial usage**

After submission, this project shall be considered the **property of Aryabhatta Knowledge University, Patna**.

---

**Author:** Romi Kumari
**Enrollment No.: 24326711012** 🎓<br/>
**MCA - 4th semester AKU (2024-2026)**<br/>
**CIMAGE Center of Digital Technology and Entrepreneurship, Patna**

---

# [CIMAGE Online Examination System](https://github.com/MCA-711-1101-ROMI-KUMARI/CIMAGE_ONLINE_EXAMINATION_SYSTEM)

## MCA Final Year Project

**Institute:** CIMAGE – Centre of Digital Technology and Entrepreneurship
**Admin:** RUMI

A complete full-stack Online Examination System built with PHP, MySQL, HTML, CSS, and JavaScript. Features include student registration/login, online MCQ exams with timer, instant results, admin dashboard with analytics, course and question management, and AI proctoring logs.

---

## 🛠️ Technologies Used

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | Core PHP |
| Database | MySQL |
| Server | Apache (XAMPP/WAMP) |
| Charts | Chart.js |
| Icons | Bootstrap Icons |

---

## 📁 Folder Structure

```
cimage_exam_system/
├── admin/
│   ├── dashboard.php
│   ├── students.php
│   ├── courses.php
│   ├── exams.php
│   ├── questions.php
│   ├── add_question.php
│   ├── edit_question.php
│   ├── edit_student.php
│   ├── results.php
│   ├── reports.php
│   ├── proctoring.php
│   ├── settings.php
│   └── sidebar.php
├── student/
│   ├── dashboard.php
│   ├── my_exams.php
│   ├── start_exam.php
│   ├── view_result.php
│   ├── results.php
│   ├── exam_history.php
│   ├── profile.php
│   ├── change_password.php
│   ├── proctoring_guide.php
│   └── sidebar.php
├── auth/
│   ├── admin_login.php
│   ├── student_login.php
│   ├── student_register.php
│   └── logout.php
├── css/
│   ├── style.css
│   └── landing.css
├── js/
│   ├── main.js
│   └── exam.js
├── database/
│   └── cimage_exam_db.sql
├── includes/
│   └── functions.php
├── config/
│   └── db.php
├── index.php
└── README.md
```

---

## ⚙️ Installation Steps

### Step 1 – Install XAMPP
Download and install XAMPP from https://www.apachefriends.org
Start **Apache** and **MySQL** from the XAMPP Control Panel.

### Step 2 – Copy Project Files
Copy the `cimage_exam_system` folder to:
```
C:\xampp\htdocs\
```

### Step 3 – Create Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **New** → create a database named `cimage_exam_db`
3. Select the database → click **Import**
4. Choose file: `database/cimage_exam_db.sql`
5. Click **Go** / **Import**

### Step 4 – Configure Database (if needed)
Edit `config/db.php` if your MySQL credentials differ:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // Add your MySQL password here
define('DB_NAME', 'cimage_exam_db');
```

### Step 5 – Open in Browser
Go to: `http://localhost/cimage_exam_system/`

---

## 🔑 Login Credentials

### Admin Login
| Field | Value |
|-------|-------|
| URL | `http://localhost/cimage_exam_system/auth/admin_login.php` |
| Email | `admin@cimage.com` |
| Password | `admin123` |

### Student Login (Demo Accounts)
| Email | Password |
|-------|----------|
| student1@gmail.com | password |
| amit@gmail.com | password |
| priya@gmail.com | password |

---

## 📚 Modules

1. **Landing Page** – Homepage with course info
2. **Student Login/Register** – Secure authentication
3. **Admin Login** – Admin-only portal
4. **Student Dashboard** – Stats, quick actions, recent exams
5. **Admin Dashboard** – Analytics, charts, activity logs
6. **Online Exam Interface** – Timer, MCQ, navigation palette
7. **Question Management** – Add/Edit/Delete questions
8. **Course Management** – Add/Edit/Delete courses
9. **Result Management** – Detailed result with grade
10. **Performance Analytics** – Charts & reports
11. **AI Proctoring UI** – Session logs & guidelines
12. **Reports** – Course-wise, student-wise analytics

---

## 📊 Database Tables

| Table | Description |
|-------|-------------|
| admin_tbl | Admin accounts |
| student_tbl | Student records |
| course_tbl | Course list (BCA, BBA, etc.) |
| exam_tbl | Exam details |
| question_tbl | MCQ questions |
| result_tbl | Student results |
| activity_log | System activity log |

---

## 🎓 Project Details

- **Project Type:** MCA Final Year Project
- **Project Name:** CIMAGE Online Examination System
- **Institute:** CIMAGE – Centre of Digital Technology and Entrepreneurship
- **Version:** 1.0.0

---

## Academic Submission Details

**Project Title:** CIMAGE Online Examination System<br/>
**Submitted By:** Romi Kumari<br/>
**Enrollment Number:** 24326711012<br/>
**Program:** Master of Computer Applications (MCA)<br/>
**Session:** 2024-2026<br/>
**Semester:** 4th Semester<br/>
**Institution:** CIMAGE Center Of Digital Technology & Entrepreneurship, Patna<br/>
**University:** Aryabhatta Knowledge University (AKU), Patna<br/>
**Project Guide:** Dr. Amit Kumar Shukla Sir<br/>
**Development Duration:** 31st March 2026 to 30th June 2026<br/>

This project is submitted as a partial fulfillment of the requirements for the award of the degree of **Master of Computer Applications (MCA)** under **Aryabhatta Knowledge University, Patna**.

---
