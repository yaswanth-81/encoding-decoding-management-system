<?php
// Start the session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['rollno'])) {
    header("Location: studentlogin.php");
    exit();
}

// Retrieve the student roll number from the session
$rollno = $_SESSION['rollno'];

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

// Fetch available exams
$examQuery = "SELECT exam_id, exam_name FROM exams";
$examResult = $conn->query($examQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Fully Improved Internal CSS -->
    <style>
        /* General Page Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        /* Header */
        h2 {
            color: #333;
            margin-top: 20px;
            font-size: 32px;
            font-weight: bold;
        }

        /* Dropdown Container */
        .dropdown-container {
            margin: 30px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* Labels */
        label {
            font-size: 18px;
            font-weight: bold;
        }

        /* Dropdown Styling */
        select {
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 2px solid #007bff;
            background: white;
            color: #333;
            outline: none;
            cursor: pointer;
            transition: 0.3s ease;
        }

        select:hover, select:focus {
            border-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 91, 187, 0.2);
        }

        /* Table Styling */
        table {
            width: 90%; /* Increased width */
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        td {
            background: #f9f9f9;
            font-size: 18px;
        }

        /* Messages */
        p {
            font-size: 20px;
            margin: 15px 0;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        /* Results Box */
        #result-container {
            margin-top: 30px;
            padding: 20px;
            background: white;
            display: inline-block;
            border-radius: 10px;
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1200px;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dropdown-container {
                flex-direction: column;
                align-items: center;
            }

            table {
                width: 100%;
            }

            select {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <h2>Student Results</h2>
    <p><strong>Roll No:</strong> <?php echo htmlspecialchars($rollno); ?></p>

    <!-- Dropdowns -->
    <div class="dropdown-container">
        <label for="exam">Select Exam:</label>
        <select id="exam" name="exam">
            <option value="">-- Select Exam --</option>
            <?php
            while ($exam = $examResult->fetch_assoc()) {
                echo "<option value='{$exam['exam_id']}'>{$exam['exam_name']}</option>";
            }
            ?>
        </select>

        <label for="class">Select Class:</label>
        <select id="class" name="class">
            <option value="">-- Select Class --</option>
        </select>
    </div>

    <div id="result-container"></div>

    <script>
        $(document).ready(function () {
            $("#exam").change(function () {
                var examId = $(this).val();
                if (examId) {
                    $.ajax({
                        type: "POST",
                        url: "get_c.php",
                        data: { exam_id: examId },
                        dataType: "html",
                        success: function (response) {
                            $("#class").html(response);
                        }
                    });
                } else {
                    $("#class").html('<option value="">-- Select Class --</option>');
                    $("#result-container").html('');
                }
            });

            $("#class").change(function () {
                var examId = $("#exam").val();
                var className = $(this).val();
                var rollno = "<?php echo $rollno; ?>";

                if (className) {
                    $.ajax({
                        type: "POST",
                        url: "get_results.php",
                        data: { exam_id: examId, class_name: className, roll_no: rollno },
                        success: function (response) {
                            $("#result-container").html(response);
                        },
                        error: function (xhr, status, error) {
                            console.log("AJAX Error:", error);
                            console.log("Response:", xhr.responseText);
                            $("#result-container").html("<p class='error'>Error fetching results.</p>");
                        }
                    });
                } else {
                    $("#result-container").html('');
                }
            });
        });
    </script>
    <!-- Add this button below the result container -->
<button id="downloadBtn" style="display: none; margin-top: 20px; padding: 10px 20px; font-size: 16px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Download as Image</button>

<!-- Include html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    $(document).ready(function () {
        $("#class").change(function () {
            var examId = $("#exam").val();
            var className = $(this).val();
            var rollno = "<?php echo $rollno; ?>";

            if (className) {
                $.ajax({
                    type: "POST",
                    url: "get_results.php",
                    data: { exam_id: examId, class_name: className, roll_no: rollno },
                    success: function (response) {
                        $("#result-container").html(response);
                        
                        // Show download button if table is found
                        if ($("#result-container table").length > 0) {
                            $("#downloadBtn").show();
                        } else {
                            $("#downloadBtn").hide();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("AJAX Error:", error);
                        console.log("Response:", xhr.responseText);
                        $("#result-container").html("<p class='error'>Error fetching results.</p>");
                        $("#downloadBtn").hide();
                    }
                });
            } else {
                $("#result-container").html('');
                $("#downloadBtn").hide();
            }
        });

        // Function to capture the result as an image
        $("#downloadBtn").click(function () {
            html2canvas(document.querySelector("#result-container")).then(canvas => {
                let link = document.createElement("a");
                link.href = canvas.toDataURL("image/png");
                link.download = "Result.png";
                link.click();
            });
        });
    });
</script>

</body>
</html>
