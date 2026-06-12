<?php
	include 'header_admin.php';

	include 'db_connection.php';

	$form_values = [
		'name' => '',
		'e_id' => '',
		'address' => '',
		'job' => '',
		'email' => '',
		'phone' => '',
		'salary' => ''
	];
	$error_messages = [];

	function handle_add_employee($conn, &$form_values, &$error_messages) {
		$name=$form_values['name'] = trim($_POST['name']);
		$e_id=$form_values['e_id'] = trim($_POST['e_id']);
		$address=$form_values['address'] = trim($_POST['address']);
		$job=$form_values['job'] = trim($_POST['job']);
		$email=$form_values['email'] = trim($_POST['email']);
		$phone=$form_values['phone'] = trim($_POST['phone']);
		$salary=$form_values['salary'] = trim($_POST['salary']);
		$pass = $_POST['password'];
		$rpass = $_POST['rpassword'];

		$errors = [];

		if (empty($name) || empty($address) || empty($e_id) || empty($job) || empty($email) || empty($phone) || empty($salary) || empty($pass) || empty($rpass)) {
			$errors[] = "All fields are required.";
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Invalid email format.";
		}

		if (!preg_match("/^\d{1,6}$/", $e_id) || $e_id == 1 || $e_id == 2) {
			$errors[] = "Invalid employee ID.";
		}

		if (!preg_match("/^\d{10}$/", $phone)) {
			$errors[] = "Invalid phone number.";
		}
		
		if (!preg_match('/^[\s\S]{6,20}$/', $pass)) {
			$errors[] = "Password must be between 6 and 20 characters.";
		}

		if (!is_numeric($salary)) {
			$errors[] = "Salary must be numeric.";
		}

		if ($pass !== $rpass) {
			$errors[] = "Passwords do not match.";
		}

		if (preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $name) || preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $job) || preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $address)) {
			$errors[] = "Special characters are not allowed in Name or Job or Address Title.";
		}

		if (!isset($_FILES['emp_img']) || $_FILES['emp_img']['size'] == 0) {
			$errors[] = "Employee image is required.";
		}

		if (!empty($errors)) {
			echo "<script>alert('" . implode("\\n", $errors) . "');</script>";
			return;
		}

		if (isset($_FILES['emp_img']) && $_FILES['emp_img']['tmp_name']) {
			$image_data = addslashes(file_get_contents($_FILES['emp_img']['tmp_name']));
		}

		if (!empty($errors)) {
			$error_messages = $errors;
			return;
		}

		$image_data = '';
		if (isset($_FILES['emp_img']) && $_FILES['emp_img']['tmp_name']) {
			$image_data = addslashes(file_get_contents($_FILES['emp_img']['tmp_name']));
		}

		$sql = "SELECT * FROM emp_info";
		$result2 = $conn->query($sql);
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		$error_db = [];

		while ($emp = $result2->fetch_assoc()) {
			if ($form_values['e_id'] == $emp['emp_id']) $error_db[] = "Employee ID already used";
			if ($form_values['name'] == $emp['name']) $error_db[] = "Employee Name already used";
			if ($form_values['address'] == $emp['address']) $error_db[] = "Employee Address already used";
			if ($form_values['email'] == $emp['email']) $error_db[] = "Employee Email already used";
			if ($form_values['phone'] == $emp['phone']) $error_db[] = "Employee Phone number already used";
			if ($hash == $emp['password']) $error_db[] = "Employee Password already used";

			if (!empty($error_db)) {
				$error_messages = $error_db;
				return;
			}
		}

		$basic_salary = $form_values['salary'];
		$hra = $basic_salary * 0.20;
		$da = $basic_salary * 0.10;
		$allowances = 2500;
		$deductions = 1500;

		$sql = "INSERT INTO emp_info 
			(image_data, emp_id, name, address, job, email, phone, basic_salary, hra, da, allowances, deductions , status, password)
			VALUES 
			('$image_data', '$e_id', '$name', '$address', '$job', '$email', '$phone', '$salary', '$hra', '$da', '$allowances', '$deductions', 1, '$hash')";

		if ($conn->query($sql)) {
			echo "<script>alert('New Employee with ID {$form_values['e_id']} added successfully.'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
			exit;
		} else {
			$error_messages[] = "Error: " . $conn->error;
		}
	}

	function delete_employee($conn, $emp_id) {
		echo "<script>
			if (confirm('Are you sure you want to delete Employee ID $emp_id? This action cannot be undone!')) {
				window.location.href = 'delete_employee_admin.php?confirm_delete=1&emp_id=$emp_id';
			}
		</script>";
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['add_employee'])) {
			handle_add_employee($conn, $form_values, $error_messages);
		} elseif (isset($_POST['delete_emp'])) {
			delete_employee($conn, intval($_POST['e_id']));
		}
	}

	$employees = $conn->query("SELECT * FROM emp_info");
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Employees</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h2 { text-align: center; }
        form { background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: auto; box-shadow: 0 0 10px #ccc; }
        input, textarea { width: 90%; padding: 10px; margin: 10px 0; }
        input[type=submit] { background: #35424a; color: white; border: none; cursor: pointer; }
        input[type=submit]:hover { background: #222; }

        table { width: 100%; margin-top: 40px; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #35424a; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .emp-img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; }
    </style>
    <script>
    function validateForm() {
		const form = document.forms["empForm"];
		const name = String(form["name"].value).trim();
		const e_id = String(form["e_id"].value).trim();
		const address = String(form["address"].value).trim();
		const job = String(form["job"].value).trim();
		const email = String(form["email"].value).trim();
		const phone = String(form["phone"].value).trim();
		const salary = String(form["salary"].value).trim();
		const pass = form["password"].value;
		const rpass = form["rpassword"].value;
		const image = form["emp_img"].files[0];
		const errors = [];

		if (!name || !address || !e_id || !job || !email || !phone || !salary || !pass || !rpass || !image) {
			errors.push("All fields are required.");
		}

		if (!/^\d{1,6}$/.test(e_id)) {
			errors.push("Invalid Employee ID format.");
		}

		if (!/^\d{10}$/.test(phone)) {
			errors.push("Phone number must be 10 digits.");
		}

		if (!/^[\s\S]{6,20}$/.test(pass)) {
			errors.push("Password must be between 6 and 20 characters.");
		}

		if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
			errors.push("Invalid email format.");
		}

		if (isNaN(salary)) {
			errors.push("Salary must be a number.");
		}

		if (pass !== rpass) {
			errors.push("Passwords do not match.");
		}

		// Special character validation for name, job, and address
		if (/[^a-zA-Z\s]/.test(name)) {
			errors.push("Name must not contain special characters or numbers.");
		}

		if (/[^a-zA-Z\s]/.test(job)) {
			errors.push("Job title must not contain special characters or numbers.");
		}

		if (/[^a-zA-Z0-9\s,.-]/.test(address)) {
			errors.push("Address contains invalid characters.");
		}

		if (!image) {
			errors.push("Employee image is required.");
		}

		if (errors.length > 0) {
			alert(errors.join("\n"));
			return false;
		}

		return true;
	}
</script>
</head>
<body>

<h2>All Employees</h2>
<table>
    <tr>
        <th>Image</th><th>Name</th><th>ID</th><th>Address</th><th>Job</th><th>Email</th><th>Phone</th><th>Net Salary</th><th>Status</th><th>Actions</th>
    </tr>
    <?php
    if ($employees->num_rows > 0) {
        while ($row = $employees->fetch_assoc()) {
			$salary = $row['basic_salary'] + $row['hra'] + $row['da'] + $row['allowances'] - $row['deductions'];
            echo "<tr>";
            echo "<td>";
            if (!empty($row['image_data'])) {
                echo "<img class='emp-img' src='data:image/jpeg;base64," . base64_encode($row['image_data']) . "'>";
            } else {
                echo "<img class='emp-img' src='default_user.png'>";
            }
            echo "</td>";
            echo "<td>{$row['name']}</td>
                  <td>{$row['emp_id']}</td>
                  <td>{$row['address']}</td>
                  <td>{$row['job']}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['phone']}</td>
                  <td>".number_format($salary)."</td>
                  <td>" . ($row['status'] == 1 ? 'Hired' : 'On Leave') . "</td>
                  <td>
                    <form method='post' style='display:inline-block;'>
                        <input type='hidden' name='e_id' value='{$row['emp_id']}'>
                        <input type='submit' name='delete_emp' value='Delete' style='background-color:red;color:white;font-weight:bold;padding:6px 10px;'>
                    </form>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No employees found.</td></tr>";
    }
    ?>
</table>

<?php
if (!empty($error_messages)) {
	echo "<div style='color: red; font-weight: bold; text-align:center;'>";
	foreach ($error_messages as $err) {
		echo htmlspecialchars($err) . "<br>";
	}
	echo "</div>";
}
?>

<h2>Add New Employee</h2>
<form name="empForm" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
    <label for="e_id">Employee ID:</label><br>
	<input type="text" name="e_id" id="e_id" required pattern="\d{1,6}" placeholder="Employee ID must be 1 to 6 digits." value="<?= htmlspecialchars($form_values['e_id']) ?>"><br>

    
    <label for="name">Name:</label><br>
    <input type="text" name="name" id="name" required pattern="[A-Za-z\s]+" placeholder="Only letters and spaces allowed." value="<?= htmlspecialchars($form_values['name']) ?>">
	<br>
	
	<label for="emp_img">Employee Image:</label><br>
    <input type="file" name="emp_img" id="emp_img" required accept="image/*" title="Upload an image file.">
	<br>

    <label for="address">Address:</label><br>
    <input type="text" name="address" id="address" required pattern="[A-Za-z0-9\s,.-]+" placeholder="Address must not contain special characters except , . -" value="<?= htmlspecialchars($form_values['address']) ?>"><br>

    <label for="job">Job Title:</label><br>
    <input type="text" name="job" id="job" required pattern="[A-Za-z\s]+" placeholder="Only letters and spaces allowed." value="<?= htmlspecialchars($form_values['job']) ?>"><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required placeholder="Enter a valid email address." value="<?= htmlspecialchars($form_values['email']) ?>"><br>

    <label for="phone">Phone:</label><br>
    <input type="text" name="phone" id="phone" required pattern="\d{10}" placeholder="Phone number must be exactly 10 digits." value="<?= htmlspecialchars($form_values['phone']) ?>"><br>

    <label for="salary">Salary:</label><br>
    <input type="number" name="salary" id="salary" required min="0" step="0.01" placeholder="Enter a valid salary amount." value="<?= htmlspecialchars($form_values['salary']) ?>"><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required minlength="6" maxlength="20" placeholder="Password must be between 6 and 20 characters."><br>

    <label for="rpassword">Re-enter Password:</label><br>
    <input type="password" name="rpassword" id="rpassword" required minlength="6" maxlength="20" placeholder="Password must be between 6 and 20 characters."><br>

    <button type="submit" name="add_employee">Add Employee</button>
</form>

<?php include 'footer_admin.php';?>

</body>
</html>