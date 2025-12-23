<?php
session_start();
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$host = 'sql212.infinityfree.com';
$dbname = 'if0_40748957_encode_decode';
$username = 'if0_40748957';
$password = 'endecode';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Reset session if user restarts process
if (isset($_POST['restart'])) {
    session_destroy();
    session_start();
}

// Step 1: Fetch email based on Roll Number
if (isset($_POST['fetch_email'])) {
    $rollno = trim($_POST['rollno']);

    // Validate Roll Number
    if (empty($rollno)) {
        $error = "Please enter a Roll Number.";
    } else {
        $stmt = $conn->prepare("SELECT email FROM students WHERE rollno = :rollno");
        $stmt->bindParam(':rollno', $rollno);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $_SESSION['rollno'] = $rollno;
            $_SESSION['email'] = $student['email'];
        } else {
            $error = "Roll number not found.";
        }
    }
}

// Step 2: Send OTP to email
if (isset($_POST['send_otp']) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 300; // OTP valid for 5 minutes

    // Send OTP via Email
    $mail = new PHPMailer(true);
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'encodingdecodingmanagement@gmail.com'; // Your Gmail ID
        $mail->Password = 'zhbw lskr qtkl vtya'; // Use App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('encodingdecodingmanagement@gmail.com', 'ENCODING AND DECODING MANAGEMENT SYSTEM');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body = "Your OTP for resetting your password is: $otp. This OTP is valid for 5 minutes.";
    
        $mail->send();
        $otp_sent = "OTP sent to your registered email: " . htmlentities($email);
    } catch (Exception $e) {
        $error = "Failed to send OTP: " . $mail->ErrorInfo;
    }
    
}

// Step 3: Handle OTP Verification
if (isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];
    if ($_SESSION['otp'] == $entered_otp && time() <= $_SESSION['otp_expiry']) {
        $_SESSION['otp_verified'] = true;
    } else {
        $error = "Invalid or expired OTP.";
    }
}

// Step 4: Handle Password Reset
if (isset($_POST['reset_password']) && $_SESSION['otp_verified']) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE students SET password = :password WHERE rollno = :rollno");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':rollno', $_SESSION['rollno']);
        $stmt->execute();

        session_destroy(); // Clear session
        header("Location: studentlogin.php?msg=Password updated successfully.");
        exit();
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Step 1: Enter Roll Number -->
        <?php if (!isset($_SESSION['email'])): ?>
            <form method="POST">
                <input type="text" name="rollno" placeholder="Enter Roll Number" required>
                <button type="submit" name="fetch_email">Fetch Email</button>
            </form>
        <?php elseif (!isset($_SESSION['otp'])): ?>
            <!-- Step 2: Show Email & Send OTP -->
            <form method="POST">
                <input type="text" name="email" value="<?php echo htmlentities($_SESSION['email']); ?>" readonly>
                <button type="submit" name="send_otp">Send OTP</button>
            </form>
        <?php elseif (!isset($_SESSION['otp_verified'])): ?>
            <!-- Step 3: Enter OTP -->
            <form method="POST">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <button type="submit" name="verify_otp">Verify OTP</button>
            </form>
        <?php else: ?>
            <!-- Step 4: Reset Password -->
            <form method="POST">
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="restart" style="margin-top:10px; background-color: red;">Restart Process</button>
        </form>

    </div>
</body>
</html>
