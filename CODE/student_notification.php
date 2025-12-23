<?php
// Enable Error Reporting    
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load PHPMailer (Ensure PHPMailer is installed via Composer)
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database Connection
$servername = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$database = "if0_40748957_encode_decode";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch exams for dropdown
$examOptions = "";
$result = $conn->query("SELECT DISTINCT exam FROM faculty_assignments");
while ($row = $result->fetch_assoc()) {
    $examOptions .= "<option value='{$row['exam']}'>{$row['exam']}</option>";
}

// Fetch classes for dropdown
$classOptions = "<option value=''>-- Select Class --</option>";
$result = $conn->query("SELECT DISTINCT class FROM faculty_assignments");
while ($row = $result->fetch_assoc()) {
    $classOptions .= "<option value='{$row['class']}'>{$row['class']}</option>";
}

// Fetch student details when form is submitted
$studentsData = "";
$studentEmails = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['exam']) && isset($_POST['class'])) {
    $exam = $_POST['exam'];
    $class = $_POST['class'];

    // Fetch roll numbers from faculty_assignments
    $query = "SELECT DISTINCT hall_ticket_no FROM faculty_assignments WHERE exam='$exam' AND class='$class'";
    $result = $conn->query($query);

    if (!$result) {
        die("Error fetching roll numbers: " . $conn->error);
    }

    $hall_tickets = [];
    while ($row = $result->fetch_assoc()) {
        $hall_tickets[] = $row['hall_ticket_no'];
    }

    if (!empty($hall_tickets)) {
        // Convert array to a string for SQL query
        $hall_tickets_str = "'" . implode("','", $hall_tickets) . "'";

        // Fetch student details from students table
        $student_query = "SELECT rollno, fullname, email FROM students WHERE rollno IN ($hall_tickets_str)";
        $student_result = $conn->query($student_query);

        if ($student_result->num_rows > 0) {
            while ($student = $student_result->fetch_assoc()) {
                $studentsData .= "
                    <tr>
                        <td>{$student['fullname']}</td>
                        <td>{$student['rollno']}</td>
                        <td>{$student['email']}</td>
                    </tr>";
                // Store email for sending notifications
                $studentEmails[] = [
                    'name' => $student['fullname'],
                    'rollno' => $student['rollno'],
                    'email' => $student['email']
                ];
            }
        } else {
            $studentsData = "<tr><td colspan='3'>No students found.</td></tr>";
        }
    } else {
        $studentsData = "<tr><td colspan='3'>No students found.</td></tr>";
    }
}

// Handle email sending
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendEmails'])) {
    $exam = $_POST['exam'];
    $class = $_POST['class'];

    // Fetch student emails again
    $email_query = "SELECT fullname, rollno, email FROM students WHERE rollno IN (SELECT DISTINCT hall_ticket_no FROM faculty_assignments WHERE exam='$exam' AND class='$class')";
    $email_result = $conn->query($email_query);

    $studentEmails = [];
    while ($email_row = $email_result->fetch_assoc()) {
        $studentEmails[] = [
            'name' => $email_row['fullname'],
            'rollno' => $email_row['rollno'],
            'email' => $email_row['email']
        ];
    }

    // Send Email Notifications
    if (!empty($studentEmails)) {
        sendEmailNotifications($studentEmails, $exam, $class);
        echo "<script>alert('Emails sent successfully!');</script>";
    } else {
        echo "<script>alert('No students found for the selected exam and class.');</script>";
    }
}

// Function to Send Emails using PHPMailer
function sendEmailNotifications($students, $exam, $class) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'encodingdecodingmanagement@gmail.com';  // Your Gmail
        $mail->Password = 'zhbw lskr qtkl vtya';    // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Content
        foreach ($students as $student) {
            $mail->addAddress($student['email']);
            $mail->setFrom('your-email@gmail.com', 'Coding and Decoding Management System');
            $mail->Subject = "Exam Results Available!";
            $mail->Body = "Dear {$student['name']},\n\n"
            . "Your results for the exam '$exam' in class '$class' are now available.\n\n"
            . "Roll Number: {$student['rollno']}\n\n"
            . "Please visit our login portal to view your results:\n"
            . "http://localhost/project/studentlogin.php\n\n"
            . "Best Regards,\nCollege Administration";
            $mail->send();
            $mail->clearAddresses();
        }

        echo "<script>alert('Emails sent successfully!');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Error: {$mail->ErrorInfo}');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 30px;
        }
        select, button {
            padding: 10px;
            margin: 10px;
            font-size: 16px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <h2>Send Exam Notification Emails</h2>
    
    <form method="POST">
        <label for="exam">Select Exam:</label>
        <select name="exam" id="exam" required>
            <option value="">-- Select Exam --</option>
            <?= $examOptions ?>
        </select>

        <label for="class">Select Class:</label>
        <select name="class" id="class" required>
            <?= $classOptions ?>
        </select>

        <button type="submit">Show Students</button>
    </form>

    <?php if (!empty($studentsData)): ?>
        <h3>Student Details</h3>
        <table>
            <tr>
                <th>Full Name</th>
                <th>Roll No</th>
                <th>Email</th>
            </tr>
            <?= $studentsData ?>
        </table>

        <form method="POST">
            <input type="hidden" name="exam" value="<?= htmlspecialchars($_POST['exam'] ?? '') ?>">
            <input type="hidden" name="class" value="<?= htmlspecialchars($_POST['class'] ?? '') ?>">
            <button type="submit" name="sendEmails">Send Notifications</button>
        </form>
    <?php endif; ?>

</body>
</html>