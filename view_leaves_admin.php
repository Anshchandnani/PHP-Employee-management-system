<?php
	include 'header_admin.php';
	include 'db_connection.php';
	$search = '';
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
		$search = trim($_POST['search']);
		$search_emp_id = intval($search);
	}

	if (!empty($search)) {
		$query = "SELECT * FROM leave_info WHERE emp_id = $search_emp_id";
	} else {
		$query = "SELECT * FROM leave_info";
	}
	$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Leave Requests</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 20px; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
		form { text-align: right; margin-bottom: 20px; }
        input[type=text], input[type=submit] {padding: 10px; margin: 10px;}
        th, td { padding: 10px; border: 1px solid #ccc; }
        th { background: #35424a; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
		a {color: #35424a;text-decoration: none;font-weight: bold;}
    </style>
</head>
<body>
<h2>View All Leave Requests</h2>
<form method="POST">
    <input type="text" name="search" placeholder="Search by Employee ID" value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>
<br>
<table>
    <tr>
        <th>Employee ID</th>
        <th>Description</th>
        <th>From</th>
        <th>To</th>
        <th>Status</th>
    </tr>
    <?php
		if ($result && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				echo "<tr>";
				echo "<td>" . $row['emp_id'] . "</td>";
				echo "<td>" . $row['leave_desc'] . "</td>";
				echo "<td>" . $row['from_date'] . "</td>";
				echo "<td>" . $row['to_date'] . "</td>";
				echo "<td>";
				if ($row['status'] == 0) {
					echo "Waiting";
				} elseif ($row['status'] == 1) {
					echo "<span style='color:green; font-weight:bold;'>Approved</span>";
				} else {
					echo "<span style='color:red; font-weight:bold;'>Rejected</span>";
				}
				echo "</td></tr>";
			}
		} else {
			echo "<tr><td colspan='6'>No leave records found.</td></tr>";
		}
	?>
</table>
<?php include'footer_admin.php'; ?>
</body>
</html>