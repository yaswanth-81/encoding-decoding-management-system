<?php
header("Content-Type: application/json");

// Database configuration
$host = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$database = "if0_40748957_encode_decode";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Get data from POST request
$data = json_decode(file_get_contents("php://input"), true);
$examName = $data["examName"];
$className = $data["className"];
$subjectName = $data["subjectName"];

// Check if exam already exists
$stmt = $conn->prepare("SELECT exam_id FROM exams WHERE exam_name = ?");
$stmt->bind_param("s", $examName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Exam exists, get exam_id
    $row = $result->fetch_assoc();
    $examId = $row["exam_id"];
} else {
    // Exam does not exist, insert new exam
    $stmt = $conn->prepare("INSERT INTO exams (exam_name) VALUES (?)");
    $stmt->bind_param("s", $examName);
    if (!$stmt->execute()) {
        die(json_encode(["success" => false, "message" => "Error adding exam: " . $stmt->error]));
    }
    $examId = $stmt->insert_id;
}

// Check if class already exists
$stmt = $conn->prepare("SELECT class_id FROM classes WHERE exam_id = ? AND class_name = ?");
$stmt->bind_param("is", $examId, $className);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Class exists, get class_id
    $row = $result->fetch_assoc();
    $classId = $row["class_id"];
} else {
    // Class does not exist, insert new class
    $stmt = $conn->prepare("INSERT INTO classes (exam_id, class_name) VALUES (?, ?)");
    $stmt->bind_param("is", $examId, $className);
    if (!$stmt->execute()) {
        die(json_encode(["success" => false, "message" => "Error adding class: " . $stmt->error]));
    }
    $classId = $stmt->insert_id;
}

// Insert subject
$stmt = $conn->prepare("INSERT INTO subjects (class_id, subject_name) VALUES (?, ?)");
$stmt->bind_param("is", $classId, $subjectName);
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error adding subject: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>