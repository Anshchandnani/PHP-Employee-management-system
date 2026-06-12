<?php
	include 'header_admin.php';
	include 'db_connection.php';

	function mark_attendance($conn, $emp_id, $status) {
		$today = date('Y-m-d');
		$check = $conn->query("SELECT * FROM attendance_info WHERE emp_id='$emp_id' AND date='$today'");
		if ($check->num_rows == 0) {
			$conn->query("INSERT INTO attendance_info (emp_id, date, status) VALUES ('$emp_id', '$today', '$status')");
		}
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['emp_id'])) {
		$emp_id = $_POST['emp_id'];
		$status = ($_POST['action'] === 'present') ? 'Present' : 'Absent';
		mark_attendance($conn, $emp_id, $status);
		header("Location: attendance_today_admin.php");
		exit();
	}

	$employees = $conn->query("SELECT emp_id, name FROM emp_info");
	$today = date('Y-m-d');

	$attendance_today = [];
	$attendance_result = $conn->query("SELECT emp_id, status FROM attendance_info WHERE date = '$today'");
	while ($row = $attendance_result->fetch_assoc()) {
		$attendance_today[$row['emp_id']] = $row['status'];
	}
?>

<html>
<head>
    <title>Manage Today's Attendance</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        h2 { text-align: center; }
        table { width: 100%; background: white; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #35424a; color: white; }
        .btn-present { background: green; color: white; border: none; padding: 6px 10px; cursor: pointer; }
        .btn-absent { background: red; color: white; border: none; padding: 6px 10px; cursor: pointer; }
        .marked { font-weight: bold; padding: 6px 10px; color: white; border-radius: 4px; }
        .present-marked { background-color: green; }
        .absent-marked { background-color: red; }
    </style>
</head>
<body>
    <h2>Mark Today's Attendance</h2>
    <table>
        <tr><th>Employee ID</th><th>Name</th><th>Action</th></tr>
        <?php
		while ($row = $employees->fetch_assoc()) {
			$emp_id = $row['emp_id'];
			$status = isset($attendance_today[$emp_id]) ? $attendance_today[$emp_id] : '';

			echo "<tr>";
			echo "<td>$emp_id</td>";
			echo "<td>" . $row['name'] . "</td>";
			echo "<td>";

			if ($status) {
				$marked_class = (strtolower($status) === 'present') ? 'present-marked' : 'absent-marked';
				echo "<span class='marked $marked_class'>{$status} (Marked)</span>";
			} else {
				echo "<form method='POST' style='display:inline;'>
						<input type='hidden' name='emp_id' value='$emp_id'>
						<input type='hidden' name='action' value='present'>
						<input type='submit' class='btn-present' value='Present'>
					  </form>
					  <form method='POST' style='display:inline;'>
						<input type='hidden' name='emp_id' value='$emp_id'>
						<input type='hidden' name='action' value='absent'>
						<input type='submit' class='btn-absent' value='Absent'>
					  </form>";
			}

			echo "</td>";
			echo "</tr>";
		}
		?>
    </table>
	<?php 	include 'footer_admin.php'; ?>
</body>
</html>