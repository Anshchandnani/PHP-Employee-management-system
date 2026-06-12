<?php include 'header_admin.php'; ?>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="home.css">
</head>
<section>
    <h2>Welcome, Admin!</h2>
    <p>Manage your employees efficiently with our easy-to-use system.</p>
</section>

<section>
    <h3>Quick Links</h3>
    <ul>
        <li><a id="link" href="manage_employees_admin.php">Manage Employees</a></li>
        <li><a id="link" href="view_attendance_admin.php">View Attendance</a></li>
        <li><a id="link" href="attendance_today_admin.php">Mark Todays Attendance</a></li>
        <li><a id="link" href="manage_tasks_admin.php">Manage Tasks</a></li>
        <li><a id="link" href="view_tasks_admin.php">View Tasks</a></li>
        <li><a id="link" href="manage_leaves_admin.php">Manage Leaves</a></li>
        <li><a id="link" href="view_leaves_admin.php">View All Leave Records</a></li>
        <li><a id="link" href="generate_salary_slip_admin.php">Generate Employee Salary Slip</a></li>
        <li><a id="link" href="change_password_admin.php">Change Password</a></li>
    </ul>
</section>
<?php include 'footer_admin.php'; ?>