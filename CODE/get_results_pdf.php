<?php
require('fpdf.php');
$conn = new mysqli("sql212.infinityfree.com", "if0_40748957", "endecode", "if0_40748957_encode_decode");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
if (!isset($_GET['exam_id'], $_GET['class_name'], $_GET['roll_no'])) die("Invalid request.");
$examId = $_GET['exam_id'];
$className = $_GET['class_name'];
$rollno = $_GET['roll_no'];
$stmt = $conn->prepare("SELECT exam_name FROM exams WHERE exam_id = ?");
$stmt->bind_param("i", $examId);
$stmt->execute();
$examName = $stmt->get_result()->fetch_assoc()['exam_name'] ?? die("Exam not found.");
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 16);
$pdf->Cell(190, 10, "Student Results", 1, 1, "C");
$pdf->SetFont("Arial", "", 12);
$pdf->Cell(190, 10, "Exam: $examName", 0, 1);
$pdf->Cell(190, 10, "Class: $className", 0, 1);
$pdf->Cell(190, 10, "Roll No: $rollno", 0, 1);
$stmt = $conn->prepare("SELECT subject, marks FROM marks WHERE class = ? AND exam = ? AND hall_ticket_no = ?");
$stmt->bind_param("sss", $className, $examName, $rollno);
$stmt->execute();
$result = $stmt->get_result();
$pdf->Cell(95, 10, "Subject", 1, 0, "C");
$pdf->Cell(95, 10, "Marks", 1, 1, "C");
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(95, 10, $row['subject'], 1, 0, "C");
    $pdf->Cell(95, 10, $row['marks'], 1, 1, "C");
}
$pdf->Output();
$conn->close();
?>
