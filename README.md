# Employee Management System

A web-based **Employee Management System** developed using **PHP, MySQL, HTML, CSS, and JavaScript**. The system provides separate Admin and Employee interfaces for managing employee records, attendance, leaves, tasks, salary information, and account settings.

## Features

### Admin

* Admin authentication and login
* Add, manage, and remove employees
* View and manage employee attendance
* Generate attendance reports in PDF format
* Analyze employee attendance
* Manage employee leave requests
* Assign and manage employee tasks
* View employee task records
* Generate salary slips
* Change admin password
* Email notification after password change

### Employee

* Employee authentication and login
* Employee dashboard
* View attendance records
* View and download attendance reports in PDF format
* Submit leave requests
* Check leave request status
* View assigned tasks
* Generate and view salary slips
* Change account password
* Email notification after password change

## Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **PDF Generation:** PHP PDF generation library
* **Email:** PHP email functionality
* **Development Environment:** XAMPP

## Modules

### Employee Management

* Add and manage employee records
* Delete employee records
* Employee dashboard
* Employee analysis

### Attendance Management

* Record and view employee attendance
* Admin attendance monitoring
* Employee attendance history
* Attendance analysis
* Generate attendance reports in PDF format

### Leave Management

* Submit employee leave requests
* View employee leave requests
* Manage and process leave requests
* Check leave request status

### Task Management

* Assign tasks to employees
* View assigned tasks
* Track employee tasks

### Salary Management

* Generate employee salary slips
* Allow employees to view their salary slips

### Authentication & Account Management

* Separate Admin and Employee login
* Logout functionality
* Password change functionality
* Email notification after successful password change

## Project Structure

```text
PHP-Employee-management-system/
│
├── admin_login.php
├── admin_home.php
├── employee_home.php
├── login.php
├── logout.php
├── db_connection.php
│
├── manage_employees_admin.php
├── delete_employee_admin.php
│
├── attendance_today_admin.php
├── view_attendance_admin.php
├── view_attendance_emp.php
│
├── manage_leaves_admin.php
├── view_leaves_admin.php
├── leave_request_emp.php
├── check_leave_emp.php
│
├── manage_tasks_admin.php
├── view_tasks_admin.php
├── view_tasks_emp.php
│
├── generate_salary_slip_admin.php
├── generate_salary_slip_emp.php
│
├── emp_analysis_admin.php
│
├── change_password_admin.php
├── change_password_emp.php
│
├── home.css
└── ...
```

## Installation

### Prerequisites

Make sure the following software is installed:

* XAMPP
* PHP
* MySQL
* Web Browser

### Setup

1. Clone the repository:

```bash
git clone https://github.com/Anshchandnani/PHP-Employee-management-system.git
```

2. Move the project folder into the XAMPP `htdocs` directory.

3. Start **Apache** and **MySQL** from the XAMPP Control Panel.

4. Open **phpMyAdmin** and create the required MySQL database.

5. Configure the database credentials in:

```text
db_connection.php
```

6. Configure the required email settings for password-change notifications.

7. Open the project in your web browser:

```text
http://localhost/PHP-Employee-management-system/
```

## Database

The system uses **MySQL** to store and manage:

* Employee information
* Attendance records
* Leave requests
* Task information
* Salary information
* Account-related data

## Key Highlights

* Separate Admin and Employee dashboards
* Database-driven employee management
* Attendance tracking and analysis
* PDF attendance report generation
* Leave request and management system
* Employee task assignment and tracking
* Salary slip generation
* Password management
* Email notifications after password changes
* Responsive web interface

## Purpose

This project demonstrates the development of a **database-driven Employee Management System** using PHP and MySQL. It integrates multiple business operations, including employee administration, attendance tracking, leave management, task management, salary management, PDF report generation, and email-based notifications.

## Author

**Ansh Chandnani**
