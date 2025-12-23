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

// Database connection
$conn = new mysqli('sql212.infinityfree.com', 'if0_40748957', 'endecode', 'if0_40748957_encode_decode');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch faculty assignments from the database
$stmt = $conn->prepare("SELECT fa.*, COALESCE(m.marks, NULL) AS assigned_marks FROM faculty_assignments fa LEFT JOIN marks m ON fa.hall_ticket_no = m.hall_ticket_no AND fa.exam = m.exam AND fa.class = m.class AND fa.subject = m.subject WHERE fa.faculty_id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $facultyid); // Bind the faculty ID parameter
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go for Evaluation</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar Styles (Enhanced Background) */
        nav {
            width: 100%;
            background: linear-gradient(90deg, #6a11cb, #2575fc); /* Gradient background */
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

        /* Evaluation Container Styles */
        .evaluation-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
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
            background-color: #6a11cb;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Assign Button Styles */
        .assign-button {
            padding: 8px 16px;
            background: #2575fc;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .assign-button:hover {
            background: #6a11cb;
        }

        .assigned-button {
            padding: 8px 16px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
     <a class="x" href="index.html">HOME</a>
        <a href="dashboard.php">Dashboard</a>
        <!-- <a href="go_for_evaluation.php">Go for Evaluation</a>
        <a href="history_of_evaluation.php">History of Evaluation</a>
        <a href="add_exam_subject.php">Add Exam/Subject</a>
        <a href="assign_faculty.php">Assign Faculty</a> -->
        <a href="contactus.html">Contact Us</a>
        <!-- <a href="gallery.html">Gallery</a> -->
    </nav>

    <!-- Evaluation Content -->
    <div class="evaluation-container">
        <h1>Go for Evaluation</h1>
        <p>Welcome, Faculty ID: <strong><?php echo htmlspecialchars($facultyid); ?></strong></p>

        <!-- Display Faculty Assignments in a Table -->
        <table>
            <thead>
                <tr>
                    <th>Encoded Number</th>
                    <th>Exam Name</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['encoded_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['exam']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['class']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                        echo "<td>";
                        if ($row['assigned_marks'] !== null) {
                            echo "<button class='assigned-button' disabled>Assigned</button>";
                        } else {
                            echo "<form action='assign_marks.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='encoded_no' value='" . htmlspecialchars($row['encoded_no']) . "'>
                                    <input type='hidden' name='exam' value='" . htmlspecialchars($row['exam']) . "'>
                                    <input type='hidden' name='class' value='" . htmlspecialchars($row['class']) . "'>
                                    <input type='hidden' name='subject' value='" . htmlspecialchars($row['subject']) . "'>
                                    <button type='submit' class='assign-button'>Assign Marks</button>
                                  </form>";
                        }
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No assignments found for this faculty.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
