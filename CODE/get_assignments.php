<?php
header("Content-Type: application/json");

// Database Configuration
$host = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$database = "if0_40748957_encode_decode";

// Create Database Connection
$conn = new mysqli($host, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Get selected exam_id and class_id
$exam_id = $_GET['exam_id'] ?? '';
$class_id = $_GET['class_id'] ?? '';

if (!$exam_id || !$class_id) {
    echo json_encode([]);
    exit;
}

// First, get the exam and class names for the given IDs
$info_sql = "SELECT 
                e.exam_name AS exam, 
                c.class_name AS class 
             FROM exams e, classes c 
             WHERE e.exam_id = ? AND c.class_id = ?";
$info_stmt = $conn->prepare($info_sql);
$info_stmt->bind_param("ss", $exam_id, $class_id);
$info_stmt->execute();
$info_result = $info_stmt->get_result();

if ($info_result->num_rows === 0) {
    echo json_encode([]);
    exit;
}

$info_row = $info_result->fetch_assoc();
$exam_name = $info_row['exam'];
$class_name = $info_row['class'];

// Fetch faculty assignments for the selected exam and class
$sql = "SELECT 
            fa.exam, 
            fa.class, 
            fa.subject, 
            fa.faculty_id, 
            fa.hall_ticket_no, 
            fa.encoded_no,
            CASE 
                WHEN m.marks IS NOT NULL THEN 'Assigned' 
                ELSE 'Not Assigned' 
            END AS status 
        FROM faculty_assignments fa
        LEFT JOIN marks m 
            ON fa.hall_ticket_no = m.hall_ticket_no 
            AND fa.exam = m.exam 
            AND fa.class = m.class 
            AND fa.subject = m.subject
        WHERE fa.exam = ? AND fa.class = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $exam_name, $class_name);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>