delete_employee_admin.php:
<?php include 'db_connection.php';
if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] == 1 && isset($_GET['emp_id'])) 
{$emp_id = intval($_GET['emp_id']);
$conn->query("DELETE FROM attendance_info WHERE emp_id = $emp_id");
$conn->query("DELETE FROM leave_info WHERE emp_id = $emp_id");
$conn->query("DELETE FROM task_info WHERE emp_id = $emp_id");
$conn->query("DELETE FROM emp_info WHERE emp_id = $emp_id");
echo "<script> alert('Employee ID $emp_id deleted successfully');
window.location.href = 'manage_employees_admin.php'; // Redirect after deletion
</script>";}?>