<?php
// Start the session
session_start();

// Check if the faculty is logged in
if (!isset($_SESSION['facultyid'])) {
    header("Location: teacherlogin.html");
    exit();
}

// Retrieve the faculty ID from the session
$facultyid = $_SESSION['facultyid'];

// Check if the required POST data is present
if (!isset($_POST['encoded_no']) || !isset($_POST['exam']) || !isset($_POST['class']) || !isset($_POST['subject'])) {
    header("Location: go_for_evaluation.php");
    exit();
}

// Get the details from the form
$encoded_no = $_POST['encoded_no'];
$exam = $_POST['exam'];
$class = $_POST['class'];
$subject = $_POST['subject'];

// Database connection
$host = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$database = "if0_40748957_encode_decode";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch hall_ticket_no from faculty_assignments table
$sql = "SELECT hall_ticket_no FROM faculty_assignments WHERE encoded_no = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $encoded_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hall_ticket_no = $row['hall_ticket_no'];
    } else {
        die("No record found for the given encoded number.");
    }
    $stmt->close();
} else {
    die("Error: " . $conn->error);
}

// Handle form submission to store marks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marks'])) {
    $marks = $_POST['marks'];

    // Insert data into the marks table
    $insertSql = "INSERT INTO marks (hall_ticket_no, exam, class, subject, marks) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    if ($insertStmt) {
        $insertStmt->bind_param("ssssi", $hall_ticket_no, $exam, $class, $subject, $marks);
        if ($insertStmt->execute()) {
            echo "<script>alert('Marks assigned successfully!'); window.location='go_for_evaluation.php';</script>";
        } else {
            echo "<script>alert('Error: " . $insertStmt->error . "');</script>";
        }
        $insertStmt->close();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Marks</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar Styles */
        nav {
            width: 100%;
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            padding: 10px 0;
            display: flex;
            justify-content: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        nav a {
            text-decoration: none;
            color: #fff;
            margin: 0 15px;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* Assign Marks Container Styles */
        .assign-container {
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
            margin-bottom: 20px;
        }

        .details p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background: #2575fc;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="go_for_evaluation.php">Go for Evaluation</a>
        <a href="history_of_evaluation.php">History of Evaluation</a>
        <a href="add_exam_subject.php">Add Exam/Subject</a>
        <a href="assign_faculty.php">Assign Faculty</a>
        <a href="contactus.html">Contact Us</a>
        <a href="gallery.html">Gallery</a>
    </nav>

    <div class="assign-container">
        <h1>Assign Marks</h1>
        <div class="details">
            <p><strong>Encoded Number:</strong> <?php echo htmlspecialchars($encoded_no); ?></p>
            <p><strong>Exam Name:</strong> <?php echo htmlspecialchars($exam); ?></p>
            <p><strong>Class:</strong> <?php echo htmlspecialchars($class); ?></p>
            <p><strong>Subject:</strong> <?php echo htmlspecialchars($subject); ?></p>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="encoded_no" value="<?php echo htmlspecialchars($encoded_no); ?>">
            <input type="hidden" name="exam" value="<?php echo htmlspecialchars($exam); ?>">
            <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
            <input type="hidden" name="subject" value="<?php echo htmlspecialchars($subject); ?>">

            <div class="form-group">
                <label for="marks">Enter Marks:</label>
                <input type="number" id="marks" name="marks" required>
            </div>
            <div class="form-group">
                <button type="submit">Assign Marks</button>
            </div>
        </form>
    </div>
</body>
</html>
