<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('fpdf/fpdf.php');
    include 'db_connection.php';

    $emp_id = $_POST['emp_id'];
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
        exit(); // Stops further output.
    } else {
        $error = "No employee record found for ID: $emp_id";
    }

    $conn->close();
}
include 'header_admin.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Generate Employee PDF</title>
    <style>
        body {font-family: Arial, sans-serif;background: #f4f4f4;margin: 0;padding: 0;}
        h2 {text-align: center;margin-top: 20px;}
        form {background: #fff;padding: 20px;border-radius: 15px;max-width: 420px;margin: 40px auto;box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        label {font-weight: bold;display: block;margin-bottom: 5px;}
        input[type="number"],input[type="submit"] {width: 97%;padding: 10px;margin-bottom: 15px;border: 1px solid #ccc;border-radius: 5px;}
        input[type="submit"] {width: 102%;background: #35424a;color: #fff;border: none;cursor: pointer;}
        input[type="submit"]:hover {background: #222;}
        p.error {color: red;text-align: center;}
    </style>
</head>
<body>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form action="" method="post">
		<h2>Enter Employee ID to Generate Salary Slip</h2>
        <label for="emp_id">Employee ID:</label>
        <input type="number" name="emp_id" id="emp_id" required>
        <input type="submit" value="Generate PDF">
    </form>
	<?php include 'footer_admin.php'; ?>
</body>
</html>