<?php
	include 'header_admin.php';
	include 'db_connection.php';

	$search = '';
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
		$search = trim($_POST['search']);
		$search_emp_id = intval($search);
		$query = "SELECT * FROM task_info WHERE emp_id = $search_emp_id";
	} else {
		$query = "SELECT * FROM task_info";
	}

	$result = $conn->query($query);
?>

<html>
<head>
    <title>Tasks Information Table</title>
    <style>
        body{font-family:Arial,sans-serif;background-color:#f0f0f0;padding:20px}
		table{border-collapse:collapse;width:100%;background-color:#fff}
		th,td{padding:10px;text-align:left;border:1px solid #ccc}
		th{background-color:#35424a;color:#fff}
		tr:nth-child(even){background-color:#f9f9f9}
		a{color:#35424a;text-decoration:none;font-weight:bold}
		.search-form{margin-bottom:20px}
		.search-form input[type="text"]{padding:8px;width:200px}
		.search-form input[type="submit"]{padding:8px 15px;background-color:#35424a;color:#fff;border:none;cursor:pointer}
		form{text-align:right;margin-bottom:20px}
		input[type=text],input[type=submit]{padding:10px;margin:10px}
    </style>
</head>
<body>

    <h2>Employee Task Information</h2>

    <form method="POST" class="search-form">
        <input type="text" name="search" placeholder="Search by Employee ID" value="<?php echo $search; ?>">
        <input type="submit" value="Search">
    </form>

    <table>
        <tr>
            <th>Employee ID</th>
            <th>Task Description</th>
            <th>Assign Date</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['emp_id']."</td>";
                echo "<td>".$row['task_desc']."</td>";
                echo "<td>".$row['assign_date']."</td>";
                echo "<td>".$row['due_date']."</td>";
                echo "<td style='color: ".($row['status'] == 0 ? "red" : ($row['status'] == 1 ? "green" : "red"))."; font-weight: bold;'>".($row['status'] == 0 ? "Pending" : ($row['status'] == 1 ? "Completed" : "Expired"))."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <?php include 'footer_admin.php'; ?>

</body>
</html>