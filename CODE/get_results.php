<?php
// Database connection
$servername = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$dbname = "if0_40748957_encode_decode";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure necessary data is received
if (!isset($_POST['exam_id'], $_POST['class_name'], $_POST['roll_no'])) {
    die("Invalid request. Missing parameters.");
}

$examId = $_POST['exam_id'];
$className = $_POST['class_name'];
$rollno = $_POST['roll_no'];

// 🔹 1️⃣ Get the exam name
$examQuery = "SELECT exam_name FROM exams WHERE exam_id = ?";
$stmt = $conn->prepare($examQuery);
if (!$stmt) {
    die("SQL Error (examQuery): " . $conn->error);
}
$stmt->bind_param("i", $examId);
$stmt->execute();
$examResult = $stmt->get_result()->fetch_assoc();
$examName = $examResult['exam_name'] ?? '';

if (!$examName) {
    die("Error: Exam ID not found.");
}

// 🔹 2️⃣ Get class_id from classes table
$classIdQuery = "SELECT class_id FROM classes WHERE class_name = ?";
$stmt = $conn->prepare($classIdQuery);
if (!$stmt) {
    die("SQL Error (classIdQuery): " . $conn->error);
}
$stmt->bind_param("s", $className);
$stmt->execute();
$classIdResult = $stmt->get_result()->fetch_assoc();
$classId = $classIdResult['class_id'] ?? null;

if (!$classId) {
    die("<p style='color:red;'>Error: Class not found.</p>");
}

// 🔹 3️⃣ Get all subjects for the class
$subjectQuery = "SELECT subject_name FROM subjects WHERE class_id = ?";
$stmt = $conn->prepare($subjectQuery);
if (!$stmt) {
    die("SQL Error (subjectQuery): " . $conn->error);
}
$stmt->bind_param("i", $classId);
$stmt->execute();
$subjectResult = $stmt->get_result();

$subjects = [];
while ($row = $subjectResult->fetch_assoc()) {
    $subjects[] = $row['subject_name'];
}
$totalSubjects = count($subjects);

if ($totalSubjects == 0) {
    die("<p style='color:red;'>No subjects found for this class.</p>");
}

// 🔹 4️⃣ Check if marks exist for all subjects
$marksQuery = "SELECT COUNT(DISTINCT subject) as subject_count FROM marks WHERE class = ? AND exam = ? AND hall_ticket_no = ?";
$stmt = $conn->prepare($marksQuery);
if (!$stmt) {
    die("SQL Error (marksQuery): " . $conn->error);
}
$stmt->bind_param("sss", $className, $examName, $rollno);
$stmt->execute();
$marksResult = $stmt->get_result();
$marksData = $marksResult->fetch_assoc();
$countAvailable = $marksData['subject_count'] ?? 0;

// 🔹 5️⃣ If marks for all subjects are not found, show "Evaluation Under Process"
if ($countAvailable < $totalSubjects) {
    echo "<p><strong>Exam:</strong> $examName</p>";
    echo "<p><strong>Class:</strong> $className</p>";
    echo "<p><strong>Roll No:</strong> $rollno</p>";
    echo "<p style='color: red; font-weight: bold;'>Evaluation Under Process...</p>";
} else {
    // 🔹 6️⃣ Fetch and display marks
    $resultQuery = "SELECT subject, marks FROM marks WHERE class = ? AND exam = ? AND hall_ticket_no = ?";
    $stmt = $conn->prepare($resultQuery);
    if (!$stmt) {
        die("SQL Error (resultQuery): " . $conn->error);
    }
    $stmt->bind_param("sss", $className, $examName, $rollno);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<p><strong>Exam:</strong> $examName</p>";
    echo "<p><strong>Class:</strong> $className</p>";
    echo "<p><strong>Roll No:</strong> $rollno</p>";

    echo "<table border='1'>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['subject']}</td>
                <td>{$row['marks']}</td>
              </tr>";
    }
    echo "</table>";
}

$conn->close();
?>
