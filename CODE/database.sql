CREATE DATABASE database_db;

USE database_db;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    rollno VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    gender VARCHAR(10) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE faculty_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam VARCHAR(255) NOT NULL,
    class VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    faculty_id VARCHAR(255) NOT NULL,
    hall_ticket_no VARCHAR(255) NOT NULL,
    encoded_no VARCHAR(255) NOT NULL
);


CREATE TABLE exams (
    exam_id INT AUTO_INCREMENT PRIMARY KEY,
    exam_name VARCHAR(100) NOT NULL
);

CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    class_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (exam_id) REFERENCES exams(exam_id)
);

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (class_id) REFERENCES classes(class_id)
);
CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    facultyid VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hall_ticket_no VARCHAR(255) NOT NULL,
    exam VARCHAR(255) NOT NULL,
    class VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    marks INT NOT NULL
);

CREATE TABLE contact_us (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

