-- ================================================
-- CIMAGE Online Examination System - Database
-- Institute: CIMAGE - Centre of Digital Technology
-- ================================================

CREATE DATABASE IF NOT EXISTS `cimage_exam_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cimage_exam_db`;

-- Admin Table
CREATE TABLE IF NOT EXISTS `admin_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin_tbl` (`name`, `email`, `password`, `role`) VALUES
('RUMI', 'admin@cimage.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Password: admin123

-- Course Table
CREATE TABLE IF NOT EXISTS `course_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(150) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `description` text,
  `duration` varchar(50),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `course_tbl` (`course_name`, `course_code`, `description`, `duration`) VALUES
('Bachelor of Computer Applications', 'BCA', 'Undergraduate program in Computer Applications', '3 Years'),
('Bachelor of Business Administration', 'BBA', 'Undergraduate program in Business Administration', '3 Years'),
('Bachelor of Commerce', 'B.COM', 'Undergraduate program in Commerce', '3 Years'),
('Bachelor of Science in Information Technology', 'BSC.IT', 'Undergraduate program in IT', '3 Years'),
('Master of Business Administration', 'MBA', 'Postgraduate program in Business Administration', '2 Years'),
('Master of Computer Applications', 'MCA', 'Postgraduate program in Computer Applications', '2 Years');

-- Student Table
CREATE TABLE IF NOT EXISTS `student_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20),
  `course_id` int(11),
  `semester` varchar(20),
  `dob` date,
  `gender` enum('Male','Female','Other'),
  `address` text,
  `photo` varchar(255) DEFAULT 'default.png',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `student_id` (`student_id`),
  FOREIGN KEY (`course_id`) REFERENCES `course_tbl`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `student_tbl` (`student_id`,`name`,`email`,`password`,`phone`,`course_id`,`semester`,`gender`) VALUES
('STU001','Riya Kumari','student1@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','9876543210',6,'4th','Female'),
('STU002','Amit Singh','amit@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','9876543211',1,'3rd','Male'),
('STU003','Priya Sharma','priya@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','9876543212',2,'2nd','Female');
-- Password for all demo students: password

