<?php
	session_start();
	if(!isset($_SESSION['user_name']) || $_SESSION['user_name'] != 'admin') {
		echo "Invalid Access, Only for admin";
		header('refresh: 2; url=login.php');
		echo 'You will be redirected to Login page in 2 seconds';
		exit();
	}
?>
<html>
<head>
	<title>Admin - Employee Management System</title>
	<style>
		body {font-family: Arial, sans-serif;margin: 0;padding: 0;background-color: #f4f4f4;}
		header {background: #35424a;color: #ffffff; padding: 20px 0;text-align: center;}
		nav ul {list-style: none; padding: 0;}
		nav ul li {display: inline; margin: 0 15px;}
		nav ul li a {color: #ffffff;text-decoration: none;}
		main {padding: 20px;}
		footer {text-align: center;padding: 10px 0;background: #35424a;color: #ffffff;position: relative;bottom: 0;width: 100%;}
	</style>
</head>
<body>
	<header>
		<h1>Admin Dashboard</h1>
		<nav>
		<ul>
		<li><a href="admin_home.php">Home</a></li>
		<li><a href="manage_employees_admin.php">Manage Employees</a></li>
		<li><a href="view_attendance_admin.php">View Attendance</a></li>
		<li><a href="attendance_today_admin.php">Mark Todays Attendance</a></li>
		<li><a href="manage_tasks_admin.php">Manage Tasks</a></li>
		<li><a href="view_tasks_admin.php">View Tasks</a></li>
		<li><a href="manage_leaves_admin.php">Manage Leaves</a></li>
		<li><a href="view_leaves_admin.php">View All Leave Records</a></li><br><br>
		<li><a href="generate_salary_slip_admin.php">Generate Employee Salary Slip</a></li>
		<li><a href="emp_analysis_admin.php">Employees Analysis</a></li>
		<li><a href="change_password_admin.php">Change Password</a></li>
		<li><a href="logout.php">Logout</a></li>
		</ul>
		</nav>
	</header>
	<main>	