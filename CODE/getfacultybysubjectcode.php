<?php
header("Content-Type: application/json");
$host = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$database = "if0_40748957_encode_decode";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if (!isset($_GET["subject"])) {
    die(json_encode(["error" => "Subject name is required."]));
}

$subject = trim($_GET["subject"]);

$stmt = $conn->prepare("SELECT facultyid FROM faculty WHERE LOWER(TRIM(subject)) = LOWER(TRIM(?))");
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();

$facultyData = [];
while ($row = $result->fetch_assoc()) {
    $facultyData[] = $row;
}

echo json_encode($facultyData);
$stmt->close();
$conn->close();
?>
