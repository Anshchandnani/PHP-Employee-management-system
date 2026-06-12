<?php include 'header_employee.php'; ?>
<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="home.css">
</head>
<section>
	<h2>Welcome, <?= $_SESSION['name'] ?>!</h2>
	<p>You can manage your information and keep track of your work details from here.</p>
</section>

<section>
	<h3>Quick Links</h3>
	<ul>
		<li><a id="link" href="view_tasks_emp.php">View My Tasks</a></li>
		<li><a id="link" href="view_attendance_emp.php">View My Attendance</a></li>
		<li><a id="link" href="leave_request_emp.php">Request Leave</a></li>
		<li><a id="link" href="check_leave_request.php">Check Leave Request</a></li>
		<li><a id="link" href="generate_salary_slip_emp.php">Generate Salary Slip</a></li>
		<li><a id="link" href="change_password_emp.php">Change Password</a></li>
	</ul>
</section>

<?php include 'footer_employee.php'; ?>