-- Exam Table
CREATE TABLE IF NOT EXISTS `exam_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_name` varchar(200) NOT NULL,
  `course_id` int(11) NOT NULL,
  `total_questions` int(11) DEFAULT 30,
  `duration` int(11) DEFAULT 60,
  `total_marks` int(11) DEFAULT 100,
  `passing_marks` int(11) DEFAULT 40,
  `exam_date` date,
  `start_time` time,
  `end_time` time,
  `status` enum('active','inactive','completed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`course_id`) REFERENCES `course_tbl`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `exam_tbl` (`exam_name`,`course_id`,`total_questions`,`duration`,`total_marks`,`passing_marks`,`exam_date`,`start_time`,`end_time`,`status`) VALUES
('Database Management',1,10,60,100,40,'2024-05-24','09:00:00','10:00:00','active'),
('Business Law',2,10,60,100,40,'2024-05-20','10:00:00','11:00:00','active'),
('Financial Accounting',3,10,60,100,40,'2024-05-18','11:00:00','12:00:00','active'),
('Data Structures',1,10,60,100,40,'2024-06-10','09:00:00','10:00:00','active'),
('Software Engineering',6,10,60,100,40,'2024-06-15','10:00:00','11:00:00','active');

-- Question Table
CREATE TABLE IF NOT EXISTS `question_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_type` enum('MCQ','True/False') DEFAULT 'MCQ',
  `question` text NOT NULL,
  `option_a` varchar(255),
  `option_b` varchar(255),
  `option_c` varchar(255),
  `option_d` varchar(255),
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `marks` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`course_id`) REFERENCES `course_tbl`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`exam_id`) REFERENCES `exam_tbl`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `question_tbl` (`course_id`,`exam_id`,`question_type`,`question`,`option_a`,`option_b`,`option_c`,`option_d`,`correct_answer`) VALUES
(1,1,'MCQ','Which of the following is a DDL command in SQL?','SELECT','INSERT','CREATE','UPDATE','C'),
(1,1,'MCQ','What does SQL stand for?','Structured Query Language','Simple Query Language','Sequential Query Language','Standard Query Language','A'),
(1,1,'MCQ','Which command is used to remove a table?','DELETE','DROP','REMOVE','TRUNCATE','B'),
(1,1,'MCQ','ACID properties are related to?','Networking','Database Transactions','Operating System','Programming','B'),
(1,1,'MCQ','Which JOIN returns all records from left table?','INNER JOIN','RIGHT JOIN','LEFT JOIN','FULL JOIN','C'),
(1,1,'MCQ','Primary key ensures?','Uniqueness only','Not Null only','Both Unique and Not Null','Foreign key reference','C'),
(1,1,'MCQ','Which normal form eliminates transitive dependency?','1NF','2NF','3NF','BCNF','C'),
(1,1,'MCQ','Foreign key refers to?','A unique key','Primary key of another table','A composite key','An index key','B'),
(1,1,'MCQ','Which command retrieves data?','INSERT','UPDATE','SELECT','DELETE','C'),
(1,1,'MCQ','What does ERD stand for?','Entity Relationship Diagram','Entity Record Data','Electronic Relational Database','Extended Record Diagram','A'),
(2,2,'MCQ','Contract Act was passed in India in?','1870','1872','1880','1890','B'),
(2,2,'MCQ','Which is NOT an essential element of a valid contract?','Offer','Acceptance','Consideration','Registration','D'),
(2,2,'MCQ','An agreement enforceable by law is a?','Promise','Contract','Offer','Acceptance','B'),
(2,2,'MCQ','Void agreement means?','Enforceable agreement','Unenforceable agreement','Partially enforceable','None','B'),
(2,2,'MCQ','Consideration means?','Promise','Something in return','Acceptance','Offer','B'),
(2,2,'MCQ','Minor in India means age below?','18','21','16','20','A'),
(2,2,'MCQ','Quasi contract is?','Real contract','Implied by law','Expressed contract','Verbal contract','B'),
(2,2,'MCQ','Indemnity contract is governed by section?','121','124','130','145','B'),
(2,2,'MCQ','Sale of Goods Act was passed in?','1930','1932','1920','1940','A'),
(2,2,'MCQ','Bailment means?','Transfer of ownership','Delivery of goods for a purpose','Gift','Sale','B'),
(3,3,'MCQ','Double entry system means?','Two entries for each transaction','One debit and one credit','Both A and B','None','C'),
(3,3,'MCQ','Balance sheet shows?','Income','Expenses','Financial position','Cash flow','C'),
(3,3,'MCQ','Debit means?','Increase in liability','Decrease in asset','Increase in asset','Decrease in capital','C'),
(3,3,'MCQ','Trial balance checks?','Arithmetical accuracy','Financial position','Profit/Loss','Cash position','A'),
(3,3,'MCQ','Depreciation means?','Increase in asset value','Decrease in asset value','Asset purchase','Asset sale','B'),
(3,3,'MCQ','Goodwill is?','Tangible asset','Intangible asset','Current asset','Liability','B'),
(3,3,'MCQ','P&L account shows?','Assets','Liabilities','Profit or Loss','Capital','C'),
(3,3,'MCQ','Accrual concept means?','Cash basis accounting','Record when earned/incurred','Record when paid','None','B'),
(3,3,'MCQ','Working capital is?','Fixed assets - Current liabilities','Current assets - Current liabilities','Total assets - Total liabilities','None','B'),
(3,3,'MCQ','Journal is?','Book of final entry','Book of original entry','Ledger','None','B');

-- Result Table
CREATE TABLE IF NOT EXISTS `result_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `total_questions` int(11) DEFAULT 0,
  `attempted` int(11) DEFAULT 0,
  `correct_answers` int(11) DEFAULT 0,
  `wrong_answers` int(11) DEFAULT 0,
  `score` int(11) DEFAULT 0,
  `total_marks` int(11) DEFAULT 100,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `grade` varchar(5),
  `status` enum('Pass','Fail') DEFAULT 'Fail',
  `rank_position` int(11) DEFAULT 0,
  `answers` longtext,
  `exam_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `student_tbl`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`exam_id`) REFERENCES `exam_tbl`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `result_tbl` (`student_id`,`exam_id`,`total_questions`,`attempted`,`correct_answers`,`wrong_answers`,`score`,`total_marks`,`percentage`,`grade`,`status`,`rank_position`) VALUES
(1,1,10,10,8,2,80,100,80.00,'A','Pass',8),
(2,1,10,9,7,2,70,100,70.00,'B','Pass',12),
(3,2,10,10,6,4,60,100,60.00,'C','Pass',20);

-- Activity Log Table
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','student') DEFAULT 'student',
  `user_id` int(11),
  `user_name` varchar(100),
  `action` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `activity_log` (`user_type`,`user_id`,`user_name`,`action`) VALUES
('student',1,'Riya Kumari','New Student Registered'),
('admin',1,'RUMI','Exam Created: Semester Test (BCA)'),
('admin',1,'RUMI','Question Added: Database MCQ Set'),
('student',2,'Amit Singh','Exam Submitted: Database Management');
