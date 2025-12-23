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

$examId = $_GET["exam_id"];

// Fetch classes for the selected exam
$sql = "SELECT class_id, class_name FROM classes WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $examId);
$stmt->execute();
$result = $stmt->get_result();

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

echo json_encode($classes);
$stmt->close();
$conn->close();
?>