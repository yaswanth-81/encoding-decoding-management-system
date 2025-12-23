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

// Fetch unique exams and classes from faculty_assignments table
$sql = "SELECT DISTINCT exam, class FROM faculty_assignments";
$result = $conn->query($sql);

$exams = [];
$classes = [];
while ($row = $result->fetch_assoc()) {
    $exams[] = $row["exam"];
    $classes[] = $row["class"];
}

// Return JSON response with unique exams and classes
echo json_encode(["exams" => array_values(array_unique($exams)), "classes" => array_values(array_unique($classes))]);

$conn->close();
?>
