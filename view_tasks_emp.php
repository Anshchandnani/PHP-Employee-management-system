<?php
	include 'header_employee.php';
	include 'db_connection.php';

	$e_id = $_SESSION['emp_id'];

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_completed'])) {
		$task_id = intval($_POST['task_id']);
		$conn->query("UPDATE task_info SET status='1' WHERE task_id=$task_id AND emp_id=$e_id");
	}

	$search = '';
	$search_sql = "SELECT * FROM task_info WHERE emp_id=$e_id";
	if (isset($_POST['search'])) {
		$search = trim($_POST['search']);
		$search_sql .= " AND (task_desc LIKE '%$search%' OR assign_date LIKE '%$search%' OR due_date LIKE '%$search%')";
	}

	$result = $conn->query($search_sql);
?>

<main style="padding: 20px;">
	<h2>Employee Task Information</h2>

	<form method="post" style="margin-bottom: 20px;">
		<input type="text" name="search" placeholder="Search by Task, Assign Date, Due Date" value="<?php echo $search; ?>" style="padding: 8px; width: 300px;">
		<input type="submit" value="Search" style="padding: 8px 15px;">
	</form>

	<table style="border-collapse: collapse; width: 100%; background-color: #fff;">
		<tr style="background-color: #35424a; color: white; text-align:left">
			<th style="padding: 10px;">Task Description</th>
			<th>Assign Date</th>
			<th>Due Date</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
		<?php
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				echo "<tr style='background-color: #f9f9f9;'>";
				echo "<td style='padding: 10px;'>{$row['task_desc']}</td>";
				echo "<td>{$row['assign_date']}</td>";
				echo "<td>{$row['due_date']}</td>";
				echo "<td>" . ($row['status'] == 1 ? "Completed" : "Pending") . "</td>";
				echo "<td>";
				if ($row['status'] == 0) {
					echo "<form method='post' style='display:inline;'>
							<input type='hidden' name='task_id' value='{$row['task_id']}'>
							<input type='submit' name='mark_completed' value='Mark as Completed'>
						  </form>";
				} elseif ($row['status'] == 2) {
					echo "Expired";
				}
				else {
					echo "Done";
				}
				echo "</td></tr>";
			}
		} else {
			echo "<tr><td colspan='5' style='padding: 10px;'>No records found</td></tr>";
		}
		$conn->close();
		?>
	</table>
</main>

<?php include 'footer_employee.php'; ?>