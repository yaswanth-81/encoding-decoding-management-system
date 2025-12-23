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

$classId = $_GET["class_id"];

// Fetch subjects for the selected class
$sql = "SELECT subject_id, subject_name FROM subjects WHERE class_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $classId);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

echo json_encode($subjects);
$stmt->close();
$conn->close();
?>  