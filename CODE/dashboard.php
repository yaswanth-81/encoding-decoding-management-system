<?php
// Start the session
session_start();

// Check if the faculty is logged in
if (!isset($_SESSION['facultyid'])) {
    // Redirect to login page if not logged in
    header("Location: teacherlogin.html");
    exit();
}

// Retrieve the faculty ID from the session
$facultyid = $_SESSION['facultyid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        nav {
            width: 100%;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            display: flex;
            justify-content: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        nav a {
            text-decoration: none;
            color: #333;
            margin: 0 15px;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s ease, color 0.3s ease;
        }
        nav a:hover {
            background: #6a11cb;
            color: #fff;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .welcome-message {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }
        .logout-button {
            padding: 10px 20px;
            background: #6a11cb;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .logout-button:hover {
            background: #2575fc;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
    <a href="index.html">HOME</a>
        <a href="go_for_evaluation.php">Go for Evaluation</a>
        <!-- <a href="history_of_evaluation.php">History of Evaluation</a>
        <a href="add_exam_subject.php">Add Exam/Subject</a>
        <a href="assign_faculty.php">Assign Faculty</a> -->
        <a href="contactus.html">Contact Us</a>
        <!-- <a href="gallery.html">Gallery</a> -->
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <h1>Welcome to the Dashboard</h1>
        <div class="welcome-message">
            You are logged in as Faculty ID: <strong><?php echo htmlspecialchars($facultyid); ?></strong>
        </div>
        <button class="logout-button" onclick="logout()">Logout</button>
    </div>

    <script>
        function logout() {
            // Redirect to logout page
            window.location.href = "logout.php";
        }
    </script>
</body>
</html>