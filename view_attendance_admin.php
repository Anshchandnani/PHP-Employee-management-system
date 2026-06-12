<?php
	include 'header_admin.php';
	include 'db_connection.php';

	$emp_id = '';
	$result = [];

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$emp_id = trim($_POST['emp_id']);
		if (!empty($emp_id) && preg_match("/^\d{1,6}$/", $emp_id)) {
			$query = "SELECT * FROM attendance_info WHERE emp_id = '$emp_id' AND status = 'Present'";
			$result = $conn->query($query);
		}
	}
?>

<html>
<head>
    <title>View Attendance - Present Dates</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h2 { text-align: center; }
        form { text-align: center; margin-bottom: 20px; }
        input[type=text], input[type=submit] {
            padding: 10px; margin: 10px;
        }
        table { width: 100%; background: white; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #35424a; color: white; }
    </style>
</head>
<body>
    <h2>View Present Attendance Dates by Employee ID</h2>
    <form method="post">
        <input type="text" name="emp_id" placeholder="Enter Employee ID" required>
        <input type="submit" value="Search">
    </form>

    <?php
	if ($result && $result->num_rows > 0) {
		echo "<table>";
		echo "<tr><th>Employee ID</th><th>Date</th><th>Status</th></tr>";
		while ($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td>" . $row['emp_id'] . "</td>";
			echo "<td>" . $row['date'] . "</td>";
			echo "<td>" . $row['status'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
		echo "<p style='text-align:center; color:red;'>No records found for Employee ID " . htmlspecialchars($emp_id) . " with Present status.</p>";
	}
	?>

<?php include 'footer_admin.php'; ?>
</body>
</html>
