<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'vendor/autoload.php';

// Database connection
$conn = new mysqli('sql212.infinityfree.com', 'if0_40748957', 'endecode', 'if0_40748957_encode_decode');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch faculty assignments that have not been evaluated
$sql = "SELECT fa.encoded_no, fa.exam, fa.class, fa.subject, f.facultyid, f.email, f.name 
        FROM faculty_assignments fa 
        JOIN faculty f ON fa.faculty_id = f.facultyid 
        LEFT JOIN marks m ON fa.hall_ticket_no = m.hall_ticket_no 
            AND fa.exam = m.exam 
            AND fa.class = m.class 
            AND fa.subject = m.subject 
        WHERE m.marks IS NULL";

$result = $conn->query($sql);
$faculty_notifications = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculty_notifications[$row['email']][] = $row;
    }
}

// Store email logs
$email_logs = [];

foreach ($faculty_notifications as $email => $assignments) {
    $faculty_name = $assignments[0]['name'];
    $faculty_id = $assignments[0]['facultyid'];

    // Create new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'encodingdecodingmanagement@gmail.com';  // Replace with your Gmail
        $mail->Password = 'zhbw lskr qtkl vtya';  // Use App Password (not Gmail password)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender & Recipient
        $mail->setFrom('your-email@gmail.com', 'CODING AND DECODING MANAGEMENT SYSTEM');
        $mail->addAddress($email, $faculty_name);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "Pending Evaluations - Action Required";
        
        $message = "<p>Dear $faculty_name,</p>";
        $message .= "<p>The following assignments require your evaluation:</p>";
        $message .= "<ul>";

        foreach ($assignments as $assignment) {
            $message .= "<li><strong>Encoded No:</strong> " . $assignment['encoded_no'] . "<br>";
            $message .= "<strong>Exam:</strong> " . $assignment['exam'] . "<br>";
            $message .= "<strong>Class:</strong> " . $assignment['class'] . "<br>";
            $message .= "<strong>Subject:</strong> " . $assignment['subject'] . "</li><br>";
        }

        $message .= "</ul>";
        $message .= "<p>Please log in and complete the evaluation as soon as possible.</p>";
        $message .= "<p>Thank you for your cooperation.</p>";
        $message .= "<p><strong>Best Regards,</strong><br>CODING AND DECODING MANAGEMENT SYSTEM</p>";

        $mail->Body = $message;

        // Send email
        $mail->send();
        $email_logs[] = [
            "faculty_name" => $faculty_name,
            "faculty_id" => $faculty_id,
            "email" => $email,
            "status" => "Sent Successfully"
        ];
    } catch (Exception $e) {
        $email_logs[] = [
            "faculty_name" => $faculty_name,
            "faculty_id" => $faculty_id,
            "email" => $email,
            "status" => "Failed: {$mail->ErrorInfo}"
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification Status</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        /* Container */
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        /* Success Message */
        .success-msg {
            color: green;
            font-weight: bold;
            font-size: 18px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #2575fc;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Back Button */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #2575fc;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: #6a11cb;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Email Notification Status</h1>
    <p class="success-msg">Emails have been sent successfully to the faculty.</p>

    <table>
        <thead>
            <tr>
                <th>Faculty Name</th>
                <th>Faculty ID</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($email_logs as $log) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($log['faculty_name']) . "</td>";
                echo "<td>" . htmlspecialchars($log['faculty_id']) . "</td>";
                echo "<td>" . htmlspecialchars($log['email']) . "</td>";
                echo "<td>" . htmlspecialchars($log['status']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="add_exam_subject.html" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>
