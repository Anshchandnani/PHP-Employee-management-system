<?php
	require('fpdf/fpdf.php');
	session_start();
	if (!isset($_SESSION['user_name']) || strtolower($_SESSION['user_name']) != 'employee') {
		echo "Invalid Access<br>";
		header('refresh: 2; url=login.php');
		echo 'You will be redirected to Login page in 2 seconds';
		exit();
	}

	include 'db_connection.php';

	$emp_id = $_SESSION['emp_id'];

    $sql = "SELECT * FROM emp_info WHERE emp_id = '$emp_id'";
    $result = $conn->query($sql);

    if ($result->num_rows) {
        $e = $result->fetch_assoc();
        $net_salary = $e['basic_salary'] + $e['hra'] + $e['da'] + $e['allowances'] - $e['deductions'];

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Salary Slip', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        foreach (['Employee ID' => 'emp_id', 'Name' => 'name', 'Job Title' => 'job', 'Email' => 'email', 'Phone' => 'phone'] as $label => $key) {
            $pdf->Cell(50, 10, "$label:", 0);
            $pdf->Cell(0, 10, $e[$key], 0, 1);
        }

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Salary Details', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(70, 10, 'Component', 1);
        $pdf->Cell(60, 10, 'Amount (INR)', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        $components = [
            'Basic Salary' => $e['basic_salary'],
            'HRA' => $e['hra'],
            'DA' => $e['da'],
            'Allowances' => $e['allowances'],
            'Deductions' => -$e['deductions'],
            'Net Salary' => $net_salary
        ];
        foreach ($components as $label => $amount) {
            $pdf->Cell(70, 10, $label, 1);
            $pdf->Cell(60, 10, number_format($amount, 2), 1);
            $pdf->Ln();
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'This is a system-generated salary slip.', 0, 1, 'C');

        $pdf->Output();
    }
    $conn->close();
?>