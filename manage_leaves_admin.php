<?php
	include 'db_connection.php';
	$search = '';
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
		$search = trim($_POST['search']);
		$search_emp_id = intval($search);
	}
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_btn'])) {
		$leave_id = intval($_POST['leave_id']);
		$conn->query("UPDATE leave_info SET status = '1' WHERE leave_id = $leave_id");
		header("Location: manage_leaves_admin.php");
		exit();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reject_btn'])) {
		$leave_id = intval($_POST['leave_id']);
		$conn->query("UPDATE leave_info SET status = '2' WHERE leave_id = $leave_id");
		header("Location: manage_leaves_admin.php");
		exit();
	}

	$query = (!empty($search))
		? "SELECT * FROM leave_info WHERE emp_id = $search_emp_id AND from_date >= CURDATE()"
		: "SELECT * FROM leave_info WHERE from_date >= CURDATE()";

	$result = $conn->query($query);
?>

<?php include 'header_admin.php'; ?>
<h2>Manage Leave Requests</h2>

<form method="POST">
	<input type="text" name="search" placeholder="Search by Employee ID" value="<?php echo htmlspecialchars($search); ?>">
	<input type="submit" value="Search">
</form>

<table style="border-collapse: collapse; width: 100%; background-color: #fff;">
	<tr style="background-color: #35424a; color: white; text-align:left">
		<th style="padding: 10px;">Employee ID</th>
		<th>Description</th>
		<th>From</th>
		<th>To</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php
	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			echo "<tr style='background-color: #f9f9f9;'>";
			echo "<td style='padding: 10px;'>{$row['emp_id']}</td>";
			echo "<td>" . htmlspecialchars($row['leave_desc']) . "</td>";
			echo "<td>{$row['from_date']}</td>";
			echo "<td>{$row['to_date']}</td>";

			$status_text = ($row['status'] == 0) ? 'Waiting' : (($row['status'] == 1) ? 'Approved' : 'Rejected');
			echo "<td>$status_text</td>";

			echo "<td>";
			if ($row['status'] == 0) {
				echo "<form method='POST' style='display:inline;'>
						<input type='hidden' name='leave_id' value='{$row['leave_id']}'>
						<input type='submit' name='approve_btn' value='Approve'>
					  </form>
					  <form method='POST' style='display:inline;'>
						<input type='hidden' name='leave_id' value='{$row['leave_id']}'>
						<input type='submit' name='reject_btn' value='Reject'>
					  </form>";
			} else {
				echo "✔ Done";
			}
			echo "</td></tr>";
		}
	} else {
		echo "<tr><td colspan='6'>No leave records found.</td></tr>";
	}
	?>
</table>

<br><h3><a href="admin_home.php">← Go Back</a></h3>
<?php include 'footer_admin.php'; ?>