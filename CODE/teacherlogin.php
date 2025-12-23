<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli('sql212.infinityfree.com', 'if0_40748957', 'endecode', 'if0_40748957_encode_decode');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$facultyid = $_POST['facultyid'];
$password = $_POST['password'];

// Fetch all faculty details with the same facultyid
$stmt = $conn->prepare("SELECT * FROM faculty WHERE facultyid = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $facultyid); // Bind the faculty ID parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Loop through all rows with the same facultyid
    $login_successful = false;
    while ($user = $result->fetch_assoc()) {
        // Verify the password for each row
        if (password_verify($password, $user['password'])) {
            // Password is correct, store faculty ID in session
            $_SESSION['facultyid'] = $facultyid;
            $login_successful = true;
            break; // Exit the loop if a match is found
        }
    }

    if ($login_successful) {
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Password is incorrect for all rows, redirect to login page with error
        header("Location: teacherlogin.html?error=invalid_credentials");
        exit();
    }
} else {
    // Faculty ID not found, redirect to login page with error
    header("Location: teacherlogin.html?error=invalid_credentials");
    exit();
}

$stmt->close();
$conn->close();
?>