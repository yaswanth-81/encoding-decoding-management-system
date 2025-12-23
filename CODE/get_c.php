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

if (isset($_POST['exam_id'])) {
    $examId = $_POST['exam_id'];

    $query = "SELECT class_name FROM classes WHERE exam_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $examId);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">-- Select Class --</option>';
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['class_name']}'>{$row['class_name']}</option>";
    }
}
?>
