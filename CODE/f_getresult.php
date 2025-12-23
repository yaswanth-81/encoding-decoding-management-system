<?php
// Database connection
$servername = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$dbname = "if0_40748957_encode_decode";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure data is received
if (!isset($_POST['roll_no'], $_POST['exam'], $_POST['class_name'])) {
    die("<p class='error'>Invalid request. Missing parameters.</p>");
}

$rollno = $_POST['roll_no'];
$exam = $_POST['exam'];
$className = $_POST['class_name'];

// Fetch completed subjects and marks for the student
$sql = "SELECT subject, marks FROM marks 
        WHERE hall_ticket_no = ? AND exam = ? AND class = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("<p class='error'>SQL Error: " . $conn->error . "</p>");
}
$stmt->bind_param("sss", $rollno, $exam, $className);
$stmt->execute();
$result = $stmt->get_result();

// Display results
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['subject']}</td>
                <td>{$row['marks']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>No results found for this student.</p>";
}

$conn->close();
?>
