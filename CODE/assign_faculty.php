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

// Get JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Extract data
$exam = $input['exam'];
$class = $input['class'];
$subject = $input['subject'];
$facultyId = $input['facultyId'];
$hallTicketNo = $input['hallTicketNo'];
$encodedNo = $input['encodedNo'];

// Insert data into the faculty_assignments table
$sql = "INSERT INTO faculty_assignments (exam, class, subject, faculty_id, hall_ticket_no, encoded_no)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ssssss", $exam, $class, $subject, $facultyId, $hallTicketNo, $encodedNo);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Faculty assigned successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

$conn->close();
?>