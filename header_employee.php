<?php
	session_start();
	if (!isset($_SESSION['user_name']) || strtolower($_SESSION['user_name']) != 'employee') {
		echo "Invalid Access<br>";
		header('refresh: 2; url=login.php');
		echo 'You will be redirected to Login page in 2 seconds';
		exit();
	}

	include 'db_connection.php';

	$emp_id = $_SESSION['emp_id'];
	$query = "SELECT * FROM emp_info WHERE emp_id = '$emp_id'";
	$result = $conn->query($query);

	$empData = [];
	if ($result && $result->num_rows > 0) {
		$empData = $result->fetch_assoc();
	}
	$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Employee Dashboard - Employee Management System</title>
	<style>
		html,body{height:100%;margin:0;font-family:Arial,sans-serif;background-color:#f4f4f4;display:flex;flex-direction:column}
		header{background:#35424a;color:#fff;padding:20px;position:relative}
		header h1{margin:0}
		.profile-pic{position:absolute;top:20px;right:20px;border-radius:50%;width:60px;height:60px;object-fit:cover;border:2px solid #fff;cursor:pointer}
		nav ul{list-style:none;padding:0;margin:10px 0 0}
		nav ul li{display:inline;margin:0 15px}
		nav ul li a{color:#fff;text-decoration:none}
		main{flex:1;padding:20px}
		footer{background:#35424a;color:#fff;text-align:center;padding:10px}
		.modal{display:none;position:fixed;z-index:100;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.6)}
		.modal-content{background:#fff;margin:10% auto;padding:20px;width:400px;border-radius:10px;text-align:left;position:relative}
		.close-btn{position:absolute;top:10px;right:15px;font-size:20px;font-weight:bold;cursor:pointer}
	</style>
	<script>
		function showModal() {
			document.getElementById("empModal").style.display = "block";
		}
		function closeModal() {
			document.getElementById("empModal").style.display = "none";
		}
		window.onclick = function(event) {
			let modal = document.getElementById("empModal");
			if (event.target == modal) {
				closeModal();
			}
		}
	</script>
</head>
<body>

<header>
	<h1>Employee Dashboard</h1>
	<?php if (!empty($empData['image_data'])): ?>
		<img src="data:image/jpeg;base64,<?php echo base64_encode($empData['image_data']); ?>" class="profile-pic" alt="Employee Image" onclick="showModal()">
	<?php else: ?>
		<img src="default-profile.png" class="profile-pic" alt="Employee Image" onclick="showModal()">
	<?php endif; ?>
	<nav>
		<ul>
			<li><a href="employee_home.php">Home</a></li>
			<li><a href="view_tasks_emp.php">View Your Tasks</a></li>
			<li><a href="view_attendance_emp.php">View Your Attendance</a></li>
			<li><a href="leave_request_emp.php">Request Leave</a></li>
			<li><a href="check_leave_emp.php">Check Your Leaves</a></li>
			<li><a href="generate_salary_slip_emp.php">Generate Salary Slip</a></li>
			<li><a href="change_password_emp.php">Change Password</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</nav>
</header>

<main>