# Coding and Decoding Management System

A comprehensive web-based educational management system designed to manage the encoding and decoding of student hall tickets during exam evaluations, ensuring fair and anonymous grading processes.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [User Roles](#user-roles)
- [Project Structure](#project-structure)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

The Coding and Decoding Management System is an innovative solution that addresses the need for anonymous and unbiased exam evaluation. By encoding student hall ticket numbers during the evaluation process, the system ensures that faculty members evaluate answer sheets without knowing the student's identity, promoting fairness and objectivity in grading.

### Key Benefits

- **Anonymous Evaluation**: Hall tickets are encoded, ensuring unbiased grading
- **Secure Access**: Role-based authentication for students, faculty, and administrators
- **Efficient Management**: Streamlined exam, class, and subject management
- **Real-time Results**: Students can view their results instantly after evaluation
- **User-Friendly Interface**: Modern, responsive design for all devices

## ✨ Features

### Student Features
- Student registration and login
- View exam results by selecting exam and class
- Download results as images
- Password recovery via email
- Student dashboard with personalized information
- Contact form for inquiries

### Faculty Features
- Faculty registration and login
- View assigned evaluation tasks
- Assign marks to encoded hall tickets
- Track evaluation history
- Faculty dashboard
- Notification system

### Office/Admin Features
- Admin login portal
- Add/Manage exams, classes, and subjects
- Assign faculty to specific exams and subjects
- Bulk faculty assignment
- View class-wise and subject-wise results
- Manage student and faculty data
- Generate reports

### System Features
- Hall ticket encoding/decoding mechanism
- Secure password hashing
- Session management
- Email notifications (via PHPMailer)
- Responsive web design
- AJAX-based dynamic content loading

## 🛠 Technology Stack

### Backend
- **PHP 7.4+** - Server-side scripting
- **MySQL** - Database management
- **PHPMailer 6.9+** - Email functionality

### Frontend
- **HTML5** - Structure
- **CSS3** - Styling with modern gradients and animations
- **JavaScript** - Client-side interactivity
- **jQuery 3.6.0** - AJAX requests and DOM manipulation
- **html2canvas** - Result image generation

### Tools & Libraries
- **Composer** - PHP dependency management
- **PHPMailer** - Email sending capabilities

## 📦 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for dependency management)
- Web browser (Chrome, Firefox, Safari, Edge)

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/coding-decoding-management-system.git
cd coding-decoding-management-system
```

### Step 2: Install Dependencies
```bash
composer install
```

This will install PHPMailer and other required dependencies.

### Step 3: Database Setup
1. Create a MySQL database
2. Import the database schema from `database.sql`
3. Import office database from `office_db.sql` (for admin access)

### Step 4: Configuration
Update database credentials in all PHP files:
- Replace database host, username, password, and database name
- Update email configuration in PHPMailer settings (if using email features)

### Step 5: Web Server Configuration
- Place the project in your web server's document root (e.g., `htdocs`, `www`, or `public_html`)
- Ensure PHP is enabled and MySQL extension is available
- Set proper file permissions

## 🗄 Database Setup

### Main Database (`database_db`)
The system uses the following main tables:

- **students** - Student information and credentials
- **faculty** - Faculty information and credentials
- **exams** - Exam details
- **classes** - Class information linked to exams
- **subjects** - Subject information linked to classes
- **faculty_assignments** - Faculty-to-exam assignments with encoded numbers
- **marks** - Student marks for exams
- **contact_us** - Contact form submissions

### Office Database (`office_db`)
- **office_users** - Administrative user credentials

To set up the databases, run:
```sql
-- Import main database
mysql -u username -p < database.sql

-- Import office database
mysql -u username -p < office_db.sql
```

## ⚙️ Configuration

### Database Configuration
Update the following in all PHP files that require database connection:
```php
$host = "your_host";
$username = "your_username";
$password = "your_password";
$database = "your_database";
```

### Email Configuration (Optional)
If you want to enable email features, configure PHPMailer in the relevant files:
- `forgot_s_password.php`
- `forgot_f_pass.php`

## 🚀 Usage

### Accessing the System
1. Open your web browser
2. Navigate to `http://localhost/your-project-folder/`
3. You'll be directed to the home page

### Student Login
1. Click on "Login" from the home page
2. Select "Student Login"
3. Enter your roll number and password
4. Access your dashboard and view results

### Faculty Login
1. Click on "Login" from the home page
2. Select "Teacher Login"
3. Enter your faculty ID and password
4. Access assigned evaluations and assign marks

### Office/Admin Login
1. Click on "Login" from the home page
2. Select "Office Use"
3. Enter admin credentials
4. Manage exams, classes, subjects, and faculty assignments

## 👥 User Roles

### 1. Student
- **Registration**: Students can create accounts with personal details
- **Login**: Access using roll number and password
- **Dashboard**: View personalized information
- **Results**: View and download exam results
- **Password Recovery**: Reset password via email

### 2. Faculty/Teacher
- **Registration**: Faculty can register with their details
- **Login**: Access using faculty ID and password
- **Dashboard**: View assigned tasks
- **Evaluation**: Assign marks to encoded hall tickets
- **History**: Track evaluation progress

### 3. Office/Administrator
- **Login**: Access using admin credentials
- **Management**: Add/manage exams, classes, and subjects
- **Assignment**: Assign faculty to evaluation tasks
- **Reports**: Generate class-wise and subject-wise reports
- **Bulk Operations**: Perform bulk faculty assignments

## 📁 Project Structure

```
coding-decoding-management-system/
│
├── index.html                 # Home page
├── loginportal.html           # Login portal
├── signup.html                # Registration page
├── signup.php                 # Registration handler
│
├── studentlogin.php           # Student login
├── studentdashboard.php       # Student dashboard
├── studentlogout.php          # Student logout
├── results.php                # Results viewing page
├── get_results.php            # AJAX results fetcher
│
├── teacherlogin.html          # Faculty login page
├── teacherlogin.php           # Faculty login handler
├── dashboard.php              # Faculty dashboard
├── logout.php                 # Faculty logout
├── go_for_evaluation.php      # Evaluation interface
├── assign_marks.php           # Marks assignment
│
├── o_login.html               # Office login
├── add_exam_subject.html      # Add exam/subject interface
├── add_exam_subject.php       # Exam/subject handler
├── assign_faculty.html        # Faculty assignment interface
├── assign_faculty.php         # Faculty assignment handler
├── assignbulk_faculty.html    # Bulk assignment interface
├── assignbulk_faculty.php     # Bulk assignment handler
│
├── contactus.html             # Contact page
├── contactus.php              # Contact form handler
├── aboutus.html               # About page
├── service.html               # Services page
├── gallery.html               # Gallery page
├── team.html                  # Team page
│
├── database.sql               # Main database schema
├── office_db.sql              # Office database schema
├── composer.json              # PHP dependencies
├── composer.lock              # Dependency lock file
│
├── vendor/                    # Composer dependencies
│   └── phpmailer/             # PHPMailer library
│
└── README.md                  # This file
```

## 🔒 Security Features

- Password hashing using PHP's `password_hash()` function
- Prepared statements to prevent SQL injection
- Session management for secure authentication
- Input validation and sanitization
- Role-based access control

## 🎨 UI/UX Features

- Modern gradient designs
- Responsive layout for all devices
- Smooth transitions and animations
- User-friendly navigation
- Intuitive dashboard interfaces
- Dark mode support (on home page)

## 📝 Notes

- **Database Credentials**: Remember to update database credentials in all PHP files before deployment
- **Email Configuration**: Email features require proper SMTP configuration
- **File Permissions**: Ensure proper file permissions for uploads (if any)
- **Session Security**: Configure PHP session settings for production use

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 Author

Developed by N YASWANTH as  part of an educational management system project.

## 🙏 Acknowledgments

- PHPMailer for email functionality
- jQuery team for the excellent library
- All contributors and testers


## 🔄 Version History

- **v1.0.0** - Initial release
  - Student registration and login
  - Faculty registration and login
  - Exam management
  - Hall ticket encoding/decoding
  - Results viewing
  - Contact form

---

**Note**: This system is designed for educational institutions to manage exam evaluations with anonymity and fairness. Ensure proper security measures are in place before deploying to production.

