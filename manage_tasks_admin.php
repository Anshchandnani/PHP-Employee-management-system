<?php
	include 'header_admin.php';	
	include 'db_connection.php';

	function fetch_employees($conn) {
		return $conn->query("SELECT emp_id, name FROM emp_info");
	}

	function fetch_pending_tasks($conn) {
		$today = date("Y-m-d");
		$sql = "SELECT t.task_id, t.emp_id, e.name, t.task_desc, t.due_date 
				FROM task_info t 
				JOIN emp_info e ON t.emp_id = e.emp_id 
				WHERE t.status = '0' AND t.due_date >= '$today'";
		return $conn->query($sql);
	}


	function delete_task($conn, $task_id) {
		$task_id = intval($task_id);
		$sql = "DELETE FROM task_info WHERE task_id = $task_id";
		return $conn->query($sql);
	}

	function mark_task_complete($conn, $task_id) {
		$task_id = intval($task_id);
		$sql = "UPDATE task_info SET status = '1' WHERE task_id = $task_id";
		return $conn->query($sql);
	}

	function assign_new_task($conn, $emp_id, $task_desc, $due_date) {
		$emp_id = intval($emp_id);
		$task_desc = $task_desc;
		$assign_date = date("Y-m-d");
		$sql = "INSERT INTO task_info (emp_id, task_desc, assign_date, due_date, status)
				VALUES ($emp_id, '$task_desc', '$assign_date', '$due_date', '0')";
		return $conn->query($sql);
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['delete_task'])) {
			$task_id = $_POST['task_id'];
			delete_task($conn, $task_id);
			echo "<script>alert('Task deleted successfully');</script>";
		}

		if (isset($_POST['mark_complete'])) {
			$task_id = $_POST['task_id'];
			mark_task_complete($conn, $task_id);
			echo "<script>alert('Task marked as completed');</script>";
		}

		if (isset($_POST['add_task'])) {
			$emp_id = $_POST['emp_id'];
			$task_desc = $_POST['task_desc'];
			$due_date = $_POST['due_date'];
			if (!empty($emp_id) && !empty($task_desc) && !empty($due_date)) {
				assign_new_task($conn, $emp_id, $task_desc, $due_date);
				echo "<script>alert('Task added successfully');</script>";
			} else {
				echo "<script>alert('All fields are required');</script>";
			}
		}
		echo "<meta http-equiv='refresh' content='1'>";
	}

	$tasks = fetch_pending_tasks($conn);
	$employees = fetch_employees($conn);
	?>

<html>
<head>
    <title>Manage Employee Tasks</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px; }
        table { border-collapse: collapse; width: 100%; background-color: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #333; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
        .btn-delete { background-color: #dc3545; }
        .btn-delete:hover { background-color: #c82333; }
        .form-section { margin-top: 30px; background-color: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .form-section input, .form-section select, .form-section textarea {
            padding: 8px; width: 100%; margin-bottom: 10px;
        }
        .form-section h3 { margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>Pending Employee Tasks</h2>

<?php
echo "<table>";
echo "<tr>
        <th>Employee ID</th>
        <th>Employee Name</th>
        <th>Task Description</th>
        <th>Due Date</th>
        <th>Actions</th>
      </tr>";

if ($tasks && $tasks->num_rows > 0) {
    while ($row = $tasks->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['emp_id']}</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['task_desc']) . "</td>";
        echo "<td>{$row['due_date']}</td>";
        echo "<td>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='task_id' value='{$row['task_id']}'>
                    <button type='submit' name='mark_complete' class='btn'>Mark as Complete</button>
                </form>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='task_id' value='{$row['task_id']}'>
                    <button type='submit' name='delete_task' class='btn btn-delete'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No pending tasks found.</td></tr>";
}
echo "</table>";
?>

<div class="form-section">
    <h3>Assign New Task</h3>
    <form method="POST">
        <label for="emp_id">Select Employee:</label>
		<?php
        echo "<select name='emp_id' required>";
		echo "<option value=''>--Select Employee--</option>";
		while ($emp = $employees->fetch_assoc()) {
			$emp_id = $emp['emp_id'];
			$emp_name = $emp['name'];
			echo "<option value='$emp_id'>$emp_id - $emp_name</option>";
		}
		echo "</select>";
		?>


        <label for="task_desc">Task Description:</label>
        <textarea name="task_desc" rows="3" required></textarea>

        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" required>

        <button type="submit" name="add_task" class="btn">Add Task</button>
    </form>
</div>
<?php include 'footer_admin.php'; ?>
</body>
</html>
