<?php
$conn = new mysqli("sql212.infinityfree.com", "if0_40748957", "endecode", "if0_40748957_encode_decode");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$userType = $_POST['userType'];
$fullname = isset($_POST['fullname']) ? $_POST['fullname'] : (isset($_POST['name']) ? $_POST['name'] : '');
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Validate input fields
if (!$fullname || !$email || !$password || !$confirm_password) {
    die("Error: Missing required fields.");
}

// Validate password match
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

if ($userType === 'student') {
    $rollno = isset($_POST['rollno']) ? $_POST['rollno'] : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';

    $stmt = $conn->prepare("INSERT INTO students (fullname, rollno, dob, gender, mobile, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }
    $stmt->bind_param("sssssss", $fullname, $rollno, $dob, $gender, $mobile, $email, $hashed_password);
} elseif ($userType === 'faculty') {
    $facultyid = isset($_POST['facultyid']) ? $_POST['facultyid'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';

    $stmt = $conn->prepare("INSERT INTO faculty (name, facultyid, email, subject, password) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }
    $stmt->bind_param("sssss", $fullname, $facultyid, $email, $subject, $hashed_password);
} else {
    die("Invalid user type.");
}

// Execute the query
if ($stmt->execute()) {
    echo "Signup successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
