<?php
// Database Connection
$servername = "sql212.infinityfree.com";
$username = "if0_40748957";
$password = "endecode";
$dbname = "if0_40748957_encode_decode";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Marks (Handling AJAX request)
if (isset($_GET['exam']) && isset($_GET['className']) && isset($_GET['subject']) && isset($_GET['order'])) {
    $exam = $_GET['exam'];
    $class = $_GET['className'];
    $subject = $_GET['subject'];
    $order = $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

    $query = "SELECT hall_ticket_no, marks FROM marks WHERE exam='$exam' AND class='$class' AND subject='$subject' ORDER BY marks $order";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['hall_ticket_no']}</td><td>{$row['marks']}</td></tr>";
    }
    exit; // Stop further execution for AJAX response
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Wise Result</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 30px;
        }
        select, button {
            padding: 10px;
            margin: 10px;
        }
        table {
            width: 60%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        th:hover {
            cursor: pointer;
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h2>View Subject Wise Results</h2>
    
    <label for="exam">Select Exam:</label>
    <select id="exam" onchange="loadClasses()">
        <option value="">Select Exam</option>
        <?php
        // Fetch Exams from DB
        $result = $conn->query("SELECT exam_id, exam_name FROM exams");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['exam_id']}'>{$row['exam_name']}</option>";
        }
        ?>
    </select>
    
    <label for="class">Select Class:</label>
    <select id="class" onchange="loadSubjects()">
        <option value="">Select Class</option>
    </select>
    
    <label for="subject">Select Subject:</label>
    <select id="subject">
        <option value="">Select Subject</option>
    </select>
    
    <button onclick="fetchResults()">View Results</button>

    <h3>Student Marks</h3>
    <table>
        <thead>
            <tr>
                <th onclick="sortResults('asc')">Roll No ↑</th>
                <th onclick="sortResults('desc')">Marks ↓</th>
            </tr>
        </thead>
        <tbody id="results"></tbody>
    </table>

    <script>
function loadClasses() {
    let examId = $('#exam').val();
    $('#class').html('<option value="">Select Class</option>');
    $('#subject').html('<option value="">Select Subject</option>');
    
    if (examId) {
        $.get('get_classes.php', { exam_id: examId }, function(data) {
            if (data.length > 0) {
                data.forEach(function(item) {
                    $('#class').append(`<option value="${item.class_id}">${item.class_name}</option>`);
                });
            } else {
                $('#class').append('<option value="">No Classes Available</option>');
            }
        }, "json");
    }
}


function loadSubjects() {
    let classId = $('#class').val();
    $('#subject').html('<option value="">Select Subject</option>');

    if (classId) {
        $.get('get_subjects.php', { class_id: classId }, function(data) {
            if (data.length > 0) {
                data.forEach(function(item) {
                    $('#subject').append(`<option value="${item.subject_id}">${item.subject_name}</option>`);
                });
            } else {
                $('#subject').append('<option value="">No Subjects Available</option>');
            }
        }, "json");
    }
}


        function fetchResults(order = 'asc') {
            let exam = $('#exam option:selected').text();
            let className = $('#class option:selected').text();
            let subject = $('#subject option:selected').text();
            
            if (exam && className && subject) {
                $.get('subject_result.php', { exam, className, subject, order }, function(data) {
                    $('#results').html(data);
                });
            } else {
                alert('Please select all fields.');
            }
        }

        function sortResults(order) {
            fetchResults(order);
        }
    </script>

</body>
</html>
