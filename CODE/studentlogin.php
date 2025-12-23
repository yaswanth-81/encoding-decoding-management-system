<?php
// Database connection
$host = 'sql212.infinityfree.com'; // Database host
$dbname = 'if0_40748957_encode_decode'; // Database name
$username = 'if0_40748957'; // Database username
$password = 'endecode'; // Database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Login functionality
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rollno = $_POST['rollno'];
    $password = $_POST['password'];

    // Fetch student details from the database
    $stmt = $conn->prepare("SELECT * FROM students WHERE rollno = :rollno");
    $stmt->bindParam(':rollno', $rollno);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && password_verify($password, $student['password'])) {
        // Successful login
        $_SESSION['rollno'] = $rollno;
        header("Location: studentdashboard.php");
        exit();
    } else {
        // Invalid credentials
        $error = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
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

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
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
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .links {
            margin-top: 10px;
        }

        .links a {
            color: #007bff;
            text-decoration: none;
            display: block;
            margin-top: 5px;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="studentlogin.php" method="POST">
            <label for="rollno">Roll No:</label>
            <input type="text" id="rollno" name="rollno" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <div class="links">
            <a href="signup.html">New User? Sign Up</a>
            <a href="forgot_s_password.php">Forgot Password?</a>
            <a href="index.html"><-Back to Home</a>
        </div>
    </div>
</body>
</html>
