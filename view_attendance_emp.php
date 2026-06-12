<?php
	include 'header_employee.php';
	include 'db_connection.php';

	$e_id = $_SESSION['emp_id'];
	$result = $conn->query("SELECT * FROM attendance_info WHERE emp_id = $e_id");
?>

<html>
<head>
	<title>Employee Attendance</title>
	<style>
		main {padding: 20px;
			font-family: Arial, sans-serif;
		}
		table {
			border-collapse: collapse;
			width: 100%;
			background-color: #fff;
		}
		th, td {
			padding: 10px;
			text-align: left;
			border: 1px solid #ccc;
		}
		th {
			background-color: #35424a;
			color: #fff;
		}
		tr:nth-child(even) {
			background-color: #f9f9f9;
		}
		.status-present {
			color: green;
			font-weight: bold;
		}
		.status-absent {
			color: red;
			font-weight: bold;
		}
		a {
			display: inline-block;
			margin-top: 20px;
			color: #35424a;
			text-decoration: none;
			font-weight: bold;
		}
	</style>
</head>
<body>

<main>
	<h2>Employee Attendance</h2>

	<table>
		<tr>
			<th>Employee ID</th>
			<th>Date</th>
			<th>Status</th>
		</tr>
		<?php
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$status = strtolower($row['status']) == 'present' ? 
						"<span class='status-present'>Present</span>" : 
						"<span class='status-absent'>Absent</span>";

					echo "<tr>";
					echo "<td>{$row['emp_id']}</td>";
					echo "<td>{$row['date']}</td>";
					echo "<td>{$status}</td>";
					echo "</tr>";
				}
			} else {
				echo "<tr><td colspan='3'>No records found</td></tr>";
			}
			$conn->close();
		?>
	</table>

	<a href="employee_home.php">← Go Back</a>
</main>

<?php include 'footer_employee.php'; ?>
</body>
</html>