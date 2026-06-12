<?php
	include 'header_employee.php';
	include 'db_connection.php';

	$emp_id = intval($_SESSION['emp_id']);
	$search_date = '';
	$query = "SELECT * FROM leave_info WHERE emp_id = $emp_id";

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_date'])) {
		$search_date = trim($_POST['search_date']);
		if (!empty($search_date)) {
			$search_date = mysqli_real_escape_string($conn, $search_date);
			$query = "SELECT * FROM leave_info WHERE emp_id = $emp_id AND (from_date = '$search_date' OR to_date = '$search_date')";
		}
	}

	$result = $conn->query($query);
?>

<div style="padding: 20px;">
	<h2>My Leave Records</h2>

	<form method="POST" style="text-align: right;padding:10px; margin-bottom: 20px;">
		<label for="search_date">Search by Date:</label>
		<input type="date" name="search_date" value="<?php echo $search_date; ?>">
		<input type="submit" value="Search">
	</form>

	<table style="text-align: left;border-collapse: collapse; width: 100%; background: #fff;">
		<tr style="background: #35424a; color: white;">
			<th style="padding:10px;">Leave Description</th>
			<th>From Date</th>
			<th>To Date</th>
			<th>Status</th>
		</tr>
		<?php
		if ($result && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				echo "<tr>";
				echo "<td style='padding:10px;'>" . htmlspecialchars($row['leave_desc']) . "</td>";
				echo "<td>" . $row['from_date'] . "</td>";
				echo "<td>" . $row['to_date'] . "</td>";
				echo "<td>";
				if ($row['status'] == 0) {
					echo "<span style='font-weight:bold;'>Waiting</span>";
				} elseif ($row['status'] == 1) {
					echo "<span style='color:green; font-weight:bold;'>Approved</span>";
				} else {
					echo "<span style='color:red; font-weight:bold;'>Rejected</span>";
				}
				echo "</td></tr>";
			}
		} else {
			echo "<tr><td colspan='4'>No leave records found.</td></tr>";
		}
		?>
	</table>

</div>

<?php include 'footer_employee.php'; ?>