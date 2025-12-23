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
if (isset($_GET['exam']) && isset($_GET['className']) && isset($_GET['hallTicket'])) {
    $exam = $conn->real_escape_string($_GET['exam']);
    $class = $conn->real_escape_string($_GET['className']);
    $hallTicket = $conn->real_escape_string($_GET['hallTicket']);

    $query = "SELECT subject, marks FROM marks WHERE exam='$exam' AND class='$class' AND hall_ticket_no='$hallTicket' ORDER BY subject ASC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['subject']}</td><td>{$row['marks']}</td></tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No records found for this student</td></tr>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        select, input, button {
            padding: 10px;
            margin: 5px 0;
            width: 300px;
            max-width: 100%;
            font-size: 16px;
        }
        button {
            width: 320px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        #results tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Student Results</h2>
        
        <div class="form-group">
            <label for="exam">Select Exam:</label><br>
            <select id="exam" onchange="loadClasses()">
                <option value="">Select Exam</option>
                <?php
                $result = $conn->query("SELECT exam_id, exam_name FROM exams ORDER BY exam_name");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['exam_id']}'>{$row['exam_name']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="class">Select Class:</label><br>
            <select id="class" disabled>
                <option value="">Select Class</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="hallTicket">Hall Ticket/Roll No:</label><br>
            <input type="text" id="hallTicket" placeholder="Enter hall ticket number">
        </div>
        
        <div class="form-group">
            <button onclick="fetchResults()">View Results</button>
        </div>

        <h3>Student Marks</h3>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody id="results">
                <tr>
                    <td colspan="2">Select exam, class and enter hall ticket number</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function loadClasses() {
            let examId = $('#exam').val();
            $('#class').html('<option value="">Select Class</option>');
            
            if (examId) {
                $('#class').prop('disabled', false);
                $.get('get_classes.php', { exam_id: examId }, function(data) {
                    if (data && data.length > 0) {
                        data.forEach(function(item) {
                            $('#class').append(`<option value="${item.class_id}">${item.class_name}</option>`);
                        });
                    } else {
                        $('#class').append('<option value="">No Classes Available</option>');
                    }
                }, "json").fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error loading classes:", textStatus, errorThrown);
                    alert('Error loading classes. Please check console for details.');
                });
            } else {
                $('#class').prop('disabled', true);
            }
        }

        function fetchResults() {
            let exam = $('#exam option:selected').text();
            let classId = $('#class').val();
            let className = $('#class option:selected').text();
            let hallTicket = $('#hallTicket').val().trim();
            
            if (!exam) {
                alert('Please select an exam');
                return;
            }
            if (!classId) {
                alert('Please select a class');
                return;
            }
            if (!hallTicket) {
                alert('Please enter hall ticket number');
                return;
            }

            $('#results').html('<tr><td colspan="2">Loading results...</td></tr>');
            
            $.get(window.location.href, 
                { 
                    exam: exam, 
                    className: className, 
                    hallTicket: hallTicket 
                }, 
                function(data) {
                    $('#results').html(data);
                }
            ).fail(function() {
                $('#results').html('<tr><td colspan="2">Error loading results. Please try again.</td></tr>');
            });
        }
    </script>
</body>
</html>