# Encoding and Decoding Management System

## 📘 Overview

The **Encoding and Decoding Management System** is a professional, web-based examination evaluation platform designed to ensure **fairness, anonymity, and integrity** in academic assessments.

The system eliminates evaluator bias by **replacing student hall ticket numbers with temporary encoded identifiers** during the evaluation process. Faculty members evaluate answer scripts using encoded numbers only, ensuring that student identity remains hidden until final result processing.

This solution is ideal for **colleges, universities, and examination boards** that require transparent, secure, and unbiased evaluation workflows.

---

## 🎯 Objectives

- Ensure anonymous examination evaluation
- Prevent favoritism and manual tampering
- Provide secure, role-based access
- Automate encoding and decoding processes
- Improve efficiency and transparency in exam management

---

## ✨ Key Features

### 1. Multi-Portal Access

#### 👨‍🎓 Student Portal
- Self-registration with detailed personal information
- Secure login with encrypted passwords
- Personalized dashboard
- View final decoded examination results
- Submit queries using the *Contact Us* feature

#### 👩‍🏫 Faculty / Teacher Portal
- Secure role-based login
- Access only assigned exams and subjects
- Enter marks using **encoded numbers**
- Complete anonymity of student identity
- Restricted access to prevent unauthorized evaluations

#### 🏢 Office / Admin Portal
- Centralized administrative control
- Manage exams, classes, and subjects
- Register and assign faculty
- Generate encoded hall ticket numbers
- Decode results after evaluation
- System-wide monitoring and control

---

## 🔐 Anonymous Evaluation Logic

### Encoding System
- Office administrators generate **temporary encoded identifiers**
- Original hall ticket numbers are hidden during evaluation
- Encoded numbers ensure absolute anonymity

### Faculty Assignment
- Faculty are mapped to:
  - Exams
  - Subjects
  - Encoded student identifiers
- Prevents cross-access or data leakage

### Evaluation Workflow
1. Students register and appear for exams
2. Office generates encoded numbers
3. Faculty evaluate using encoded identifiers
4. Marks are stored against encoded IDs
5. System decodes and maps results
6. Students view final results

---

## 🛠 System Management

- Dynamic creation and management of:
  - Exams
  - Classes
  - Subjects
- Faculty–subject mapping
- Controlled evaluation access
- Integrated *Contact Us* support module
- Centralized result processing

---

## 🔒 Security & Communication

- Password hashing for all accounts
- Secure PHP session handling
- Role-based access control
- Data isolation between portals
- Integrated **PHPMailer (v6.9)** for email notifications

---

## 🧰 Tech Stack

### Backend
- PHP (Procedural)
  - Session management
  - Business logic
  - Data processing

### Frontend
- HTML5
- CSS3 (Modern gradient UI)
- Vanilla JavaScript

### Database
- MySQL / MariaDB (Relational)

### Mail
- phpmailer/phpmailer
- Dependency management using Composer

---
## 📁 Project Structure (Original)
~~~plaintext
.
├── a
├── aboutus.html
├── add_exam_subject.html
├── add_exam_subject.php
├── assign_faculty.html
├── assign_faculty.php
├── assign_marks.php
├── assign_type.html
├── assignbulk_faculty.html
├── assignbulk_faculty.php
├── class_result.php
├── college.webp
├── composer.json
├── composer.lock
├── contactus.html
├── contactus.php
├── dashboard.php
├── database.sql
├── debug_log.txt
├── f_getresult.php
├── f_result.php
├── forgot_f_pass.php
├── forgot_s_password.php
├── gallery.html
├── get_assignments.html
├── get_assignments.php
├── get_c.php
├── get_classes.php
├── get_exams.php
├── get_filters.php
├── get_results.php
├── get_results_pdf.php
├── get_subjects.php
├── getfacultybysubjectcode.php
├── go_for_evaluation.php
├── index.html
├── loginportal.html
├── logout.php
├── notification_select.html
├── o_login.html
├── office_db.sql
├── results.php
├── select_r_t.html
├── service.html
├── signup.html
├── signup.php
├── student_notification.php
├── studentdashboard.php
├── studentlogin.php
├── studentlogout.php
├── subject_result.php
├── teacher_notification.php
├── teacherlogin.html
├── teacherlogin.php
├── team.html
└── vendor/
~~~

## 🗄 Database Schema (Overview)

The system uses **two separate databases** for security and operational integrity.

### `database_db` – Core Operations

| Table | Description |
|-----|------------|
| students | Student personal data and credentials |
| faculty | Faculty profiles |
| faculty_assignments | Mapping hall tickets to encoded numbers |
| marks | Marks stored using encoded IDs |
| exams | Examination details |
| classes | Academic classes |
| subjects | Subject information |
| contact_us | User queries |

### `office_db` – Administration

| Table | Description |
|------|------------|
| office_users | Administrative credentials |

---

## 🚀 Getting Started (Local Development)

### 1. Prerequisites

- PHP **7.4+**
- MySQL / MariaDB
- Composer
- XAMPP / WAMP / LAMP
- MySQL running on **Port 3307**

---

## 👥 User Roles & Responsibilities

### 🏢 Office / Admin
- Create and manage exams, classes, and subjects
- Register faculty members
- Generate encoded hall ticket numbers
- Assign faculty to specific exams and subjects
- Decode marks after evaluation completion
- Publish final results

### 👩‍🏫 Faculty
- Log in securely
- View only assigned exams and subjects
- Enter marks using encoded identifiers
- No access to student personal details

### 👨‍🎓 Students
- Self-register and log in
- Access personalized dashboard
- View decoded examination results
- Submit queries via Contact Us module

---

## 🔄 Evaluation Lifecycle

1. Student registration
2. Hall ticket number allocation
3. Encoding of hall ticket numbers
4. Faculty evaluation using encoded IDs
5. Secure storage of marks
6. Decoding process
7. Result publication

---

## 🧪 Testing Checklist

- Verify role-based access control
- Ensure encoded numbers are unique
- Validate decoding accuracy
- Confirm faculty cannot view student identities
- Test email notifications
- Check SQL injection and session security

---

---

## ❓ Troubleshooting

- Ensure MySQL is running on port **3307**
- Confirm databases are imported correctly
- Run `composer install` only in project root
- Enable PHP extensions: `mysqli`, `openssl`
- Use PHP version **7.4 or higher**

---

## 🚀 Future Enhancements

- OTP-based authentication
- Audit logs for evaluations
- Result analytics dashboard
- REST API integration
- Cloud deployment support
- Mobile-responsive UI

---

## 📜 License

This project is licensed under the **MIT License**.

---

## 👨‍💻 Author

**Developed by:**  
**N YASWANTH**

For the **Encoding and Decoding Management System**

