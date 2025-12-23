<?php
header("Content-Type: application/json");

// Database Configuration
$host = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$database = "if0_40748957_encode_decode";

// Create Database Connection
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Get JSON input
$input = json_decode(file_get_contents("php://input"), true);
$exam = $input['exam'];
$class = $input['class'];
$subject = $input['subject'];
$rollFrom = $input['rollFrom'];
$rollTo = $input['rollTo'];

// Validate input format
if (strlen($rollFrom) < 3 || strlen($rollTo) < 3) {
    echo json_encode(["success" => false, "message" => "Invalid roll number format"]);
    exit;
}

// Extract the common prefix and last two digits
preg_match('/(.*?)(\d{2})$/', $rollFrom, $matchFrom);
preg_match('/(.*?)(\d{2})$/', $rollTo, $matchTo);

if (!$matchFrom || !$matchTo) {
    echo json_encode(["success" => false, "message" => "Invalid roll number format"]);
    exit;
}

$prefix = $matchFrom[1]; // Common prefix (e.g., "25001A05")
$start = (int)$matchFrom[2]; // Extract last two digits as integer
$end = (int)$matchTo[2]; // Extract last two digits as integer

if ($start > $end) {
    echo json_encode(["success" => false, "message" => "Invalid range for last two digits"]);
    exit;
}

// Function to convert numbers to letters (0 → A, 1 → B, ..., 9 → J)
function convertToAlphabets($number) {
    $map = ['0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', 
            '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J'];
    return strtr($number, $map);
}

// Fetch faculty based on the subject
$facultyQuery = "SELECT facultyid FROM faculty WHERE subject = ?";
$stmt = $conn->prepare($facultyQuery);
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();
$facultyList = [];

while ($row = $result->fetch_assoc()) {
    $facultyList[] = $row['facultyid'];
}
$stmt->close();

if (empty($facultyList)) {
    echo json_encode(["success" => false, "message" => "No faculty found for this subject"]);
    exit;
}

$facultyCount = count($facultyList);
$insertStmt = $conn->prepare("INSERT INTO faculty_assignments (exam, class, subject, faculty_id, hall_ticket_no, encoded_no) VALUES (?, ?, ?, ?, ?, ?)");

// Assign faculty and encode roll numbers
for ($i = $start; $i <= $end; $i++) {
    $facultyId = $facultyList[$i % $facultyCount]; // Assign faculty in round-robin
    $hallTicketNo = $prefix . str_pad($i, 2, "0", STR_PAD_LEFT); // Preserve alphanumeric structure

    // Convert roll number (including prefix) to an all-character format
    $encodedNo = "ENC" . convertToAlphabets($hallTicketNo) . chr(rand(65, 90)) . chr(rand(65, 90)); // Append two random letters

    $insertStmt->bind_param("ssssss", $exam, $class, $subject, $facultyId, $hallTicketNo, $encodedNo);
    $insertStmt->execute();
}

$insertStmt->close();
$conn->close();

echo json_encode([
    "success" => true, 
    "message" => "Faculty assigned and roll numbers encoded successfully!",
    "facultyList" => $facultyList // Send faculty IDs to the frontend
]);

?>
