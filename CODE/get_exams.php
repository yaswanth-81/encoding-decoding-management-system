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

// Fetch exams
$sql = "SELECT exam_id, exam_name FROM exams";
$result = $conn->query($sql);

$exams = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $exams[] = $row;
    }
}

echo json_encode($exams);
$conn->close();
?>