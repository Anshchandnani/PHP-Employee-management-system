<?php
	session_start();
	if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] != 'employee') {
		echo "Invalid Access";
		header('refresh: 2; url=login.php');
		exit();
	}

	include 'db_connection.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$emp_id = isset($_SESSION['emp_id']) ? intval($_SESSION['emp_id']) : 0;
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
		$leave_desc = trim($_POST['leave_desc']);
		$errors = [];

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_date)) {
			$errors[] = "Invalid date format.";
		}

		if (strtotime($from_date) > strtotime($to_date)) {
			$errors[] = "From Date cannot be after To Date.";
		}

		if (empty($leave_desc)) {
			$errors[] = "Leave description is required.";
		}

		if (!empty($errors)) {
			echo "<div class='error-box'>";
			foreach ($errors as $error) {
				echo "<p>$error</p>";
			}
			echo "</div>";
			$conn->close();
			header('refresh: 4; url=employee_home.php');
			exit();
		}

		$status = 0;
		$sql = "INSERT INTO leave_info (emp_id, leave_desc, from_date, to_date, status)
				VALUES ($emp_id, '$leave_desc', '$from_date', '$to_date', '$status')";

		if ($conn->query($sql) === TRUE) {
			echo "<div class='success-box'>Leave request submitted successfully.</div>";
		} else {
			echo "<div class='error-box'>Error: " . $conn->error . "</div>";
		}

		$conn->close();
		header('refresh: 4; url=employee_home.php');
		exit();
	}
?>

<html>
<head>
    <title>Leave Request</title>
    <style>
        body {background:#f4f6f9;font-family:'Segoe UI',sans-serif;padding:10px;display:flex;justify-content:center;}
		.form-container {background:#fff;padding:30px 40px;border-radius:22px;box-shadow:0 0 10px rgba(0,0,0,0.15);max-width:500px;width:100%;}
		.form-container h1 {text-align:center;margin-bottom:0;color:#35424a;}
		label {font-weight:bold;display:block;margin:20px 0 8px;color:#333;}
		input[type="text"],input[type="date"],textarea {width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;margin-bottom:10px;font-size:16px;}
		textarea {resize:vertical;}
		input[type="submit"] {background:#35424a;color:#fff;padding:15px;border:none;border-radius:6px;cursor:pointer;font-weight:bold;width:100%;margin-top:10px;font-size:18px;}
		input[type="submit"]:hover {background:#2b373d;}
		.success-box,.error-box {padding:15px;margin-bottom:20px;border-radius:6px;text-align:center;border:1px solid;}
		.success-box {background:#eafaf1;color:#2f7a4d;border-color:#c1e7d0;}
		.error-box {background:#ffe6e6;color:#cc0000;border-color:#ffb3b3;}
    </style>
    <script>
        function validateForm() {
            const leaveDesc = document.forms["leaveForm"]["leave_desc"].value.trim();
            const fromDate = document.forms["leaveForm"]["from_date"].value;
            const toDate = document.forms["leaveForm"]["to_date"].value;
            const errorBox = document.getElementById("client-errors");
            errorBox.innerHTML = "";
            let errors = [];
            if (!leaveDesc) {
                errors.push("Leave description is required.");
            }
            if (!fromDate || !toDate) {
                errors.push("Both dates are required.");
            } else if (new Date(fromDate) > new Date(toDate)) {
                errors.push("From Date cannot be after To Date.");
            }
            if (errors.length > 0) {
                let html = "<div class='error-box'>";
                errors.forEach(e => html += `<p>${e}</p>`);
                html += "</div>";
                errorBox.innerHTML = html;
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h1>Leave Request Form</h1>
        <div id="client-errors"></div>
        <form name="leaveForm" method="POST" action="" onsubmit="return validateForm()">
            <label for="leave_desc">Reason for Leave:</label>
            <textarea name="leave_desc" rows="4" required></textarea>
		
            <label for="from_date">From Date:</label>
            <input type="date" name="from_date" required>

            <label for="to_date">To Date:</label>
            <input type="date" name="to_date" required>
			
            <input type="submit" value="Submit Leave Request">
        </form>
    </div>
</body>
</html>